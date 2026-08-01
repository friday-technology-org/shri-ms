<?php

namespace Cms\Core\Http\Controllers;

use Cms\Core\Models\Menu;
use Cms\Core\Models\Post;
use Cms\Core\Models\PostType;
use Cms\Core\Models\Taxonomy;
use Cms\Core\Models\Term;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MenuController extends Controller
{
    /**
     * Get registered menu locations.
     */
    public function getLocations(): array
    {
        return apply_filters('cms_menu_locations', [
            'primary' => 'Primary Header',
            'top_bar' => 'Top Bar',
            'footer' => 'Footer',
            'mobile_drawer' => 'Mobile Drawer',
        ]);
    }

    public function index(): View
    {
        $menus = Menu::orderBy('name')->get();
        $locations = $this->getLocations();

        return view('cms-core::menus.index', compact('menus', 'locations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|in:' . implode(',', array_keys($this->getLocations())) . '|unique:menus,location',
        ]);

        $menu = Menu::create([
            'name' => $validated['name'],
            'slug' => \Illuminate\Support\Str::slug($validated['name']) . '-' . uniqid(),
            'location' => $validated['location'] ?? null,
        ]);

        return redirect()->route('cms.menus.edit', $menu)->with('success', 'Menu created.');
    }

    public function edit(Menu $menu): View
    {
        $tree = $menu->tree();
        $postTypes = PostType::orderBy('plural_label')->get();
        $taxonomies = Taxonomy::orderBy('name')->get();
        $locations = $this->getLocations();

        return view('cms-core::menus.edit', compact('menu', 'tree', 'postTypes', 'taxonomies', 'locations'));
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|in:' . implode(',', array_keys($this->getLocations())) . '|unique:menus,location,' . $menu->id,
            'items_json' => 'nullable|string',
        ]);

        $menu->update([
            'name' => $validated['name'],
            'location' => $validated['location'] ?? null,
        ]);

        $nodes = [];
        if (!empty($validated['items_json'])) {
            $decoded = json_decode($validated['items_json'], true);
            if (is_array($decoded)) {
                $nodes = $decoded;
            }
        }

        DB::transaction(function () use ($menu, $nodes) {
            $menu->items()->delete();
            $order = 0;
            $this->saveNodes($menu, $nodes, null, $order);
        });

        return redirect()->route('cms.menus.edit', $menu)->with('success', 'Menu saved.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        $menu->delete();
        return redirect()->route('cms.menus.index')->with('success', 'Menu deleted.');
    }

    /**
     * AJAX source list for the builder's "Add items" left panel.
     * ?source=pages | posts | cpt:{name} | terms:{taxonomyId}
     */
    public function itemLookup(Request $request): JsonResponse
    {
        $source = (string) $request->query('source', '');
        $items = [];

        if ($source === 'pages') {
            $items = Post::where('post_type', 'page')->where('status', 'published')
                ->orderBy('title')->get(['id', 'title'])
                ->map(fn ($p) => ['type' => 'post', 'object_id' => $p->id, 'label' => $p->title]);
        } elseif ($source === 'posts') {
            $items = Post::where('post_type', 'post')->where('status', 'published')
                ->orderBy('title')->get(['id', 'title'])
                ->map(fn ($p) => ['type' => 'post', 'object_id' => $p->id, 'label' => $p->title]);
        } elseif (str_starts_with($source, 'cpt:')) {
            $postType = substr($source, 4);
            $items = Post::where('post_type', $postType)->where('status', 'published')
                ->orderBy('title')->get(['id', 'title'])
                ->map(fn ($p) => ['type' => 'post', 'object_id' => $p->id, 'label' => $p->title]);
        } elseif (str_starts_with($source, 'terms:')) {
            $taxonomyId = (int) substr($source, 6);
            $items = Term::where('taxonomy_id', $taxonomyId)->orderBy('name')->get(['id', 'name'])
                ->map(fn ($t) => ['type' => 'term', 'object_id' => $t->id, 'label' => $t->name]);
        }

        return response()->json(['items' => $items]);
    }

    // ─── Helper ──────────────────────────────────────────────────────────────

    protected function saveNodes(Menu $menu, array $nodes, ?int $parentId, int &$order): void
    {
        foreach ($nodes as $node) {
            if (empty($node['label'])) {
                continue;
            }

            $item = $menu->items()->create([
                'parent_id' => $parentId,
                'type' => $node['type'] ?? 'custom',
                'object_id' => $node['object_id'] ?? null,
                'label' => $node['label'],
                'url' => $node['url'] ?? null,
                'target_blank' => !empty($node['target_blank']),
                'css_class' => $node['css_class'] ?? null,
                'rel_nofollow' => !empty($node['rel_nofollow']),
                'is_mega_menu' => !empty($node['is_mega_menu']),
                'mega_menu_settings' => $node['mega_menu_settings'] ?? null,
                'sort_order' => $order++,
            ]);

            if (!empty($node['children']) && is_array($node['children'])) {
                $this->saveNodes($menu, $node['children'], $item->id, $order);
            }
        }
    }
}
