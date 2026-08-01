<?php

namespace Cms\Core\Http\Controllers;

use Cms\Core\Models\Menu;
use Cms\Core\Models\Widget;
use Cms\Core\Models\WidgetArea;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WidgetController extends Controller
{
    /**
     * Built-in widget types available in the "Add Widget" palette.
     */
    public array $types = [
        'recent_posts' => 'Recent Posts',
        'categories' => 'Categories',
        'tag_cloud' => 'Tag Cloud',
        'search' => 'Search Bar',
        'custom_html' => 'Custom HTML',
        'image_banner' => 'Image / Banner',
        'social_icons' => 'Social Icons',
        'nav_menu' => 'Navigation Menu',
    ];

    public function index(): View
    {
        $areas = WidgetArea::with('widgets')->orderBy('id')->get();
        $types = $this->types;
        $menus = Menu::orderBy('name')->get();

        return view('cms-core::widgets.index', compact('areas', 'types', 'menus'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'area_key' => 'required|string|exists:widget_areas,key',
            'type' => 'required|string|in:' . implode(',', array_keys($this->types)),
            'title' => 'nullable|string|max:255',
            'settings' => 'nullable|array',
        ]);

        $maxOrder = Widget::where('area_key', $validated['area_key'])->max('sort_order');

        Widget::create([
            'area_key' => $validated['area_key'],
            'type' => $validated['type'],
            'title' => $validated['title'] ?? null,
            'settings' => $validated['settings'] ?? [],
            'sort_order' => $maxOrder !== null ? $maxOrder + 1 : 0,
        ]);

        return redirect()->route('cms.widgets.index')->with('success', 'Widget added.');
    }

    public function update(Request $request, Widget $widget): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'settings' => 'nullable|array',
            'is_active' => 'nullable|boolean',
        ]);

        $widget->update([
            'title' => $validated['title'] ?? null,
            'settings' => $validated['settings'] ?? [],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('cms.widgets.index')->with('success', 'Widget updated.');
    }

    public function destroy(Widget $widget): RedirectResponse
    {
        $widget->delete();
        return redirect()->route('cms.widgets.index')->with('success', 'Widget removed.');
    }

    /**
     * Bulk-save widget order/area assignment across all areas.
     * Only touches area_key/sort_order — never a widget's own `settings`.
     */
    public function reorder(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate(['items_json' => 'required|string']);

        $items = json_decode($request->input('items_json'), true) ?: [];

        $validAreaKeys = WidgetArea::pluck('key')->all();

        DB::transaction(function () use ($items, $validAreaKeys) {
            foreach ($items as $item) {
                if (empty($item['id']) || empty($item['area_key']) || !in_array($item['area_key'], $validAreaKeys, true)) {
                    continue;
                }

                Widget::where('id', $item['id'])->update([
                    'area_key' => $item['area_key'],
                    'sort_order' => (int) ($item['sort_order'] ?? 0),
                ]);
            }
        });

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('cms.widgets.index')->with('success', 'Widget layout saved.');
    }
}
