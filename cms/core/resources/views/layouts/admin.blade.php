@php
    $userLocale = auth()->user()->locale ?? 'en';
    $isRtl = in_array($userLocale, ['ar', 'he', 'fa', 'ur']);
@endphp
<!DOCTYPE html>
<html class="scroll-smooth overflow-x-hidden" lang="{{ $userLocale }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Shri-ms Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0">
    <link rel="icon" href="{{ asset('assets/images/icons/icon-favicon.svg') }}" type="image/x-icon" sizes="16x16">
    <link rel="stylesheet" href="{{ asset('assets/styles/tailwind.min.css') }}?v=5.0">
    <link rel="stylesheet" href="{{ asset('assets/styles/style.min.css') }}?v=5.0">
    <link rel="stylesheet" href="{{ asset('assets/styles/cms-admin-extra.css') }}?v=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Chivo:wght@400;700;900&family=Noto+Sans:wght@400;500;600;700;800&display=swap">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.snow.css">
    <script src="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  </head>
  <body class="w-screen relative overflow-x-hidden min-h-screen bg-gray-100 scrollbar-hide cms-dashboard-page dark:bg-[#000]">
    <div class="wrapper mx-auto text-gray-900 font-normal grid scrollbar-hide grid-cols-[257px,1fr] grid-rows-[auto,1fr]" id="layout">
      @include('cms-core::layouts.partials.sidebar')
      @include('cms-core::layouts.partials.header')
      <main class="overflow-x-scroll scrollbar-hide flex flex-col justify-between pt-[42px] px-[23px] pb-[28px]">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative mb-4" role="alert">
                <span class="block sm:inline font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative mb-4" role="alert">
                <span class="block sm:inline font-semibold">{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
      </main>
    </div>

    {{-- Global Media Picker Modal --}}
    @include('cms-core::layouts.partials.media-picker-modal')
    
    <!-- Scripts -->
    <script src="{{ asset('assets/scripts/vendors/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/scripts/chart-utils.min.js') }}"></script>
    <script src="{{ asset('assets/scripts/chart.min.js') }}"></script>
    <script src="{{ asset('assets/scripts/app.js') }}?v=5.0"></script>
    @stack('scripts')
  </body>
</html>