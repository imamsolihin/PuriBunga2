<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Puri Bunga</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        :root { --navy:#0f2557; --navy-light:#1a3a8f; }
        body { background: #f1f5f9; }
        .sidebar { background: linear-gradient(180deg, #0f2557 0%, #091840 100%); }
        .sidebar-link { transition: all 0.2s; }
        .sidebar-link:hover, .sidebar-link.active { background: rgba(255,255,255,0.12); color: #fff; }
        .card-stat { background: #fff; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.07); }
        @media (max-width: 1024px) { .sidebar { transform: translateX(-100%); } .sidebar.open { transform: translateX(0); } }
        .sidebar { transition: transform 0.3s cubic-bezier(0.4,0,0.2,1); }
        .overlay { transition: opacity 0.3s; }
        .page-content { transition: margin-left 0.3s; }
        @keyframes fadeIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
        .animate-in { animation: fadeIn 0.4s ease forwards; }
    </style>
</head>
<body x-data="{
    open: window.innerWidth >= 1024,
    get isDesktop() { return window.innerWidth >= 1024; }
}" @resize.window="if (window.innerWidth >= 1024) { open = true }">

    {{-- Overlay Mobile --}}
    <div x-show="open && !isDesktop"
         @click="open = false"
         class="fixed inset-0 z-30 bg-black/50 lg:hidden overlay"
         x-cloak
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    </div>

    {{-- Sidebar --}}
    <aside :style="open ? 'transform:translateX(0)' : 'transform:translateX(-100%)'"
           class="sidebar fixed top-0 left-0 h-full w-64 z-40 flex flex-col shadow-2xl">
        <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
            </div>
            <div>
                <div class="text-white font-bold text-sm">Puri Bunga 2</div>
                <div class="text-blue-300 text-xs">Admin Panel</div>
            </div>
        </div>
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            <p class="text-blue-400 text-xs font-semibold uppercase tracking-wider px-3 py-2">Menu Utama</p>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-blue-100 text-sm {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 6a1 1 0 011-1h4a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zm8 0a1 1 0 011-1h4a1 1 0 011 1v6a1 1 0 01-1 1h-4a1 1 0 01-1-1v-6z"/></svg>
                Dashboard
            </a>
            <p class="text-blue-400 text-xs font-semibold uppercase tracking-wider px-3 py-2 mt-2">Data Master</p>
            <a href="{{ route('admin.warga.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-blue-100 text-sm {{ request()->routeIs('admin.warga*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                Data Warga
            </a>
            <a href="{{ route('admin.kategori-iuran.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-blue-100 text-sm {{ request()->routeIs('admin.kategori-iuran*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                Kategori Iuran
            </a>
            <a href="{{ route('admin.coa.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-blue-100 text-sm {{ request()->routeIs('admin.coa*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                COA / Akun
            </a>
            <p class="text-blue-400 text-xs font-semibold uppercase tracking-wider px-3 py-2 mt-2">Transaksi</p>
            <a href="{{ route('admin.iuran.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-blue-100 text-sm {{ request()->routeIs('admin.iuran*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Iuran Warga
            </a>
            <a href="{{ route('admin.jurnal.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-blue-100 text-sm {{ request()->routeIs('admin.jurnal*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Jurnal Umum
            </a>
            <a href="{{ route('admin.kas-kecil.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-blue-100 text-sm {{ request()->routeIs('admin.kas-kecil*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Kas Kecil
            </a>
            <a href="{{ route('admin.pengeluaran.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-blue-100 text-sm {{ request()->routeIs('admin.pengeluaran*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.407 2.45 1M12 8V6m0 2v12m0 0h1m-1 0H11"/></svg>
                Pengeluaran
            </a>
            <a href="{{ route('admin.laporan.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-blue-100 text-sm {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Laporan Keuangan
            </a>
            <p class="text-blue-400 text-xs font-semibold uppercase tracking-wider px-3 py-2 mt-2">Lainnya</p>
            <a href="{{ route('admin.pengumuman.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-blue-100 text-sm {{ request()->routeIs('admin.pengumuman*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                Pengumuman
            </a>
        </nav>
        <div class="p-4 border-t border-white/10">
            <div class="flex items-center gap-3 px-2">
                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                    <span class="text-white text-xs font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-white text-xs font-semibold truncate">{{ auth()->user()->name }}</div>
                    <div class="text-blue-400 text-xs">Administrator</div>
                </div>
            </div>
        </div>
    </aside>

    {{-- Main Content --}}
    <div :class="open && isDesktop ? 'ml-64' : ''" class="min-h-screen transition-all duration-300">
        {{-- Top Bar --}}
        <header class="bg-white border-b border-slate-200 sticky top-0 z-20 shadow-sm">
            <div class="flex items-center justify-between px-4 sm:px-6 h-16">
                <div class="flex items-center gap-4">
                    <button @click="open = !open" type="button" class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-slate-100 transition-colors active:scale-95" aria-label="Toggle sidebar">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h1 class="font-semibold text-slate-800 text-sm sm:text-base">@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block text-right">
                        <div class="text-xs font-semibold text-slate-700">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-slate-400">Admin</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 text-sm text-red-500 hover:text-red-700 hover:bg-red-50 px-3 py-2 rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            <span class="hidden sm:block">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="p-4 sm:p-6 animate-in">
            @if(session('success'))
                <div class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>
</html>
