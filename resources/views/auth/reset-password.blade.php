<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-favicon />

    <title>Atur Ulang Kata Sandi - SIBATU - Sistem Bantuan Tepat Sasaran</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-[#FAF7F2] text-[#1C2420] min-h-screen flex flex-col">
    <header class="bg-white border-b-2 border-[#D6CFC2] px-6 py-4">
        <div class="max-w-5xl mx-auto flex items-center justify-between">
            <a href="{{ route('login') }}" class="flex items-center gap-3">
                @if(file_exists(public_path('images/logo.png')))
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Sekolah" width="44" height="44" class="h-11 w-11 object-contain">
                @endif
                <div>
                    <span class="block font-editorial font-bold text-xl text-[#1B4D3E] leading-none">SIBATU</span>
                    <span class="block text-xs font-semibold text-[#4B5851] mt-0.5">Sistem Bantuan Tepat Sasaran</span>
                </div>
            </a>
        </div>
    </header>

    <main class="flex-1 flex items-center justify-center p-6">
        <div class="w-full max-w-md book-card p-7 sm:p-9">
            <h1 class="font-editorial text-2xl font-bold text-[#1C2420]">Buat Kata Sandi Baru</h1>
            <p class="mt-2 text-sm text-[#4B5851]">Gunakan minimal 8 karakter yang mudah Anda ingat.</p>

            <form method="POST" action="{{ route('password.store') }}" class="mt-6 space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <label for="email" class="book-label">Email Terdaftar</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}" required readonly class="book-input bg-[#F3EFE6] text-[#4B5851]">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <label for="password" class="book-label">Kata Sandi Baru</label>
                    <input type="password" id="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" class="book-input">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <label for="password_confirmation" class="book-label">Ulangi Kata Sandi Baru</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" placeholder="Ketik ulang kata sandi baru" class="book-input">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <button type="submit" class="btn-primary w-full">
                    Simpan Kata Sandi Baru
                </button>
            </form>
        </div>
    </main>
</body>
</html>
