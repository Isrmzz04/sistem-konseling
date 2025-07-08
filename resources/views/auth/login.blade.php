@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-[#f8f9fc] px-4">
        <div class="bg-white p-10 rounded-3xl shadow-md max-w-sm w-full space-y-8 text-center">
            <div class="flex justify-center">
                <img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo Sekolah"
                    class="w-24 h-24 object-cover rounded-md" />
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Login</h2>
                <p class="mt-1 text-sm text-gray-400">Aplikasi Layanan Konseling Siswa</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                        <i class='bx bx-user text-lg'></i>
                    </span>
                    <input id="username" name="username" type="text" required value="{{ old('username') }}"
                        class="w-full pl-10 pr-4 py-2 rounded-xl bg-blue-100 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-400 @error('username') border-red-500 @enderror"
                        placeholder="Username" />
                </div>

                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                        <i class='bx bx-lock-alt text-lg'></i>
                    </span>
                    <input id="password" name="password" type="password" required
                        class="w-full pl-10 pr-4 py-2 rounded-xl bg-blue-100 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-400 @error('password') border-red-500 @enderror"
                        placeholder="********" />
                </div>

                <div>
                    @error('username')
                        <p class="text-left text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    @error('password')
                        <p class="text-left text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-black text-white font-semibold py-2 rounded-full shadow-lg hover:bg-gray-800 transition cursor-pointer">
                    Login
                </button>
            </form>
        </div>
    </div>
@endsection
