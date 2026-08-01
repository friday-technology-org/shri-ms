@extends('cms-core::layouts.auth')

@section('title', 'Shri-ms Admin Login')

@section('content')
<form class="rounded-2xl bg-white mx-auto p-10 text-center max-w-[440px] my-[84px] shadow-sm dark:bg-[#1F2128]" action="{{ route('cms.login') }}" method="POST">
  @csrf
  
  <div class="mb-4 text-center mx-auto">
    <img class="inline-block" src="{{ asset('assets/images/icons/icon-landing-success-1.svg') }}" alt="landing success">
  </div>
  
  <h3 class="font-bold text-2xl text-gray-1100 capitalize mb-[5px] dark:text-gray-dark-1100">Welcome Back!</h3>
  <p class="text-sm text-gray-500 mb-[30px] dark:text-gray-dark-500">Sign in to the CMS Administrator Dashboard</p>
  
  @if($errors->any())
    <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-600 text-sm font-semibold text-left">
      {{ $errors->first() }}
    </div>
  @endif

  <div>
    <label for="email">
      <p class="text-left text-sm mb-2 text-gray-1100 dark:text-gray-dark-1100">E-mail address</p>
    </label>
    <div class="form-control mb-[20px]">
      <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
        <input class="input flex-1 bg-transparent text-gray-800 focus:outline-none dark:text-gray-dark-300 w-full px-4 py-3" type="email" placeholder="admin@example.com" name="email" value="{{ old('email') }}" required autofocus>
      </div>
    </div>
    
    <label for="password">
      <p class="text-left text-sm mb-2 text-gray-1100 dark:text-gray-dark-1100">Password</p>
    </label>
    <div class="form-control mb-[20px]">
      <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
        <input class="input flex-1 bg-transparent text-gray-800 focus:outline-none dark:text-gray-dark-300 w-full px-4 py-3" type="password" placeholder="Password" name="password" required>
      </div>
    </div>
  </div>
  
  <button type="submit" class="btn normal-case h-fit min-h-fit transition-all duration-300 border-4 w-full bg-color-brands hover:bg-color-brands hover:opacity-90 border-transparent mb-[20px] py-[14px] text-white font-bold rounded-xl shadow-lg cursor-pointer text-sm">Login to Shri-ms</button>
  
  <a class="text-center text-xs block text-[#8083A3] mb-[20px]" href="#">Forgot password?</a>
  
</form>
@endsection
