<div>
    <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-1">Menu to display</p>
    <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
        <select name="settings[menu_id]" class="select w-full bg-transparent text-sm text-gray-800 dark:text-white h-fit min-h-fit py-2 px-3 focus:outline-none">
            <option value="" class="bg-white dark:bg-dark-neutral-bg">— Select a menu —</option>
            @foreach($menus as $menu)
                <option value="{{ $menu->id }}" class="bg-white dark:bg-dark-neutral-bg" {{ ($settings['menu_id'] ?? null) == $menu->id ? 'selected' : '' }}>{{ $menu->name }}</option>
            @endforeach
        </select>
    </div>
</div>
