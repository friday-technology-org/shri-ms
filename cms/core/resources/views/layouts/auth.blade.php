<!DOCTYPE html>
<html class="scroll-smooth overflow-x-hidden" lang="en">
  <head>
    <meta charset="utf-8">
    <title>@yield('title', 'LaraCMS Admin Authentication')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0">
    <link rel="icon" href="{{ asset('assets/images/icons/icon-favicon.svg') }}" type="image/x-icon" sizes="16x16">
    <link rel="stylesheet" href="{{ asset('assets/styles/tailwind.min.css') }}?v=5.0">
    <link rel="stylesheet" href="{{ asset('assets/styles/style.min.css') }}?v=5.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Chivo:wght@400;700;900&family=Noto+Sans:wght@400;500;600;700;800&display=swap">
  </head>
  <body class="w-screen relative overflow-x-hidden min-h-screen bg-gray-100 scrollbar-hide authentication-sign-in-page dark:bg-[#000]">
    <div class="wrapper mx-auto text-gray-900 font-normal flex items-center justify-center min-h-screen">
      <main class="w-full max-w-md px-[23px] pb-[28px]">
        @yield('content')
      </main>
    </div>
  </body>
</html>
