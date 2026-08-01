@extends('cms-core::layouts.admin')

@section('title', 'Users - LaraCMS')

@section('content')
<div>
    <div class="flex flex-col gap-y-2 mb-[36px]">
        <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100">Users</h2>
        <div class="flex items-center text-xs gap-x-[11px]">
            <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}" alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div>
            <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
            <span class="capitalize text-color-brands">Users</span>
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

    <div class="flex justify-end mb-4">
        <a href="{{ route('cms.users.create') }}" class="btn normal-case h-fit min-h-fit border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] border-neutral-bg dark:border-dark-neutral-bg text-white text-sm py-[10px] px-[20px]">Add New User</a>
    </div>

    <div class="w-full border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-neutral dark:bg-dark-neutral-border">
                        <th class="text-left text-xs font-semibold text-gray-500 dark:text-gray-dark-500 px-5 py-3">ID</th>
                        <th class="text-left text-xs font-semibold text-gray-500 dark:text-gray-dark-500 px-5 py-3">Avatar</th>
                        <th class="text-left text-xs font-semibold text-gray-500 dark:text-gray-dark-500 px-5 py-3">Name</th>
                        <th class="text-left text-xs font-semibold text-gray-500 dark:text-gray-dark-500 px-5 py-3">Email</th>
                        <th class="text-left text-xs font-semibold text-gray-500 dark:text-gray-dark-500 px-5 py-3">Roles</th>
                        <th class="text-right text-xs font-semibold text-gray-500 dark:text-gray-dark-500 px-5 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E8EDF2] dark:divide-[#313442]">
                    @forelse($users as $user)
                    <tr>
                        <td class="px-5 py-4 text-sm font-medium text-gray-800 dark:text-gray-dark-1100">{{ $user->id }}</td>
                        <td class="px-5 py-4">
                            @if($user->avatar)
                                <img src="{{ asset($user->avatar) }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 text-xs">NA</div>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-800 dark:text-gray-dark-1100">{{ $user->name }}</td>
                        <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-dark-500">{{ $user->email }}</td>
                        <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-dark-500">
                            @if($user->roles->count() > 0)
                                {{ $user->roles->pluck('name')->join(', ') }}
                            @else
                                <span class="text-xs text-gray-400 italic">No roles</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('cms.users.edit', $user) }}" class="text-color-brands text-xs font-medium hover:opacity-75">Edit</a>
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('cms.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 text-xs font-medium hover:opacity-75">Delete</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400 dark:text-gray-dark-500">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="px-5 py-4 border-t border-neutral dark:border-dark-neutral-border">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
