@extends('layouts.app')

@section('content')
<div class="bg-[#f4f7fb] min-h-screen py-8">
    <div class="container mx-auto max-w-7xl flex gap-8 px-4 sm:px-6 lg:px-8">
        
        <!-- Sidebar Navigation -->
        @include('adminPanel.partials.sidebar')

        <!-- Main Content -->
        <main class="flex-1 min-w-0 space-y-6 pb-12">
            @yield('admin_content')
        </main>
    </div>
</div>
@endsection
