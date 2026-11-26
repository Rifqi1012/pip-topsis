<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-favicon />

    <title>Masuk - SIBATU - Sistem Bantuan Tepat Sasaran</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-[#FAF7F2] text-[#1C2420] min-h-screen flex flex-col">
    <main class="flex-1 grid grid-cols-1 lg:grid-cols-12 min-h-screen">
        <!-- Left Identity Panel -->
        <section class="lg:col-span-5 bg-[#1B4D3E] text-white p-8 sm:p-12 flex flex-col justify-between border-b-2 lg:border-b-0 lg:border-r-2 border-[#143B2F]">
            <div>
                <div class="flex items-center gap-4">
                    @if(file_exists(public_path('images/logo.png')))
                        <div class="w-16 h-16 rounded-2xl bg-white p-2 flex items-center justify-center border-2 border-[#D6CFC2] flex-shrink-0">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo Sekolah" width="52" height="52" fetchpriority="high" class="w-full h-full object-contain">
                        </div>
                    @else
                        <div class="w-16 h-16 rounded-2xl bg-[#FDE68A] text-[#1B4D3E] flex items-center justify-center font-editorial font-bold text-3xl flex-shrink-0">S</div>
                    @endif
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-[#FDE68A]">Portal Sekolah</p>
                        <h1 class="font-editorial text-3xl font-bold text-white leading-tight">SIBATU</h1>
                    </div>
                </div>

                <div class="mt-10 max-w-md">
                    <p class="font-editorial text-2xl sm:text-3xl font-semibold text-white leading-snug">
                        Sistem Bantuan Tepat Sasaran untuk Seleksi Siswa yang Adil dan Terukur.
                    </p>
                    <p class="mt-4 text-sm sm:text-base text-[#D8E7E1] leading-relaxed">
                        Dirancang agar Wali Kelas dan Kepala Sekolah dapat mencatat data siswa, mengisi penilaian, hingga menentukan penerima bantuan tanpa proses yang rumit.
                    </p>
                </div>

                <ol class="mt-8 space-y-3 max-w-md">
                    <li class="flex items-start gap-3 bg-[#143B2F] border border-[#2C6B57] rounded-xl p-3.5">
                        <span class="w-6 h-6 rounded-full bg-[#FDE68A] text-[#1C2420] font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">1</span>
                        <span class="text-sm text-[#EDF5F2]">Input atau unggah data siswa dengan pilihan kelas yang terstandar.</span>
                    </li>
                    <li class="flex items-start gap-3 bg-[#143B2F] border border-[#2C6B57] rounded-xl p-3.5">
                        <span class="w-6 h-6 rounded-full bg-[#FDE68A] text-[#1C2420] font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">2</span>
                        <span class="text-sm text-[#EDF5F2]">Nilai setiap siswa berdasarkan kriteria yang transparan dan mudah dipilih.</span>
                    </li>
                    <li class="flex items-start gap-3 bg-[#143B2F] border border-[#2C6B57] rounded-xl p-3.5">
                        <span class="w-6 h-6 rounded-full bg-[#FDE68A] text-[#1C2420] font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">3</span>
                        <span class="text-sm text-[#EDF5F2]">Hasil perankingan otomatis siap diunduh dalam format PDF dan Excel.</span>
                    </li>
                </ol>
            </div>

            <p class="mt-10 text-xs text-[#C0D6CE]">
                &copy; {{ date('Y') }} SIBATU - Sistem Bantuan Tepat Sasaran
            </p>
        </section>

        <!-- Right Login Form -->
        <section class="lg:col-span-7 flex items-center justify-center p-6 sm:p-10 lg:p-14">
            <div class="w-full max-w-md">
                <div class="book-card p-7 sm:p-9">
                    <div class="mb-7">
                        <h2 class="font-editorial text-2xl sm:text-3xl font-bold text-[#1C2420]">Masuk ke Akun</h2>
                        <p class="mt-1.5 text-sm text-[#4B5851]">
                            Masukkan email dan kata sandi yang diberikan oleh Kepala Sekolah.
                        </p>
                    </div>

                    <x-auth-session-status class="mb-5" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="book-label">Alamat Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="nama@sekolah.com"
                                class="book-input"
                            >
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="password" class="book-label !mb-0">Kata Sandi</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-xs font-bold text-[#1B4D3E] hover:underline">
                                        Lupa kata sandi?
                                    </a>
                                @endif
                            </div>
                            <div class="relative">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Masukkan kata sandi Anda"
                                    class="book-input pr-12"
                                >
                                <button
                                    type="button"
                                    id="toggle-password"
                                    aria-label="Tampilkan atau sembunyikan kata sandi"
                                    class="absolute inset-y-0 right-0 flex items-center justify-center w-11 text-[#4B5851] hover:text-[#1C2420]"
                                >
                                    <svg id="eye-open" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg id="eye-closed" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="btn-primary w-full text-base py-3">
                                Masuk Sekarang
                            </button>
                        </div>
                    </form>

                    <div class="mt-7 pt-5 border-t border-[#EAE4D8] text-xs text-[#4B5851] leading-relaxed">
                        Akses ini khusus untuk <strong>Wali Kelas</strong> dan <strong>Kepala Sekolah</strong>. Jika belum memiliki akun atau lupa email, silakan hubungi Kepala Sekolah.
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        document.getElementById('toggle-password')?.addEventListener('click', function () {
            const input = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');
            if (input) {
                const isPassword = input.getAttribute('type') === 'password';
                input.setAttribute('type', isPassword ? 'text' : 'password');
                eyeOpen?.classList.toggle('hidden');
                eyeClosed?.classList.toggle('hidden');
            }
        });
    </script>
</body>
</html>
