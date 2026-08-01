@extends('cms-core::layouts.admin')

@section('title', 'Revisions for ' . $content->title . ' - Shri-ms')

@section('content')
<div>
    <div class="flex flex-col gap-y-2 mb-[36px]">
        <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100">Revisions</h2>
        <div class="flex items-center text-xs gap-x-[11px]">
            <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}" alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div>
            <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
            <span class="capitalize text-color-brands">{{ $cpt->plural_label }}</span>
            <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
            <a href="{{ route('cms.content.edit', [$cpt->name, $content->id]) }}" class="capitalize text-color-brands hover:underline">Edit {{ strtolower($cpt->singular_label) }}</a>
            <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
            <span class="capitalize text-color-brands">Revisions</span>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl px-[25px] py-[25px] mb-9">
        <div class="flex justify-between items-center mb-5">
            <p class="text-gray-1100 leading-4 font-semibold dark:text-gray-dark-1100 text-[16px]">Revision History for: {{ $content->title }}</p>
            <a href="{{ route('cms.content.edit', [$cpt->name, $content->id]) }}" class="btn normal-case h-fit min-h-fit border-4 bg-gray-500 hover:bg-gray-600 border-neutral-bg dark:border-dark-neutral-bg text-white text-sm py-[8px] px-[15px] rounded-lg">Back to Editor</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3 font-semibold">Date / Time</th>
                        <th scope="col" class="px-6 py-3 font-semibold">Author</th>
                        <th scope="col" class="px-6 py-3 font-semibold">Title</th>
                        <th scope="col" class="px-6 py-3 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($revisions as $revision)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700" x-data="{ show: false }">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $revision->created_at->format('M d, Y h:i A') }}
                            <br>
                            <span class="text-xs text-gray-400">{{ $revision->created_at->diffForHumans() }}</span>
                        </td>
                        <td class="px-6 py-4">
                            {{ $revision->author ? $revision->author->name : 'System' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ \Illuminate\Support\Str::limit($revision->title, 50) }}
                        </td>
                        <td class="px-6 py-4 text-right flex justify-end gap-2">
                            <button type="button" @click="show = true" class="btn normal-case h-fit min-h-fit bg-blue-500 hover:bg-blue-600 text-white text-xs py-[6px] px-[12px] rounded">Preview</button>
                            
                            <form action="{{ route('cms.content.revisions.restore', [$cpt->name, $content->id, $revision->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to restore this revision? The current state will be saved as a new revision.');">
                                @csrf
                                <button type="submit" class="btn normal-case h-fit min-h-fit bg-red-500 hover:bg-red-600 text-white text-xs py-[6px] px-[12px] rounded">Restore</button>
                            </form>

                            <!-- Preview Modal -->
                            <div x-show="show" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    <div x-show="show" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="show = false"></div>
                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                    <div x-show="show" x-transition class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 text-left">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4" id="modal-title">Revision Preview: {{ $revision->created_at->format('M d, Y h:i A') }}</h3>
                                            <div class="mb-4">
                                                <h4 class="font-semibold text-gray-700 dark:text-gray-300">Title:</h4>
                                                <p class="text-gray-600 dark:text-gray-400 border p-2 rounded">{{ $revision->title }}</p>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-700 dark:text-gray-300">Content:</h4>
                                                <div class="prose dark:prose-invert max-w-none border p-4 rounded bg-gray-50 dark:bg-gray-900 overflow-y-auto max-h-[50vh]">
                                                    {!! $revision->content !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <button type="button" @click="show = false" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No revisions found for this content.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $revisions->links() }}
        </div>
    </div>
</div>
@endsection
