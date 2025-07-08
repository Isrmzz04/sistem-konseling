@php
    $user = Auth::user();
    $role = $user->role;
    $namaLengkap = match($role) {
        'administrator' => $user->username,
        'guru_bk' => optional($user->guruBk)->nama_lengkap,
        'siswa' => optional($user->siswa)->nama_lengkap,
        default => $user->username
    };
@endphp

@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="flex justify-between items-center px-6 py-4">
                <div>
                    <h1 class="text-xl font-semibold text-gray-900">
                        @yield('page-title', 'Dashboard')
                    </h1>
                </div>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 text-sm text-gray-700 hover:text-gray-900 focus:outline-none cursor-pointer">
                        <i class='bx bx-user-circle text-2xl'></i>
                        <span>{{ $namaLengkap }} ({{ ucfirst(str_replace('_', ' ', $role)) }})</span>
                        <i class='bx bx-chevron-down'></i>
                    </button>

                    <div x-show="open" @click.outside="open = false"
                         x-transition
                         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 z-50 border border-gray-200">
                        <div class="px-4 py-2 text-sm text-gray-600 border-b">
                            <strong>{{ $namaLengkap }}</strong><br>
                            <span class="text-xs">{{ ucfirst(str_replace('_', ' ', $role)) }}</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 cursor-pointer">
                                <i class='bx bx-log-out mr-2'></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-6 py-8">
                @if(session('success'))
                    @include('components.alerts.success', ['message' => session('success')])
                @endif

                @if(session('error'))
                    @include('components.alerts.error', ['message' => session('error')])
                @endif

                @if(session('warning'))
                    @include('components.alerts.warning', ['message' => session('warning')])
                @endif

                @yield('main-content')
            </div>
        </main>
    </div>
</div>
@endsection
