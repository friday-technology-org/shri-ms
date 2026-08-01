<!DOCTYPE html>
<html class="scroll-smooth overflow-x-hidden" lang="en">
<head>
  <meta charset="utf-8">
  <title>Shri-ms Setup Wizard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0">
  <link rel="icon" href="{{ asset('assets/images/icons/icon-favicon.svg') }}" type="image/x-icon" sizes="16x16">
  <link rel="stylesheet" href="{{ asset('assets/styles/tailwind.min.css?v=5.0') }}">
  <link rel="stylesheet" href="{{ asset('assets/styles/style.min.css?v=5.0') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Chivo:wght@400;700;900&family=Noto+Sans:wght@400;500;600;700;800&display=swap">
  <style>
    body { font-family: 'Noto Sans', sans-serif; }
    .bg-color-brands { background-color: #4318FF; }
    .text-color-brands { color: #4318FF; }
    .border-color-brands { border-color: #4318FF; }
  </style>
</head>
<body class="w-screen relative min-h-screen bg-gray-100 dark:bg-[#000] text-gray-900 dark:text-gray-100 flex items-center justify-center p-4 sm:p-6 overflow-y-auto">

  <div class="w-full max-w-2xl bg-white dark:bg-[#111C44] rounded-2xl shadow-2xl border border-[#E8EDF2] dark:border-[#1B254B] overflow-hidden my-6">

    <!-- Professional Brand Header -->
    <div class="p-6 sm:p-8 bg-white dark:bg-[#111C44] border-b border-[#E8EDF2] dark:border-[#1B254B] flex items-center justify-between">
      <div class="flex items-center gap-3">
        <img src="{{ asset('assets/images/icons/icon-logo.svg') }}" alt="Shri-ms Logo" class="h-8">
      </div>
      <div class="text-right">
        <span class="text-xs font-bold uppercase tracking-wider text-color-brands bg-indigo-50 dark:bg-indigo-950/60 px-5 py-2 rounded-full border border-indigo-100 dark:border-indigo-900">
          Setup Wizard
        </span>
      </div>
    </div>

    <!-- Frox Styled Stepper -->
    <div class="bg-[#F8FAFC] dark:bg-[#0D1536] px-6 py-4 border-b border-[#E8EDF2] dark:border-[#1B254B]">
      @php
        $currentRoute = Route::currentRouteName();
      @endphp
      <div class="grid grid-cols-4 gap-2 text-center text-xs font-semibold">
        <div class="py-2 rounded-lg {{ $currentRoute === 'install.step1' ? 'bg-color-brands text-white shadow-md' : 'text-gray-500 dark:text-gray-400 bg-white dark:bg-[#111C44] border border-[#E8EDF2] dark:border-[#1B254B]' }}">
          1. Requirements
        </div>
        <div class="py-2 rounded-lg {{ $currentRoute === 'install.step2' ? 'bg-color-brands text-white shadow-md' : 'text-gray-500 dark:text-gray-400 bg-white dark:bg-[#111C44] border border-[#E8EDF2] dark:border-[#1B254B]' }}">
          2. Database
        </div>
        <div class="py-2 rounded-lg {{ $currentRoute === 'install.step3' ? 'bg-color-brands text-white shadow-md' : 'text-gray-500 dark:text-gray-400 bg-white dark:bg-[#111C44] border border-[#E8EDF2] dark:border-[#1B254B]' }}">
          3. Site Setup
        </div>
        <div class="py-2 rounded-lg {{ $currentRoute === 'install.finish' ? 'bg-color-brands text-white shadow-md' : 'text-gray-500 dark:text-gray-400 bg-white dark:bg-[#111C44] border border-[#E8EDF2] dark:border-[#1B254B]' }}">
          4. Finish
        </div>
      </div>
    </div>

    <!-- Main Content Container -->
    <div class="p-6 sm:p-8 bg-white dark:bg-[#111C44]">
      @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 text-sm">
          <ul class="list-disc pl-5 space-y-1">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @yield('content')
    </div>
  </div>

  @yield('scripts')
</body>
</html>
