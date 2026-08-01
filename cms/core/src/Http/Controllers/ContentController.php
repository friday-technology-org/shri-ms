<?php

namespace Cms\Core\Http\Controllers;

use Cms\Core\Models\Post;
use Cms\Core\Models\PostType;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ContentController extends Controller
{
    public function index(Request $request, string $postTypeSlug)
    {
        $cpt = PostType::where('name', $postTypeSlug)->firstOrFail();
        
        $query = Post::where('post_type', $postTypeSlug)->with('author');
        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }
        $posts = $query->latest()->paginate(20)->appends($request->only('search'));
        return view('cms-core::content.index', compact('posts', 'cpt', 'search'));
    }

    public function create(string $postTypeSlug)
    {
        $cpt = PostType::where('name', $postTypeSlug)->firstOrFail();

        $fieldGroups = \Cms\Core\Models\FieldGroup::getForPost($postTypeSlug);
            
        return view('cms-core::content.create', compact('cpt', 'fieldGroups'));
    }

    public function store(Request $request, string $postTypeSlug)
    {
        $cpt = PostType::where('name', $postTypeSlug)->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'excerpt' => 'nullable|string',
            'status' => 'required|in:draft,published,trashed',
            'featured_image_id' => 'nullable|integer|exists:media,id',
        ]);

        $post = new Post($validated);
        $post->author_id = auth()->id();
        $post->post_type = $postTypeSlug;

        if ($post->status === 'published') {
            $post->published_at = now();
        }

        $post->save();

        if ($request->has('meta')) {
            foreach ($request->input('meta') as $key => $value) {
                $post->updateMeta($key, $value);
            }
        }

        if ($request->has('terms')) {
            $post->terms()->sync($request->input('terms'));
        }

        return redirect()->route('cms.content.edit', [$postTypeSlug, $post->id])->with('success', $cpt->singular_label . ' created successfully.');
    }

    public function edit(string $postTypeSlug, Post $content)
    {
        $cpt = PostType::where('name', $postTypeSlug)->firstOrFail();

        if ($content->post_type !== $postTypeSlug) {
            abort(404);
        }

        $fieldGroups = \Cms\Core\Models\FieldGroup::getForPost($postTypeSlug, $content);
            
        return view('cms-core::content.edit', compact('content', 'cpt', 'fieldGroups'));
    }

    public function update(Request $request, string $postTypeSlug, Post $content)
    {
        $cpt = PostType::where('name', $postTypeSlug)->firstOrFail();

        if ($content->post_type !== $postTypeSlug) {
            abort(404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'excerpt' => 'nullable|string',
            'status' => 'required|in:draft,published,trashed',
            'featured_image_id' => 'nullable|integer|exists:media,id',
        ]);

        if ($content->status !== 'published' && $validated['status'] === 'published') {
            $content->published_at = now();
        }

        // Save a revision before updating
        \Cms\Core\Models\PostRevision::create([
            'post_id' => $content->id,
            'author_id' => auth()->id() ?: $content->author_id,
            'title' => $content->title,
            'content' => $content->content,
            'excerpt' => $content->excerpt,
        ]);

        $content->update($validated);

        if ($request->has('meta')) {
            foreach ($request->input('meta') as $key => $value) {
                $content->updateMeta($key, $value);
            }
        }

        if ($request->has('terms')) {
            $content->terms()->sync($request->input('terms'));
        } else {
            $content->terms()->detach();
        }

        return redirect()->route('cms.content.edit', [$postTypeSlug, $content->id])->with('success', $cpt->singular_label . ' updated successfully.');
    }

    public function destroy(string $postTypeSlug, Post $content)
    {
        $cpt = PostType::where('name', $postTypeSlug)->firstOrFail();
        
        if ($content->post_type !== $postTypeSlug) {
            abort(404);
        }

        $content->delete();
        return redirect()->route('cms.content.index', $postTypeSlug)->with('success', $cpt->singular_label . ' moved to trash.');
    }

    public function revisions(string $postTypeSlug, Post $content)
    {
        $cpt = PostType::where('name', $postTypeSlug)->firstOrFail();
        
        if ($content->post_type !== $postTypeSlug) {
            abort(404);
        }

        $revisions = $content->revisions()->with('author')->paginate(10);
        return view('cms-core::content.revisions', compact('content', 'cpt', 'revisions'));
    }

    public function restoreRevision(Request $request, string $postTypeSlug, Post $content, \Cms\Core\Models\PostRevision $revision)
    {
        $cpt = PostType::where('name', $postTypeSlug)->firstOrFail();
        
        if ($content->post_type !== $postTypeSlug || $revision->post_id !== $content->id) {
            abort(404);
        }

        // Save current state as a revision before restoring
        \Cms\Core\Models\PostRevision::create([
            'post_id' => $content->id,
            'author_id' => auth()->id() ?: $content->author_id,
            'title' => $content->title,
            'content' => $content->content,
            'excerpt' => $content->excerpt,
        ]);

        $content->update([
            'title' => $revision->title,
            'content' => $revision->content,
            'excerpt' => $revision->excerpt,
        ]);

        return redirect()->route('cms.content.edit', [$postTypeSlug, $content->id])->with('success', 'Revision restored successfully.');
    }
}
