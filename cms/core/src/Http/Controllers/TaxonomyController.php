<?php

namespace Cms\Core\Http\Controllers;

use Cms\Core\Models\Taxonomy;
use Cms\Core\Models\PostType;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

class TaxonomyController extends Controller
{
    public function index()
    {
        $taxonomies = Taxonomy::latest()->paginate(20);
        return view('cms-core::taxonomies.index', compact('taxonomies'));
    }

    public function create()
    {
        $postTypes = PostType::all();
        return view('cms-core::taxonomies.create', compact('postTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:taxonomies,slug',
            'description' => 'nullable|string',
            'hierarchical' => 'nullable|boolean',
            'post_types' => 'nullable|array',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['hierarchical'] = $request->has('hierarchical');

        Taxonomy::create($validated);

        return redirect()->route('cms.taxonomies.index')->with('success', 'Taxonomy created successfully.');
    }

    public function edit(Taxonomy $taxonomy)
    {
        $postTypes = PostType::all();
        return view('cms-core::taxonomies.edit', compact('taxonomy', 'postTypes'));
    }

    public function update(Request $request, Taxonomy $taxonomy)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:taxonomies,slug,' . $taxonomy->id,
            'description' => 'nullable|string',
            'hierarchical' => 'nullable|boolean',
            'post_types' => 'nullable|array',
        ]);

        $validated['hierarchical'] = $request->has('hierarchical');

        $taxonomy->update($validated);

        return redirect()->route('cms.taxonomies.index')->with('success', 'Taxonomy updated successfully.');
    }

    public function destroy(Taxonomy $taxonomy)
    {
        $taxonomy->delete();
        return redirect()->route('cms.taxonomies.index')->with('success', 'Taxonomy deleted.');
    }
}
