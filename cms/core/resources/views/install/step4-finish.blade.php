@extends('cms-core::install.layout')

@section('content')
<div class="text-center py-10 max-w-xl mx-auto">
  <div class="space-y-4">
    <div class="flex justify-center">
      <img src="{{ asset('assets/images/icons/icon-landing-success-1.svg') }}" alt="Installation Successful" class="w-28 h-28">
    </div>
    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Installation Successfully Completed!</h2>
    <p class="text-gray-500 dark:text-gray-400 text-sm max-w-md mx-auto leading-relaxed pb-6">
      Shri-ms has been successfully installed. Database migrations have been executed, default options seeded, and your Super Administrator account has been configured.
    </p>
  </div>

  <div class="bg-[#F8FAFC] dark:bg-[#0D1536] p-5 rounded-xl max-w-md mx-auto text-left text-xs text-gray-600 dark:text-gray-400 border border-[#E8EDF2] dark:border-[#1B254B] space-y-2.5 mt-10">
    <div class="flex justify-between items-center">
      <span class="font-bold uppercase tracking-wider text-gray-500">Site Title:</span>
      <span class="text-gray-900 dark:text-white font-bold text-sm">{{ cms_option('site_title', 'Shri-ms') }}</span>
    </div>
    <div class="flex justify-between items-center">
      <span class="font-bold uppercase tracking-wider text-gray-500">Version:</span>
      <span class="text-gray-900 dark:text-white font-bold">1.0.0</span>
    </div>
    <div class="flex justify-between items-center">
      <span class="font-bold uppercase tracking-wider text-gray-500">Security Lock:</span>
      <span class="text-green-600 dark:text-green-400 font-bold px-2 py-0.5 rounded bg-green-50 dark:bg-green-950/50 border border-green-200 dark:border-green-900">
        Active (`storage/app/.installed`)
      </span>
    </div>
  </div>

  <div class="pt-12 pb-10 flex justify-center gap-4">
    <a href="{{ route('cms.login') }}" class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] dark:hover:border-[#B2A7FF] border-neutral-bg dark:border-dark-neutral-bg py-[11px] px-[23px] text-white font-bold rounded-lg">
      Log In to Admin Dashboard →
    </a>
    <a href="{{ url('/') }}" class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-gray-200 hover:bg-gray-300 border-neutral-bg dark:border-dark-neutral-bg py-[11px] px-[23px] text-gray-700 font-bold rounded-lg">
      Go to Homepage
    </a>
  </div>
</div>
@endsection
