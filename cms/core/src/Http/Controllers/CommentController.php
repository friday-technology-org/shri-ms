<?php

namespace Cms\Core\Http\Controllers;

use Cms\Core\Models\Comment;
use Cms\Core\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class CommentController extends Controller
{
    /**
     * Display a listing of comments in the admin dashboard.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');
        $query = Comment::with('post')->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $comments = $query->paginate(20);

        return view('cms-core::comments.index', compact('comments', 'status'));
    }

    /**
     * Store a newly created comment from frontend.
     */
    public function store(Request $request): RedirectResponse
    {
        // Simple Honeypot Anti-Spam Check
        if ($request->filled('website_verify')) {
            // Honeypot field filled -> silent discard or flag as spam
            return redirect()->back()->with('error', 'Spam detected.');
        }

        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'parent_id' => 'nullable|exists:comments,id',
            'author_name' => 'required|string|max:255',
            'author_email' => 'required|email|max:255',
            'author_url' => 'nullable|url|max:255',
            'content' => 'required|string|min:3|max:1000',
        ]);

        // Simple local keyword blacklist check
        $blacklist = ['viagra', 'casino', 'lottery', 'crypto', 'bitcoin', 'sex', 'drugs'];
        $status = 'pending';

        foreach ($blacklist as $word) {
            if (stripos($validated['content'], $word) !== false) {
                $status = 'spam';
                break;
            }
        }

        $comment = Comment::create([
            'post_id' => $validated['post_id'],
            'parent_id' => $validated['parent_id'] ?? null,
            'user_id' => auth()->id(),
            'author_name' => $validated['author_name'],
            'author_email' => $validated['author_email'],
            'author_url' => $validated['author_url'] ?? null,
            'content' => $validated['content'],
            'status' => $status,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Trigger action hook for new comments (for plugin notifications etc)
        do_action('comment_posted', $comment);

        if ($status === 'spam') {
            return redirect()->back()->with('error', 'Your comment was flagged as spam.');
        }

        return redirect()->back()->with('success', 'Your comment is pending moderation.');
    }

    /**
     * Approve a comment.
     */
    public function approve(Comment $comment): RedirectResponse
    {
        $comment->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Comment approved.');
    }

    /**
     * Mark a comment as spam.
     */
    public function spam(Comment $comment): RedirectResponse
    {
        $comment->update(['status' => 'spam']);
        return redirect()->back()->with('success', 'Comment marked as spam.');
    }

    /**
     * Delete a comment.
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();
        return redirect()->back()->with('success', 'Comment deleted.');
    }
}
