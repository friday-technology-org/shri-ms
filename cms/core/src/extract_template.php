<?php

$html = file_get_contents('/home/bipincodes/projects/laravel/friday-technology/lara-cms/html/cms-dashboard.html');

// Extract Sidebar
preg_match('/<aside.*?>.*?<\/aside>/s', $html, $sidebarMatches);
$sidebar = $sidebarMatches[0] ?? '';

// Extract Header
preg_match('/<header.*?>.*?<\/header>/s', $html, $headerMatches);
$header = $headerMatches[0] ?? '';

// Create Admin Layout
$layout = <<<HTML
<!DOCTYPE html>
<html class="scroll-smooth overflow-x-hidden" lang="en">
  <head>
    <meta charset="utf-8">
    <title>@yield('title', 'LaraCMS Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0">
    <link rel="icon" href="{{ asset('assets/images/icons/icon-favicon.svg') }}" type="image/x-icon" sizes="16x16">
    <link rel="stylesheet" href="{{ asset('assets/styles/tailwind.min.css') }}?v=5.0">
    <link rel="stylesheet" href="{{ asset('assets/styles/style.min.css') }}?v=5.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Chivo:wght@400;700;900&family=Noto+Sans:wght@400;500;600;700;800&display=swap">
  </head>
  <body class="w-screen relative overflow-x-hidden min-h-screen bg-gray-100 scrollbar-hide cms-dashboard-page dark:bg-[#000]">
    <div class="wrapper mx-auto text-gray-900 font-normal grid scrollbar-hide grid-cols-[257px,1fr] grid-rows-[auto,1fr]" id="layout">
      @include('cms-core::layouts.partials.sidebar')
      @include('cms-core::layouts.partials.header')
      <main class="overflow-x-scroll scrollbar-hide flex flex-col justify-between pt-[42px] px-[23px] pb-[28px]">
        @yield('content')
      </main>
    </div>
    
    <!-- Scripts -->
    <script src="{{ asset('assets/scripts/chart.min.js') }}"></script>
    <script src="{{ asset('assets/scripts/main.js') }}"></script>
    <script src="{{ asset('assets/scripts/cms-dashboard.js') }}"></script>
  </body>
</html>
HTML;

// Create Dashboard Index (Main content)
preg_match('/<main.*?>(.*?)<\/main>/s', $html, $mainMatches);
$mainContent = $mainMatches[1] ?? '';
$dashboard = <<<HTML
@extends('cms-core::layouts.admin')

@section('title', 'Dashboard - LaraCMS')

@section('content')
$mainContent
@endsection
HTML;

@mkdir(__DIR__ . '/../resources/views/layouts/partials', 0755, true);
@mkdir(__DIR__ . '/../resources/views/dashboard', 0755, true);

file_put_contents(__DIR__ . '/../resources/views/layouts/partials/sidebar.blade.php', $sidebar);
file_put_contents(__DIR__ . '/../resources/views/layouts/partials/header.blade.php', $header);
file_put_contents(__DIR__ . '/../resources/views/layouts/admin.blade.php', $layout);
file_put_contents(__DIR__ . '/../resources/views/dashboard/index.blade.php', $dashboard);

echo "Extracted successfully.\n";

