/**
 * Drag-and-drop nested menu builder (SortableJS-backed).
 * Vanilla JS — no build step, matching this admin's other per-page scripts
 * (Quill/TinyMCE-before-it were loaded the same way).
 */
(function () {
    'use strict';

    const SORTABLE_GROUP = 'menu-items';
    const tree = document.getElementById('menu-tree');
    const template = document.getElementById('menu-item-template');
    const emptyLabel = document.getElementById('menu-tree-empty');
    const form = document.getElementById('menu-builder-form');
    const itemsJsonField = document.getElementById('items_json');

    if (!tree || !template || !form) return;

    function initSortable(list) {
        if (!list || list.dataset.sortableInit) return;
        list.dataset.sortableInit = '1';
        Sortable.create(list, {
            group: SORTABLE_GROUP,
            animation: 150,
            handle: '.drag-handle',
            fallbackOnBody: true,
            swapThreshold: 0.65,
            onAdd: updateEmptyState,
            onRemove: updateEmptyState,
        });
    }

    function initAllSortables() {
        initSortable(tree);
        tree.querySelectorAll('.mi-children').forEach(initSortable);
    }

    function updateEmptyState() {
        if (emptyLabel) {
            emptyLabel.style.display = tree.children.length ? 'none' : '';
        }
    }

    function createItemNode({ type, objectId, label, url }) {
        const fragment = template.content.cloneNode(true);
        const li = fragment.querySelector('.menu-item-node');

        li.dataset.type = type;
        li.dataset.objectId = objectId || '';

        li.querySelector('.mi-label').value = label || '';
        const typeLabelEl = li.querySelector('.mi-type-label');
        if (typeLabelEl) typeLabelEl.textContent = type;

        const urlInput = li.querySelector('.mi-url');
        if (urlInput) {
            urlInput.value = url || '';
            urlInput.classList.toggle('hidden', type !== 'custom');
        }

        return li;
    }

    function addItemToTree(data) {
        const li = createItemNode(data);
        tree.appendChild(li);
        initSortable(li.querySelector('.mi-children'));
        updateEmptyState();
    }

    // ─── Event delegation on the tree (remove / toggle settings) ──────────

    tree.addEventListener('click', function (e) {
        const removeBtn = e.target.closest('.mi-remove');
        if (removeBtn) {
            const li = removeBtn.closest('.menu-item-node');
            li.parentElement.removeChild(li);
            updateEmptyState();
            return;
        }

        const toggleBtn = e.target.closest('.mi-toggle-settings');
        if (toggleBtn) {
            const li = toggleBtn.closest('.menu-item-node');
            const settings = li.querySelector(':scope > .mi-settings');
            settings.classList.toggle('hidden');
        }
    });

    // ─── Left panel: source tabs + lookup list ─────────────────────────────

    const tabs = document.querySelectorAll('.menu-src-tab');
    const listEl = document.getElementById('menu-source-list');
    const customForm = document.getElementById('menu-custom-link-form');

    function activateTab(tab) {
        tabs.forEach(t => {
            t.classList.remove('border-color-brands', 'text-color-brands');
            t.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-dark-500');
        });
        tab.classList.add('border-color-brands', 'text-color-brands');
        tab.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-dark-500');

        const source = tab.dataset.source;
        if (source === 'custom') {
            listEl.classList.add('hidden');
            customForm.classList.remove('hidden');
        } else {
            customForm.classList.add('hidden');
            listEl.classList.remove('hidden');
            loadSourceList(source);
        }
    }

    async function loadSourceList(source) {
        listEl.innerHTML = '<p class="text-xs text-gray-400 dark:text-gray-dark-500">Loading…</p>';
        try {
            const res = await fetch(window.cmsMenuLookupUrl + '?source=' + encodeURIComponent(source), {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();
            renderSourceList(data.items || []);
        } catch (e) {
            listEl.innerHTML = '<p class="text-xs text-red-500">Failed to load.</p>';
        }
    }

    function renderSourceList(items) {
        if (!items.length) {
            listEl.innerHTML = '<p class="text-xs text-gray-400 dark:text-gray-dark-500">Nothing found.</p>';
            return;
        }

        listEl.innerHTML = '';
        items.forEach(item => {
            const row = document.createElement('div');
            row.className = 'flex items-center justify-between gap-2 py-2 border-b border-[#E8EDF2] dark:border-[#313442] last:border-0';
            row.innerHTML =
                '<span class="text-sm text-gray-700 dark:text-gray-dark-1100 truncate">' + escapeHtml(item.label) + '</span>' +
                '<button type="button" class="mi-add-source text-color-brands text-xs font-medium px-2 py-1 hover:opacity-75">+ Add</button>';

            row.querySelector('.mi-add-source').addEventListener('click', function () {
                addItemToTree({ type: item.type, objectId: item.object_id, label: item.label });
            });

            listEl.appendChild(row);
        });
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    tabs.forEach(tab => tab.addEventListener('click', () => activateTab(tab)));
    if (tabs.length) activateTab(tabs[0]);

    const addCustomBtn = document.getElementById('add-custom-link');
    if (addCustomBtn) {
        addCustomBtn.addEventListener('click', function () {
            const labelInput = document.getElementById('custom-link-label');
            const urlInput = document.getElementById('custom-link-url');
            if (!labelInput.value.trim() || !urlInput.value.trim()) return;

            addItemToTree({ type: 'custom', objectId: null, label: labelInput.value.trim(), url: urlInput.value.trim() });
            labelInput.value = '';
            urlInput.value = '';
        });
    }

    // ─── Serialize tree to JSON on submit ───────────────────────────────────

    function serializeList(listEl) {
        const nodes = [];
        listEl.querySelectorAll(':scope > .menu-item-node').forEach(li => {
            nodes.push({
                type: li.dataset.type,
                object_id: li.dataset.objectId || null,
                label: li.querySelector(':scope > .menu-item-row .mi-label').value,
                url: li.querySelector(':scope > .mi-settings .mi-url')?.value || null,
                target_blank: li.querySelector(':scope > .mi-settings .mi-target-blank')?.checked || false,
                css_class: li.querySelector(':scope > .mi-settings .mi-css-class')?.value || null,
                rel_nofollow: li.querySelector(':scope > .mi-settings .mi-rel-nofollow')?.checked || false,
                children: serializeList(li.querySelector(':scope > .mi-children')),
            });
        });
        return nodes;
    }

    form.addEventListener('submit', function () {
        itemsJsonField.value = JSON.stringify(serializeList(tree));
    });

    initAllSortables();
    updateEmptyState();
})();
