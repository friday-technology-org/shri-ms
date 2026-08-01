<?php

namespace Cms\Core\Http\Controllers;

use Cms\Core\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::where('post_type', 'page')->with('author');
        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }
        $pages = $query->latest()->paginate(20)->appends($request->only('search'));
        return view('cms-core::pages.index', compact('pages', 'search'));
    }

    public function create()
    {
        $fieldGroups = \Cms\Core\Models\FieldGroup::getForPost('page');
            
        return view('cms-core::pages.create', compact('fieldGroups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'excerpt' => 'nullable|string',
            'status' => 'required|in:draft,published,trashed',
            'featured_image_id' => 'nullable|integer|exists:media,id',
        ]);

        $page = new Post($validated);
        $page->author_id = auth()->id();
        $page->post_type = 'page';

        if ($page->status === 'published') {
            $page->published_at = now();
        }

        $page->save();

        if ($request->has('meta')) {
            foreach ($request->input('meta') as $key => $value) {
                $page->updateMeta($key, $value);
            }
        }

        if ($request->has('terms')) {
            $page->terms()->sync($request->input('terms'));
        }

        return redirect()->route('cms.pages.edit', $page->id)->with('success', 'Page created successfully.');
    }

    public function edit(Post $page)
    {
        // Ensure it's a page
        if ($page->post_type !== 'page') {
            abort(404);
        }

        $fieldGroups = \Cms\Core\Models\FieldGroup::getForPost('page', $page);

        return view('cms-core::pages.edit', compact('page', 'fieldGroups'));
    }

    public function update(Request $request, Post $page)
    {
        // Ensure it's a page
        if ($page->post_type !== 'page') {
            abort(404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'excerpt' => 'nullable|string',
            'status' => 'required|in:draft,published,trashed',
            'featured_image_id' => 'nullable|integer|exists:media,id',
        ]);

        if ($page->status !== 'published' && $validated['status'] === 'published') {
            $page->published_at = now();
        }

        $page->update($validated);

        if ($request->has('meta')) {
            foreach ($request->input('meta') as $key => $value) {
                $page->updateMeta($key, $value);
            }
        }

        if ($request->has('terms')) {
            $page->terms()->sync($request->input('terms'));
        } else {
            $page->terms()->detach();
        }

        return redirect()->route('cms.pages.edit', $page->id)->with('success', 'Page updated successfully.');
    }

    public function destroy(Post $page)
    {
        // Ensure it's a page
        if ($page->post_type !== 'page') {
            abort(404);
        }
        
        $page->delete();
        return redirect()->route('cms.pages.index')->with('success', 'Page moved to trash.');
    }
}
