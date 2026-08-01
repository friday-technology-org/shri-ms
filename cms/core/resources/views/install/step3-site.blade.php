@extends('cms-core::install.layout')

@section('content')
<div class="mb-6">
  <h2 class="text-xl font-bold text-gray-900 dark:text-white">Step 3: Site Identity & Administrator Account</h2>
  <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Configure your website title and create your Super Admin credentials.</p>
</div>

<form action="{{ route('install.process') }}" method="POST" class="space-y-6">
  @csrf

  <!-- Site Information Group -->
  <div class="border border-[#E8EDF2] dark:border-[#1B254B] rounded-xl overflow-hidden shadow-sm">
    <div class="bg-[#F8FAFC] dark:bg-[#0D1536] px-4 py-3 border-b border-[#E8EDF2] dark:border-[#1B254B] font-bold text-xs uppercase tracking-wider text-gray-600 dark:text-gray-300">
      Site Information
    </div>
    <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">
      <!-- Site Title -->
      <div class="mb-[20px] md:col-span-2">
        <label for="site_title">
          <p class="text-left text-sm mb-2 text-gray-1100 dark:text-gray-dark-1100">Site Title</p>
        </label>
        <div class="form-control">
          <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
            <input type="text" name="site_title" id="site_title" value="{{ old('site_title', 'My LaraCMS Website') }}" class="input flex-1 bg-transparent text-gray-600 focus:outline-none dark:text-gray-dark-300 w-full py-3 px-4" required>
          </div>
        </div>
      </div>

      <!-- Tagline -->
      <div class="mb-[20px] md:col-span-2">
        <label for="site_tagline">
          <p class="text-left text-sm mb-2 text-gray-1100 dark:text-gray-dark-1100">Tagline</p>
        </label>
        <div class="form-control">
          <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
            <input type="text" name="site_tagline" id="site_tagline" value="{{ old('site_tagline', 'Just another LaraCMS site') }}" class="input flex-1 bg-transparent text-gray-600 focus:outline-none dark:text-gray-dark-300 w-full py-3 px-4">
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Admin Credentials Group -->
  <div class="border border-[#E8EDF2] dark:border-[#1B254B] rounded-xl overflow-hidden shadow-sm">
    <div class="bg-[#F8FAFC] dark:bg-[#0D1536] px-4 py-3 border-b border-[#E8EDF2] dark:border-[#1B254B] font-bold text-xs uppercase tracking-wider text-gray-600 dark:text-gray-300">
      Super Administrator Credentials
    </div>
    <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">
      <!-- Admin Full Name -->
      <div class="mb-[20px]">
        <label for="admin_name">
          <p class="text-left text-sm mb-2 text-gray-1100 dark:text-gray-dark-1100">Admin Full Name</p>
        </label>
        <div class="form-control">
          <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
            <input type="text" name="admin_name" id="admin_name" value="{{ old('admin_name', 'Administrator') }}" class="input flex-1 bg-transparent text-gray-600 focus:outline-none dark:text-gray-dark-300 w-full py-3 px-4" required>
          </div>
        </div>
      </div>

      <!-- Admin Email Address -->
      <div class="mb-[20px]">
        <label for="admin_email">
          <p class="text-left text-sm mb-2 text-gray-1100 dark:text-gray-dark-1100">Admin Email Address</p>
        </label>
        <div class="form-control">
          <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
            <input type="email" name="admin_email" id="admin_email" value="{{ old('admin_email', 'admin@example.com') }}" class="input flex-1 bg-transparent text-gray-600 focus:outline-none dark:text-gray-dark-300 w-full py-3 px-4" required>
          </div>
        </div>
      </div>

      <!-- Password -->
      <div class="mb-[20px]">
        <label for="admin_password">
          <p class="text-left text-sm mb-2 text-gray-1100 dark:text-gray-dark-1100">Password</p>
        </label>
        <div class="form-control">
          <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
            <input type="password" name="admin_password" id="admin_password" class="input flex-1 bg-transparent text-gray-600 focus:outline-none dark:text-gray-dark-300 w-full py-3 px-4" required>
          </div>
        </div>
      </div>

      <!-- Confirm Password -->
      <div class="mb-[20px]">
        <label for="admin_password_confirmation">
          <p class="text-left text-sm mb-2 text-gray-1100 dark:text-gray-dark-1100">Confirm Password</p>
        </label>
        <div class="form-control">
          <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
            <input type="password" name="admin_password_confirmation" id="admin_password_confirmation" class="input flex-1 bg-transparent text-gray-600 focus:outline-none dark:text-gray-dark-300 w-full py-3 px-4" required>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="flex items-center justify-between pt-4 border-t border-[#E8EDF2] dark:border-[#1B254B]">
    <a href="{{ route('install.step2') }}" class="px-4 py-2 text-xs font-bold text-gray-500 hover:text-gray-700 dark:text-gray-400">
      ← Back to Database
    </a>

    <button type="submit" class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] dark:hover:border-[#B2A7FF] border-neutral-bg dark:border-dark-neutral-bg py-[11px] px-[23px] text-white">
      Install LaraCMS →
    </button>
  </div>
</form>
@endsection
