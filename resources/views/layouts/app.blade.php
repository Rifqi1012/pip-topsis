<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($header) ? trim(strip_tags($header)) . ' - ' : '' }}{{ config('app.name', 'SIBATU - Sistem Bantuan Tepat Sasaran') }}</title>

    <!-- Fonts: Plus Jakarta Sans (UI) & Fraunces (Editorial Display/Numbers) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
</head>
<body
    class="antialiased bg-[#FAF7F2] text-[#1C2420]"
    x-data="{
        sidebarOpen: false,
        sidebarCollapsed: localStorage.getItem('sibatu_sidebar_collapsed') === 'true',
        toggleCollapse() {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            localStorage.setItem('sibatu_sidebar_collapsed', this.sidebarCollapsed ? 'true' : 'false');
        }
    }"
    @keydown.escape.window="sidebarOpen = false"
>
    @php
        $role = Auth::user()->role;
        $roleLabel = $role === 'kepala_sekolah' ? 'Kepala Sekolah' : 'Wali Kelas';
        $dashboardRoute = match($role) {
            'wali_kelas' => 'wali-kelas.dashboard',
            'kepala_sekolah' => 'kepala-sekolah.dashboard',
            default => 'dashboard',
        };

        $navBase = 'group flex items-center gap-3 min-h-[44px] px-3.5 py-2.5 rounded-xl text-sm transition-colors';
        $navActive = 'bg-[#1B4D3E] text-white font-bold shadow-[0_2px_0_0_#102D24]';
        $navInactive = 'text-[#2E3A34] hover:bg-[#EAE4D8] font-semibold';
    @endphp

    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 btn-primary">
        Lompat ke konten utama
    </a>

    <div class="min-h-screen flex flex-col">
        <!-- Top Header -->
        <header class="sticky top-0 z-40 h-[70px] bg-white border-b-2 border-[#D6CFC2] flex items-center">
            <div class="w-full flex items-center justify-between gap-3 px-4 sm:px-6">
                <div class="flex items-center gap-3 min-w-0">
                    <!-- Mobile Menu Button (Labeled, not bare hamburger) -->
                    <button
                        type="button"
                        @click="sidebarOpen = !sidebarOpen"
                        :aria-expanded="sidebarOpen.toString()"
                        aria-controls="app-sidebar"
                        aria-label="Buka menu navigasi"
                        class="lg:hidden inline-flex items-center gap-2 min-h-[44px] px-3 py-2 rounded-xl border-[1.5px] border-[#C9C0B1] bg-[#FAF7F2] hover:bg-[#F3EFE6] text-[#1C2420] font-bold text-sm"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        <span>Menu</span>
                    </button>

                    <!-- Desktop Collapse Button -->
                    <button
                        type="button"
                        @click="toggleCollapse()"
                        :aria-expanded="(!sidebarCollapsed).toString()"
                        aria-label="Perkecil atau perluas sidebar"
                        class="hidden lg:inline-flex items-center justify-center w-10 h-10 rounded-xl border-[1.5px] border-[#D6CFC2] bg-[#FAF7F2] hover:bg-[#F3EFE6] text-[#1C2420]"
                        title="Tampilkan / Sembunyikan label menu"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h16"></path></svg>
                    </button>

                    <!-- Brand Identity -->
                    <a href="{{ route($dashboardRoute) }}" class="flex items-center gap-3 min-w-0">
                        @if(file_exists(public_path('images/logo.png')))
                            <img src="{{ asset('images/logo.png') }}" alt="Logo Sekolah" width="44" height="44" class="h-11 w-11 object-contain flex-shrink-0">
                        @else
                            <div class="w-11 h-11 rounded-xl bg-[#1B4D3E] text-white flex items-center justify-center font-editorial font-bold text-lg flex-shrink-0">S</div>
                        @endif
                        <div class="min-w-0">
                            <span class="block font-editorial font-bold text-lg leading-none text-[#1B4D3E] tracking-tight">SIBATU</span>
                            <span class="hidden sm:block text-xs font-semibold text-[#4B5851] mt-0.5 truncate">Sistem Bantuan Tepat Sasaran</span>
                        </div>
                    </a>
                </div>

                <!-- Right: Role Indicator & Profile Dropdown -->
                <div class="flex items-center gap-2.5 flex-shrink-0">
                    <span class="hidden md:inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-[#E8F1EE] text-[#1B4D3E] border border-[#B8D4CA]">
                        {{ $roleLabel }}
                    </span>

                    <div class="relative" x-data="{ dropdownOpen: false }" @keydown.escape="dropdownOpen = false">
                        <button
                            type="button"
                            @click="dropdownOpen = !dropdownOpen"
                            :aria-expanded="dropdownOpen.toString()"
                            aria-haspopup="menu"
                            class="flex items-center gap-2.5 min-h-[44px] px-2.5 sm:px-3 py-1.5 rounded-xl border-[1.5px] border-[#D6CFC2] bg-[#FAF7F2] hover:bg-[#F3EFE6] transition-colors"
                        >
                            <span class="w-8 h-8 rounded-lg bg-[#1B4D3E] text-white flex items-center justify-center font-bold text-sm flex-shrink-0">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <span class="hidden sm:block text-sm font-bold text-[#1C2420] max-w-[140px] truncate">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-[#4B5851]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <div
                            x-show="dropdownOpen"
                            x-cloak
                            @click.away="dropdownOpen = false"
                            x-transition.opacity
                            role="menu"
                            class="absolute right-0 mt-2 w-60 bg-white rounded-xl border-[1.5px] border-[#D6CFC2] shadow-lg overflow-hidden z-50"
                        >
                            <div class="px-4 py-3 bg-[#F3EFE6] border-b border-[#D6CFC2]">
                                <p class="text-sm font-bold text-[#1C2420] truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs font-medium text-[#4B5851] truncate">{{ Auth::user()->email }}</p>
                                <span class="inline-block mt-1.5 px-2 py-0.5 rounded text-[11px] font-bold bg-[#E8F1EE] text-[#1B4D3E] border border-[#B8D4CA]">{{ $roleLabel }}</span>
                            </div>
                            <div class="p-1.5">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" role="menuitem" class="w-full flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-bold text-[#991B1B] hover:bg-[#FEF2F2] transition-colors min-h-[44px]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        Keluar dari Akun
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex flex-1 min-h-0">
            <!-- Sidebar -->
            <aside
                id="app-sidebar"
                :class="[
                    sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
                    sidebarCollapsed ? 'lg:w-20' : 'lg:w-68'
                ]"
                class="fixed inset-y-0 left-0 z-50 lg:z-20 w-72 bg-[#F3EFE6] border-r-2 border-[#D6CFC2] transition-all duration-200 lg:sticky lg:top-[70px] lg:h-[calc(100vh-70px)] flex flex-col pt-4 pb-6 flex-shrink-0"
            >
                <!-- Mobile Drawer Header -->
                <div class="flex items-center justify-between px-4 pb-3 mb-2 border-b border-[#D6CFC2] lg:hidden">
                    <span class="font-editorial font-bold text-lg text-[#1B4D3E]">Menu Navigasi</span>
                    <button
                        type="button"
                        @click="sidebarOpen = false"
                        aria-label="Tutup menu navigasi"
                        class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-[#C9C0B1] text-[#1C2420] font-bold"
                    >
                        &times;
                    </button>
                </div>

                <nav aria-label="Navigasi utama" class="flex-1 px-3 space-y-5 overflow-y-auto">
                    <!-- Group 1: Utama -->
                    <div>
                        <p x-show="!sidebarCollapsed" class="px-3 mb-1.5 text-[11px] font-extrabold uppercase tracking-wider text-[#4B5851]">
                            Menu Utama
                        </p>
                        <div class="space-y-1">
                            <a
                                href="{{ route($dashboardRoute) }}"
                                title="Dashboard"
                                class="{{ $navBase }} {{ request()->routeIs('*.dashboard') || request()->routeIs('dashboard') ? $navActive : $navInactive }}"
                                :class="sidebarCollapsed ? 'lg:justify-center lg:px-2' : ''"
                            >
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                <span x-show="!sidebarCollapsed" class="truncate">Beranda</span>
                            </a>

                            <a
                                href="{{ route('siswas.index') }}"
                                title="Data Siswa"
                                class="{{ $navBase }} {{ request()->routeIs('siswas.*') || request()->routeIs('siswa-import.*') ? $navActive : $navInactive }}"
                                :class="sidebarCollapsed ? 'lg:justify-center lg:px-2' : ''"
                            >
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <span x-show="!sidebarCollapsed" class="truncate">Data Siswa</span>
                            </a>

                            @if($role === 'wali_kelas')
                            <a
                                href="{{ route('penilaian-siswa.index') }}"
                                title="Penilaian Siswa"
                                class="{{ $navBase }} {{ request()->routeIs('penilaian-siswa.*') ? $navActive : $navInactive }}"
                                :class="sidebarCollapsed ? 'lg:justify-center lg:px-2' : ''"
                            >
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                <span x-show="!sidebarCollapsed" class="truncate">Penilaian Siswa</span>
                            </a>
                            @endif
                        </div>
                    </div>

                    @if($role === 'kepala_sekolah')
                    <!-- Group 2: Data Master -->
                    <div>
                        <p x-show="!sidebarCollapsed" class="px-3 mb-1.5 text-[11px] font-extrabold uppercase tracking-wider text-[#4B5851]">
                            Data Master
                        </p>
                        <div class="space-y-1">
                            <a
                                href="{{ route('kelas.index') }}"
                                title="Data Kelas"
                                class="{{ $navBase }} {{ request()->routeIs('kelas.*') ? $navActive : $navInactive }}"
                                :class="sidebarCollapsed ? 'lg:justify-center lg:px-2' : ''"
                            >
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                <span x-show="!sidebarCollapsed" class="truncate">Data Kelas</span>
                            </a>

                            <a
                                href="{{ route('tahun-ajaran.index') }}"
                                title="Periode Tahun Ajaran"
                                class="{{ $navBase }} {{ request()->routeIs('tahun-ajaran.*') ? $navActive : $navInactive }}"
                                :class="sidebarCollapsed ? 'lg:justify-center lg:px-2' : ''"
                            >
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span x-show="!sidebarCollapsed" class="truncate">Tahun Ajaran</span>
                            </a>

                            <a
                                href="{{ route('kriteria.index') }}"
                                title="Data Kriteria"
                                class="{{ $navBase }} {{ request()->routeIs('kriteria.*') ? $navActive : $navInactive }}"
                                :class="sidebarCollapsed ? 'lg:justify-center lg:px-2' : ''"
                            >
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                <span x-show="!sidebarCollapsed" class="truncate">Data Kriteria</span>
                            </a>
                        </div>
                    </div>

                    <!-- Group 3: Seleksi & Sistem -->
                    <div>
                        <p x-show="!sidebarCollapsed" class="px-3 mb-1.5 text-[11px] font-extrabold uppercase tracking-wider text-[#4B5851]">
                            Seleksi &amp; Sistem
                        </p>
                        <div class="space-y-1">
                            <a
                                href="{{ route('topsis.index') }}"
                                title="Proses Seleksi"
                                class="{{ $navBase }} {{ request()->routeIs('topsis.*') ? $navActive : $navInactive }}"
                                :class="sidebarCollapsed ? 'lg:justify-center lg:px-2' : ''"
                            >
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                <span x-show="!sidebarCollapsed" class="truncate">Proses Seleksi</span>
                            </a>

                            <a
                                href="{{ route('pengaturan.index') }}"
                                title="Pengaturan Kuota"
                                class="{{ $navBase }} {{ request()->routeIs('pengaturan.*') ? $navActive : $navInactive }}"
                                :class="sidebarCollapsed ? 'lg:justify-center lg:px-2' : ''"
                            >
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span x-show="!sidebarCollapsed" class="truncate">Pengaturan Kuota</span>
                            </a>

                            <a
                                href="{{ route('users.index') }}"
                                title="Kelola Akun"
                                class="{{ $navBase }} {{ request()->routeIs('users.*') ? $navActive : $navInactive }}"
                                :class="sidebarCollapsed ? 'lg:justify-center lg:px-2' : ''"
                            >
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span x-show="!sidebarCollapsed" class="truncate">Kelola Akun</span>
                            </a>
                        </div>
                    </div>
                    @endif
                </nav>

                <!-- Sidebar Footer Logout -->
                <div class="px-3 pt-4 mt-4 border-t border-[#D6CFC2]">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            title="Keluar"
                            class="w-full flex items-center gap-3 min-h-[44px] px-3.5 py-2.5 rounded-xl text-sm font-bold text-[#991B1B] hover:bg-[#FEE2E2] transition-colors"
                            :class="sidebarCollapsed ? 'lg:justify-center lg:px-2' : ''"
                        >
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span x-show="!sidebarCollapsed">Keluar</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Mobile Overlay -->
            <div
                x-show="sidebarOpen"
                x-cloak
                @click="sidebarOpen = false"
                x-transition.opacity
                class="fixed inset-0 z-40 bg-[#1C2420]/50 lg:hidden"
                aria-hidden="true"
            ></div>

            <!-- Main Content Area -->
            <main id="main-content" class="flex-1 min-w-0 p-4 sm:p-6 lg:p-8 pb-16">
                <div class="max-w-6xl mx-auto">
                    @isset($header)
                        <div class="mb-6 pb-4 border-b border-[#D6CFC2] flex flex-col sm:flex-row sm:items-end justify-between gap-2">
                            <h1 class="font-editorial text-2xl sm:text-3xl font-bold text-[#1C2420] tracking-tight">
                                {{ $header }}
                            </h1>
                            <span class="text-xs font-semibold text-[#4B5851]">
                                {{ now()->translatedFormat('l, j F Y') }}
                            </span>
                        </div>
                    @endisset

                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    @if(session('success'))
    <script>
        window.addEventListener('DOMContentLoaded', function () {
            if (window.Swal) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: @json(session('success')),
                    confirmButtonText: 'Oke, Mengerti',
                    confirmButtonColor: '#1B4D3E',
                });
            }
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        window.addEventListener('DOMContentLoaded', function () {
            if (window.Swal) {
                Swal.fire({
                    icon: 'error',
                    title: 'Perhatian',
                    text: @json(session('error')),
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#991B1B',
                });
            }
        });
    </script>
    @endif
</body>
</html>
