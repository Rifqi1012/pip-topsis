<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Lupa Password - PIP TOPSIS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            font-size: 14px;
        }
        .login-title {
            font-size: 24px;
            letter-spacing: 0.05em;
        }
        .bg-pattern {
            background-color: #f8fafc;
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</head>
<body class="antialiased text-slate-800 min-h-screen flex flex-col bg-pattern">

    <!-- Header Section -->
    <header class="w-full flex items-center px-8 py-5 bg-white border-b-4 border-yellow-400 shadow-sm relative z-10">
        <div class="flex-shrink-0 w-32">
            @if(file_exists(public_path('images/logo.png')))
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-30 w-auto drop-shadow-md">
            @else
                <div class="w-20 h-20 bg-blue-100 border-2 border-blue-600 rounded-lg flex items-center justify-center font-bold text-blue-800">LOGO</div>
            @endif
        </div>
        <div class="flex-1 text-center pr-32">
            <h1 class="font-extrabold text-lg md:text-xl lg:text-2xl leading-snug text-slate-800 tracking-tight">
                SISTEM PENDUKUNG KEPUTUSAN<br>
                <span class="text-blue-600">PENERIMA PROGRAM INDONESIA PINTAR (PIP)</span><br>
                METODE TOPSIS
            </h1>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center p-6">
        <div class="w-full max-w-md bg-white border border-slate-200 rounded-xl shadow-xl p-8 pt-6 relative overflow-hidden">
            <!-- Decorative top accent -->
            <div class="absolute top-0 left-0 w-full h-1.5 bg-blue-600"></div>

            <div class="flex items-center mb-6 mt-2">
                <div class="flex-1 border-b border-slate-200"></div>
                <h2 class="px-4 font-extrabold login-title text-slate-800 text-center">LUPA<br>PASSWORD?</h2>
                <div class="flex-1 border-b border-slate-200"></div>
            </div>

            <div class="mb-6 text-sm text-slate-600 text-center leading-relaxed">
                Masukkan email yang terdaftar pada akun Anda. Kami akan mengirimkan tautan untuk mengatur ulang password.
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-8 group">
                    <label for="email" class="block font-semibold mb-2 text-slate-700 group-focus-within:text-blue-600 transition-colors">Email Terdaftar</label>
                    <div class="relative shadow-sm">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400 group-focus-within:text-blue-500 transition-colors" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 16">
                                <path d="m10.036 8.278 9.258-7.79A1.979 1.979 0 0 0 18 0H2A1.987 1.987 0 0 0 .641.541l9.395 7.737Z"/>
                                <path d="M11.241 9.817c-.36.275-.801.425-1.255.427-.428 0-.845-.138-1.187-.395L0 2.6V14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2.5l-8.759 7.317Z"/>
                            </svg>
                        </div>
                        <input type="email" id="email" name="email" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-3 transition-all" placeholder="Masukkan email Anda" value="{{ old('email') }}" required autofocus autocomplete="username">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Button -->
                <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-bold rounded-lg text-sm px-5 py-3.5 text-center shadow-md hover:shadow-lg transition-all transform active:scale-[0.98]">
                    KIRIM LINK RESET PASSWORD
                </button>

                <!-- Back to Login -->
                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 hover:underline transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Login
                    </a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
