@extends('cms-core::layouts.admin')

@section('title', 'Comments - LaraCMS')

@section('content')
<div>
    <div class="flex justify-between flex-col gap-y-3 mb-[36px] md:flex-row">
        <div>
            <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100 mb-[13px]">Comments</h2>
            <div class="flex items-center text-xs gap-x-[11px]">
                <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}" alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div>
                <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
                <span class="capitalize text-color-brands">Comments</span>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    {{-- Status Tabs --}}
    <div class="flex gap-4 mb-6 border-b border-[#E8EDF2] dark:border-[#313442] pb-3">
        <a href="{{ route('cms.comments.index', ['status' => 'all']) }}" class="text-sm font-semibold {{ $status === 'all' ? 'text-color-brands border-b-2 border-color-brands' : 'text-gray-500 hover:text-gray-700' }} pb-2 px-1">All</a>
        <a href="{{ route('cms.comments.index', ['status' => 'pending']) }}" class="text-sm font-semibold {{ $status === 'pending' ? 'text-color-brands border-b-2 border-color-brands' : 'text-gray-500 hover:text-gray-700' }} pb-2 px-1">Pending</a>
        <a href="{{ route('cms.comments.index', ['status' => 'approved']) }}" class="text-sm font-semibold {{ $status === 'approved' ? 'text-color-brands border-b-2 border-color-brands' : 'text-gray-500 hover:text-gray-700' }} pb-2 px-1">Approved</a>
        <a href="{{ route('cms.comments.index', ['status' => 'spam']) }}" class="text-sm font-semibold {{ $status === 'spam' ? 'text-color-brands border-b-2 border-color-brands' : 'text-gray-500 hover:text-gray-700' }} pb-2 px-1">Spam</a>
    </div>

    <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl mb-9 overflow-x-scroll scrollbar-hide pl-[29px] pr-[22px] pb-[26px] pt-[17px] xl:overflow-x-hidden">
        <table class="w-full border-separate border-spacing-y-[15px] min-w-[900px]">
            <thead> 
                <tr> 
                    <th class="leading-4 text-gray-500 text-left font-normal text-[14px] dark:text-gray-dark-500 pl-5 w-1/4">Author</th>
                    <th class="leading-4 text-gray-500 text-left font-normal text-[14px] dark:text-gray-dark-500 w-2/5">Comment</th>
                    <th class="leading-4 text-gray-500 text-left font-normal text-[14px] dark:text-gray-dark-500">In Response To</th>
                    <th class="leading-4 text-gray-500 text-left font-normal text-[14px] dark:text-gray-dark-500">Submitted On</th>
                    <th class="leading-4 text-gray-500 text-right font-normal text-[14px] dark:text-gray-dark-500 pr-5">Actions</th>
                </tr>
            </thead>
            <tbody> 
                @forelse($comments as $comment)
                <tr>
                    <td class="py-4 pl-5 border-y border-neutral border-l dark:border-dark-neutral-bg rounded-l-[7px] text-sm">
                        <div class="font-semibold text-gray-1100 dark:text-white">{{ $comment->author_name }}</div>
                        <div class="text-xs text-gray-400">{{ $comment->author_email }}</div>
                        <div class="text-[10px] text-gray-400 mt-1">IP: {{ $comment->ip_address }}</div>
                    </td>
                    <td class="py-4 border-y border-neutral dark:border-dark-neutral-bg text-sm">
                        <p class="text-gray-800 dark:text-gray-dark-300 line-clamp-3">{{ $comment->content }}</p>
                        @if($comment->status === 'pending')
                            <span class="inline-block mt-2 px-2 py-0.5 bg-yellow-100 text-yellow-700 text-[10px] rounded-md font-semibold">Pending Moderation</span>
                        @elseif($comment->status === 'spam')
                            <span class="inline-block mt-2 px-2 py-0.5 bg-red-100 text-red-700 text-[10px] rounded-md font-semibold">Spam</span>
                        @endif
                    </td>
                    <td class="py-4 border-y border-neutral dark:border-dark-neutral-bg text-sm">
                        @if($comment->post)
                            <a href="{{ $comment->post->permalink }}" target="_blank" class="text-color-brands hover:underline font-medium">{{ $comment->post->title }}</a>
                        @else
                            <span class="text-gray-400">Post Deleted</span>
                        @endif
                    </td>
                    <td class="py-4 border-y border-neutral dark:border-dark-neutral-bg text-xs text-gray-400">
                        {{ $comment->created_at->format('d M Y \a\t H:i') }}
                    </td>
                    <td class="py-4 border-y border-neutral border-r dark:border-dark-neutral-bg rounded-r-[7px] text-right pr-5">
                        <div class="flex items-center justify-end gap-2">
                            @if($comment->status !== 'approved')
                                <form action="{{ route('cms.comments.approve', $comment->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-green-600 text-xs font-semibold hover:opacity-75">Approve</button>
                                </form>
                            @endif

                            @if($comment->status !== 'spam')
                                <form action="{{ route('cms.comments.spam', $comment->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-yellow-600 text-xs font-semibold hover:opacity-75">Spam</button>
                                </form>
                            @endif

                            <form action="{{ route('cms.comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Delete this comment?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 text-xs font-semibold hover:opacity-75">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-500 dark:text-gray-dark-500">No comments found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="mt-5">
            {{ $comments->links() }}
        </div>
    </div>
</div>
@endsection
