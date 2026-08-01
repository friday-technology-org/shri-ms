/**
 * Drag-and-drop widget layout builder (SortableJS-backed).
 * Widgets can be dragged within an area to reorder, or between areas to
 * reassign. "Save Layout" serializes every area's widget id order into a
 * hidden JSON field before submitting a plain POST — settings/title edits
 * are handled by each widget's own inline form, never touched here.
 */
(function () {
    'use strict';

    const lists = document.querySelectorAll('.widget-area-list');
    const form = document.getElementById('widgets-reorder-form');
    const jsonField = document.getElementById('widgets_items_json');

    if (!lists.length || !form || !jsonField) return;

    lists.forEach(list => {
        Sortable.create(list, {
            group: 'widget-areas',
            animation: 150,
            handle: '.drag-handle',
        });
    });

    form.addEventListener('submit', function () {
        const items = [];
        document.querySelectorAll('.widget-area-list').forEach(list => {
            const areaKey = list.dataset.areaKey;
            Array.from(list.children).forEach((card, index) => {
                items.push({
                    id: card.dataset.widgetId,
                    area_key: areaKey,
                    sort_order: index,
                });
            });
        });
        jsonField.value = JSON.stringify(items);
    });
})();
