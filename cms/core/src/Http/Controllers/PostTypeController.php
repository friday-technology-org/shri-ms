<?php

namespace Cms\Core\Http\Controllers;

use Cms\Core\Models\PostType;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

class PostTypeController extends Controller
{
    public function index()
    {
        $postTypes = PostType::latest()->paginate(20);
        return view('cms-core::post-types.index', compact('postTypes'));
    }

    public function create()
    {
        return view('cms-core::post-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'singular_label' => 'required|string|max:255',
            'plural_label' => 'required|string|max:255',
            'name' => 'nullable|string|max:255|unique:post_types,name',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'is_hierarchical' => 'nullable|boolean',
            'has_archive' => 'nullable|boolean',
            'supports' => 'nullable|array',
        ]);

        if (empty($validated['name'])) {
            $validated['name'] = Str::slug($validated['plural_label']);
        }

        $validated['is_hierarchical'] = $request->has('is_hierarchical');
        $validated['has_archive'] = $request->has('has_archive');

        PostType::create($validated);

        return redirect()->route('cms.post-types.index')->with('success', 'Custom Post Type created successfully.');
    }

    public function edit(PostType $postType)
    {
        return view('cms-core::post-types.edit', compact('postType'));
    }

    public function update(Request $request, PostType $postType)
    {
        $validated = $request->validate([
            'singular_label' => 'required|string|max:255',
            'plural_label' => 'required|string|max:255',
            'name' => 'required|string|max:255|unique:post_types,name,' . $postType->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'is_hierarchical' => 'nullable|boolean',
            'has_archive' => 'nullable|boolean',
            'supports' => 'nullable|array',
        ]);

        $validated['is_hierarchical'] = $request->has('is_hierarchical');
        $validated['has_archive'] = $request->has('has_archive');

        $postType->update($validated);

        return redirect()->route('cms.post-types.index')->with('success', 'Custom Post Type updated successfully.');
    }

    public function destroy(PostType $postType)
    {
        $postType->delete();
        return redirect()->route('cms.post-types.index')->with('success', 'Custom Post Type deleted.');
    }
}
