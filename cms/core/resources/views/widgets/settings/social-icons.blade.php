@php
    $platforms = ['facebook' => 'Facebook', 'twitter' => 'Twitter / X', 'instagram' => 'Instagram', 'linkedin' => 'LinkedIn', 'youtube' => 'YouTube'];
@endphp
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
    @foreach($platforms as $key => $label)
    <div>
        <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-1">{{ $label }} URL</p>
        <input type="text" name="settings[{{ $key }}]" value="{{ $settings[$key] ?? '' }}" placeholder="https://{{ $key }}.com/..."
            class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442] bg-transparent text-sm text-gray-800 dark:text-white py-2 px-3 w-full focus:outline-none">
    </div>
    @endforeach
</div>
