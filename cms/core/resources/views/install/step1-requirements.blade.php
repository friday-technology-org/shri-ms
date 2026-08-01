@extends('cms-core::install.layout')

@section('content')
<div class="mb-6">
  <h2 class="text-xl font-bold text-gray-900 dark:text-white">Step 1: System Requirements & Permissions</h2>
  <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Checking server environment compatibility before database installation.</p>
</div>

<div class="space-y-6">
  @if(!$allPassed)
    <div class="p-4 rounded-xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 text-xs sm:text-sm leading-relaxed flex items-start gap-3">
      <span class="text-lg">⚠️</span>
      <div>
        <strong class="font-bold block text-sm">Some optional server checks flagged warnings:</strong>
        One or more PHP extensions or write checks are not fully loaded in CLI. You may still proceed with installation.
      </div>
    </div>
  @endif

  <!-- Requirements Table -->
  <div class="border border-[#E8EDF2] dark:border-[#1B254B] rounded-xl overflow-hidden shadow-sm">
    <div class="bg-[#F8FAFC] dark:bg-[#0D1536] px-4 py-3 border-b border-[#E8EDF2] dark:border-[#1B254B] font-bold text-xs uppercase tracking-wider text-gray-600 dark:text-gray-300">
      PHP Environment & Extensions
    </div>
    <div class="divide-y divide-[#E8EDF2] dark:divide-[#1B254B] text-sm">
      @foreach($requirements as $key => $item)
        <div class="flex items-center justify-between px-4 py-3">
          <span class="text-gray-700 dark:text-gray-300 font-medium">
            {{ $item['name'] }}
            @if(isset($item['current']))
              <span class="text-xs text-gray-400">({{ $item['current'] }})</span>
            @endif
          </span>
          @if($item['pass'])
            <span class="inline-flex items-center gap-1 text-green-600 dark:text-green-400 text-xs font-bold px-3 py-1 rounded-full bg-green-50 dark:bg-green-950/50 border border-green-200 dark:border-green-900">
              ✓ Passed
            </span>
          @else
            <span class="inline-flex items-center gap-1 text-red-600 dark:text-red-400 text-xs font-bold px-3 py-1 rounded-full bg-red-50 dark:bg-red-950/50 border border-red-200 dark:border-red-900">
              ! Warning
            </span>
          @endif
        </div>
      @endforeach
    </div>
  </div>

  <!-- Permissions Table -->
  <div class="border border-[#E8EDF2] dark:border-[#1B254B] rounded-xl overflow-hidden shadow-sm">
    <div class="bg-[#F8FAFC] dark:bg-[#0D1536] px-4 py-3 border-b border-[#E8EDF2] dark:border-[#1B254B] font-bold text-xs uppercase tracking-wider text-gray-600 dark:text-gray-300">
      Directory Permissions
    </div>
    <div class="divide-y divide-[#E8EDF2] dark:divide-[#1B254B] text-sm">
      @foreach($permissions as $key => $item)
        <div class="flex items-center justify-between px-4 py-3">
          <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $item['name'] }}</span>
          @if($item['pass'])
            <span class="inline-flex items-center gap-1 text-green-600 dark:text-green-400 text-xs font-bold px-3 py-1 rounded-full bg-green-50 dark:bg-green-950/50 border border-green-200 dark:border-green-900">
              ✓ Writable
            </span>
          @else
            <span class="inline-flex items-center gap-1 text-red-600 dark:text-red-400 text-xs font-bold px-3 py-1 rounded-full bg-red-50 dark:bg-red-950/50 border border-red-200 dark:border-red-900">
              ✗ Not Writable
            </span>
          @endif
        </div>
      @endforeach
    </div>
  </div>

  <!-- Action Button ALWAYS VISIBLE AND CLICKABLE -->
  <div class="flex items-center justify-between pt-4 border-t border-[#E8EDF2] dark:border-[#1B254B]">
    <span class="text-xs text-gray-400">Step 1 of 4</span>

    <a href="{{ route('install.step2') }}" class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] dark:hover:border-[#B2A7FF] border-neutral-bg dark:border-dark-neutral-bg py-[11px] px-[23px] text-white">
      Continue to Database Setup →
    </a>
  </div>
</div>
@endsection
