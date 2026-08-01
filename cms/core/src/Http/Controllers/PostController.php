<?php

namespace Cms\Core\Http\Controllers;

use Cms\Core\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::where('post_type', 'post')->with('author');
        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }
        $posts = $query->latest()->paginate(20)->appends($request->only('search'));
        return view('cms-core::posts.index', compact('posts', 'search'));
    }

    public function create()
    {
        $fieldGroups = \Cms\Core\Models\FieldGroup::getForPost('post');
            
        return view('cms-core::posts.create', compact('fieldGroups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'              => 'required|string|max:255',
            'content'            => 'nullable|string',
            'excerpt'            => 'nullable|string',
            'status'             => 'required|in:draft,published,trashed',
            'post_type'          => 'nullable|string',
            'featured_image_id'  => 'nullable|integer|exists:media,id',
        ]);

        $post = new Post($validated);
        $post->author_id = auth()->id();
        
        if (!isset($validated['post_type'])) {
            $post->post_type = 'post';
        }

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

        return redirect()->route('cms.posts.edit', $post->id)->with('success', 'Post created successfully.');
    }

    public function edit(Post $post)
    {
        $fieldGroups = \Cms\Core\Models\FieldGroup::getForPost('post', $post);
            
        return view('cms-core::posts.edit', compact('post', 'fieldGroups'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'content'           => 'nullable|string',
            'excerpt'           => 'nullable|string',
            'status'            => 'required|in:draft,published,trashed',
            'featured_image_id' => 'nullable|integer|exists:media,id',
        ]);

        if ($post->status !== 'published' && $validated['status'] === 'published') {
            $post->published_at = now();
        }

        $post->update($validated);

        if ($request->has('meta')) {
            foreach ($request->input('meta') as $key => $value) {
                $post->updateMeta($key, $value);
            }
        }

        if ($request->has('terms')) {
            $post->terms()->sync($request->input('terms'));
        } else {
            $post->terms()->detach();
        }

        return redirect()->route('cms.posts.edit', $post->id)->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('cms.posts.index')->with('success', 'Post moved to trash.');
    }
}
