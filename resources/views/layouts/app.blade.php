<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PIP TOPSIS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Inter', sans-serif; }
        </style>
    </head>
    <body class="font-sans antialiased text-slate-800 bg-slate-50" x-data="{ sidebarOpen: false }">
        <div class="flex flex-col h-screen overflow-hidden">
            
            <!-- Top Header -->
            <header class="w-full flex items-center justify-between px-6 py-4 bg-white border-b-4 border-yellow-400 z-20 flex-shrink-0 shadow-sm">
                <div class="flex items-center gap-4">
                    <!-- Mobile Menu Button -->
                    <button @click="sidebarOpen = !sidebarOpen" class="text-slate-500 focus:outline-none lg:hidden hover:text-blue-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        @if(file_exists(public_path('images/logo.png')))
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 w-auto drop-shadow-sm">
                        @else
                            <div class="w-12 h-12 bg-blue-100 border-2 border-blue-600 rounded-lg flex items-center justify-center font-bold text-blue-800 text-xs">LOGO</div>
                        @endif
                    </div>
                    <!-- Title -->
                    <div class="hidden sm:block">
                        <h1 class="font-bold text-sm md:text-base leading-tight tracking-tight text-slate-800">
                            SPK PENERIMA PROGRAM<br>
                            <span class="text-blue-600">INDONESIA PINTAR (PIP)</span><br>
                            METODE TOPSIS
                        </h1>
                    </div>
                </div>
                
                <!-- Profile Dropdown -->
                <div class="flex items-center">
                    <div class="relative" x-data="{ dropdownOpen: false }">
                        <button @click="dropdownOpen = !dropdownOpen" class="flex items-center gap-2 px-3 py-2 border border-slate-200 rounded-full hover:bg-slate-50 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="font-medium text-sm hidden sm:block text-slate-700">{{ explode(' ', Auth::user()->name)[0] }}</span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        
                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-transition.opacity class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-50 border border-slate-100 overflow-hidden" style="display: none;">
                            <div class="px-4 py-3 border-b border-slate-100 bg-slate-50">
                                <p class="text-sm text-slate-900 font-semibold">{{ Auth::user()->name }}</p>
                                <p class="text-xs font-medium text-slate-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-sm text-red-600 font-medium hover:bg-red-50 transition-colors">
                                    Logout
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <div class="flex flex-1 overflow-hidden">
                <!-- Sidebar -->
                <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-slate-200 transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col pt-16 lg:pt-0">
                    <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
                        @php
                            $dashboardRoute = match(Auth::user()->role) {
                                'wali_kelas' => 'wali-kelas.dashboard',
                                'tata_usaha' => 'tata-usaha.dashboard',
                                'kepala_sekolah' => 'kepala-sekolah.dashboard',
                                default => 'dashboard',
                            };
                            
                            $linkActive = 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-semibold';
                            $linkInactive = 'text-slate-600 hover:bg-slate-50 border-l-4 border-transparent font-medium';
                            $iconActive = 'text-blue-600';
                            $iconInactive = 'text-slate-400 group-hover:text-slate-600';
                        @endphp
                        
                        <!-- Dashboard -->
                        <a href="{{ route($dashboardRoute) }}" class="flex items-center px-4 py-3 {{ request()->routeIs('*.dashboard') ? $linkActive : $linkInactive }} rounded-r-md transition-colors group">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('*.dashboard') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            Dashboard
                        </a>
                        
                        <!-- Data Siswa -->
                        @if(in_array(Auth::user()->role, ['wali_kelas', 'tata_usaha']))
                        <a href="{{ route('siswas.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('siswas.*') ? $linkActive : $linkInactive }} rounded-r-md transition-colors group mt-1">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('siswas.*') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            Data Siswa
                        </a>
                        @endif

                        <!-- Master Kriteria & Sub -->
                        @if(Auth::user()->role === 'tata_usaha')
                        <a href="{{ route('kriteria.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('kriteria.*') ? $linkActive : $linkInactive }} rounded-r-md transition-colors group mt-1">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('kriteria.*') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            Data Kriteria
                        </a>

                        <a href="{{ route('sub_kriteria.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('sub_kriteria.*') ? $linkActive : $linkInactive }} rounded-r-md transition-colors group mt-1">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('sub_kriteria.*') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            Data Penilaian
                        </a>
                        @endif

                        <!-- Perhitungan TOPSIS -->
                        @if(in_array(Auth::user()->role, ['tata_usaha', 'kepala_sekolah']))
                        <a href="{{ route('topsis.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('topsis.*') ? $linkActive : $linkInactive }} rounded-r-md transition-colors group mt-1">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('topsis.*') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            Perhitungan TOPSIS
                        </a>
                        @endif

                        <!-- Pengaturan -->
                        @if(Auth::user()->role === 'tata_usaha')
                        <a href="{{ route('pengaturan.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('pengaturan.*') ? $linkActive : $linkInactive }} rounded-r-md transition-colors group mt-1">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('pengaturan.*') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Pengaturan Sistem
                        </a>

                        <a href="{{ route('users.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('users.*') ? $linkActive : $linkInactive }} rounded-r-md transition-colors group mt-1">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('users.*') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Kelola User
                        </a>
                        @endif
                        
                        <div class="pt-8">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center px-4 py-3 text-red-600 hover:bg-red-50 rounded-r-md transition-colors font-medium border-l-4 border-transparent">
                                    <svg class="w-5 h-5 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </nav>
                </aside>

                <!-- Overlay for mobile -->
                <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 z-30 bg-black opacity-50 lg:hidden" style="display: none;"></div>

                <!-- Main Content Area -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-6 md:p-8 relative pb-20">
                    {{ $slot }}
                    
                    <!-- Footer attached to bottom of main area -->
                    <!-- <footer class="absolute bottom-0 left-0 w-full px-8 py-6 text-sm text-slate-500 flex flex-col md:flex-row justify-between items-center border-t border-slate-200 bg-slate-50">
                        <div class="flex items-center gap-2 mb-2 md:mb-0">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span>Tanggal : {{ now()->format('j F Y') }}</span>
                        </div>
                        <div class="font-medium">
                            &copy; {{ date('Y') }} SPK Penerima PIP - <span class="text-blue-600">Metode TOPSIS</span>
                        </div>
                    </footer> -->
                </main>
            </div>
        </div>

        @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session("success") }}',
                    confirmButtonColor: '#2563eb',
                });
            });
        </script>
        @endif
        @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session("error") }}',
                    confirmButtonColor: '#dc2626',
                });
            });
        </script>
        @endif
    </body>
</html>
