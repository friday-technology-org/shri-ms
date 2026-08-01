<?php

namespace Cms\Core\Http\Controllers;

use Cms\Core\Models\Comment;
use Cms\Core\Models\Post;
use Cms\Core\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends Controller
{
    /**
     * Display general tabs for core settings and tools.
     */
    public function index(): View
    {
        return view('cms-core::settings.general');
    }

    /**
     * Update CMS settings options.
     */
    public function update(Request $request): RedirectResponse
    {
        if ($request->has('user_locale') && auth()->check()) {
            auth()->user()->update(['locale' => $request->input('user_locale')]);
        }

        $options = $request->except(['_token', 'gdpr_action', 'gdpr_email', 'user_locale']);

        foreach ($options as $key => $value) {
            // Store as string or boolean
            if (is_array($value)) {
                $value = json_encode($value);
            }
            update_cms_option($key, $value);
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Download personal data export as JSON.
     */
    public function exportData(Request $request): Response
    {
        $request->validate([
            'gdpr_email' => 'required|email',
        ]);

        $email = $request->input('gdpr_email');
        $user = User::where('email', $email)->first();

        $data = [
            'exported_at' => now()->toIso8601String(),
            'subject_email' => $email,
            'user_profile' => $user ? [
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at?->toIso8601String(),
            ] : null,
            'comments' => Comment::where('author_email', $email)->get()->map(function ($c) {
                return [
                    'id' => $c->id,
                    'author_name' => $c->author_name,
                    'content' => $c->content,
                    'ip_address' => $c->ip_address,
                    'created_at' => $c->created_at?->toIso8601String(),
                ];
            }),
            'posts' => Post::whereHas('author', function ($q) use ($email) {
                $q->where('email', $email);
            })->get()->map(function ($p) {
                return [
                    'id' => $p->id,
                    'title' => $p->title,
                    'slug' => $p->slug,
                    'created_at' => $p->created_at?->toIso8601String(),
                ];
            }),
        ];

        return response()->json($data, 200, [
            'Content-Disposition' => 'attachment; filename="gdpr-export-' . str_replace('@', '-', $email) . '.json"',
        ]);
    }

    /**
     * Erase/anonymize personal data.
     */
    public function eraseData(Request $request): RedirectResponse
    {
        $request->validate([
            'gdpr_email' => 'required|email',
        ]);

        $email = $request->input('gdpr_email');

        DB::transaction(function () use ($email) {
            // 1. Anonymize comments
            Comment::where('author_email', $email)->update([
                'author_name' => 'Anonymous',
                'author_email' => 'anonymous@example.com',
                'author_url' => null,
                'ip_address' => '0.0.0.0',
                'user_agent' => null,
            ]);

            // 2. Anonymize user if exists (except admin)
            $user = User::where('email', $email)->where('role', '!=', 'admin')->first();
            if ($user) {
                $user->update([
                    'name' => 'Deleted User',
                    'email' => 'deleted-' . uniqid() . '@example.com',
                    'password' => bcrypt(uniqid()),
                ]);
            }
        });

        return redirect()->back()->with('success', 'Personal data has been successfully anonymized.');
    }
}
