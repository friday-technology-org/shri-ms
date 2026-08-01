@extends('cms-core::layouts.admin')

@section('title', $title . ' - Shri-ms')

@section('content')
    <div class="cms-theme-page-wrapper bg-white dark:bg-dark-neutral-bg rounded-2xl p-6 shadow-sm">
        {!! $content !!}
    </div>
@endsection
