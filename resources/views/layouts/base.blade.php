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
                
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-700">
                        {{ Auth::user()->name }} ({{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }})
                    </span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm">
                            Logout
                        </button>
                    </form>
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