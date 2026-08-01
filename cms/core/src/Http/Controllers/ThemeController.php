<?php

namespace Cms\Core\Http\Controllers;

use Cms\Core\Models\Theme;
use Cms\Core\Services\ThemeInstallerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ThemeController extends Controller
{
    public function __construct(protected ThemeInstallerService $installer) {}

    public function index(): View
    {
        $themes = Theme::all();

        return view('cms-core::themes.index', compact('themes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'theme_zip' => 'required|file|mimes:zip|max:20480',
        ]);

        try {
            $theme = $this->installer->install($request->file('theme_zip'));
            return redirect()->route('cms.themes.index')->with('success', "Theme \"{$theme->name}\" installed successfully.");
        } catch (\Exception $e) {
            return back()->withErrors(['theme_zip' => $e->getMessage()]);
        }
    }

    public function activate(string $themeSlug): RedirectResponse
    {
        $theme = Theme::find($themeSlug);
        if (!$theme) {
            abort(404);
        }

        \Cms\Core\Models\Option::set('active_theme', $themeSlug);

        return redirect()->route('cms.themes.index')->with('success', "\"{$theme->name}\" is now the active theme.");
    }

    public function destroy(string $themeSlug): RedirectResponse
    {
        $theme = Theme::find($themeSlug);
        if (!$theme) {
            abort(404);
        }

        if ($theme->is_active) {
            return redirect()->route('cms.themes.index')->with('error', "Cannot delete the active theme.");
        }

        \Illuminate\Support\Facades\File::deleteDirectory($theme->path());
        return redirect()->route('cms.themes.index')->with('success', "\"{$theme->name}\" has been deleted.");
    }

    public function preview(string $themeSlug)
    {
        $theme = Theme::find($themeSlug);
        if (!$theme) {
            abort(404);
        }

        return redirect()->to(url('/?cms_preview_theme=' . $theme->slug));
    }
}
