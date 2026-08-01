@extends('cms-core::layouts.admin')

@section('title', 'Edit Translations - LaraCMS')

@section('content')
<div>
    <div class="flex justify-between flex-col gap-y-3 mb-[24px] md:flex-row">
        <div>
            <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Edit Translations [{{ strtoupper($locale) }}]</h2>
            <div class="flex items-center text-xs gap-x-[11px]">
                <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}" alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div>
                <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
                <span class="capitalize text-gray-500 dark:text-gray-dark-500">Translations</span>
                <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
                <span class="capitalize text-color-brands">Edit Strings</span>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative mb-4" role="alert">
        <span class="block sm:inline font-semibold">{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-white dark:bg-dark-neutral-bg border border-neutral dark:border-dark-neutral-border p-6 rounded-2xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-1100 dark:text-white pb-3">Translation Keys</h3>
            <button type="button" onclick="addNewTranslationRow()" class="text-sm font-semibold py-2 px-4 rounded-xl bg-color-brands text-white hover:opacity-90">+ Add String Key</button>
        </div>

        <form action="{{ route('cms.translations.update', $locale) }}" method="POST">
            @csrf
            
            <div id="translation-rows" class="space-y-4 mb-6">
                @forelse($translations as $key => $val)
                <div class="flex items-center gap-4 translation-row">
                    <input type="text" name="keys[]" value="{{ $key }}" placeholder="Translation Key" class="w-1/2 border border-[#E8EDF2] dark:border-[#313442] bg-transparent rounded-xl p-3 text-sm text-gray-1100 dark:text-white" required>
                    <input type="text" name="values[]" value="{{ $val }}" placeholder="Translated Text" class="w-1/2 border border-[#E8EDF2] dark:border-[#313442] bg-transparent rounded-xl p-3 text-sm text-gray-1100 dark:text-white">
                    <button type="button" onclick="this.parentElement.remove()" class="text-red-500 font-bold hover:text-red-700 px-2">&times;</button>
                </div>
                @empty
                <div class="flex items-center gap-4 translation-row">
                    <input type="text" name="keys[]" placeholder="Translation Key (e.g. hello)" class="w-1/2 border border-[#E8EDF2] dark:border-[#313442] bg-transparent rounded-xl p-3 text-sm text-gray-1100 dark:text-white" required>
                    <input type="text" name="values[]" placeholder="Translated Text (e.g. Bonjour)" class="w-1/2 border border-[#E8EDF2] dark:border-[#313442] bg-transparent rounded-xl p-3 text-sm text-gray-1100 dark:text-white">
                    <button type="button" onclick="this.parentElement.remove()" class="text-red-500 font-bold hover:text-red-700 px-2">&times;</button>
                </div>
                @endforelse
            </div>

            <div class="flex gap-4">
                <button type="submit" class="text-sm font-semibold py-3 px-6 rounded-xl bg-color-brands text-white hover:opacity-90">Save Translations</button>
                <a href="{{ route('cms.translations.index') }}" class="text-sm font-semibold py-3 px-6 rounded-xl bg-gray-100 dark:bg-dark-neutral-border text-gray-700 dark:text-white hover:opacity-90">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
function addNewTranslationRow() {
    const container = document.getElementById('translation-rows');
    const row = document.createElement('div');
    row.className = 'flex items-center gap-4 translation-row';
    row.innerHTML = `
        <input type="text" name="keys[]" placeholder="Translation Key" class="w-1/2 border border-[#E8EDF2] dark:border-[#313442] bg-transparent rounded-xl p-3 text-sm text-gray-1100 dark:text-white" required>
        <input type="text" name="values[]" placeholder="Translated Text" class="w-1/2 border border-[#E8EDF2] dark:border-[#313442] bg-transparent rounded-xl p-3 text-sm text-gray-1100 dark:text-white">
        <button type="button" onclick="this.parentElement.remove()" class="text-red-500 font-bold hover:text-red-700 px-2">&times;</button>
    `;
    container.appendChild(row);
}
</script>
@endsection
