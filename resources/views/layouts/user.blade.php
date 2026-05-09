<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Warga') - Puri Bunga</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        :root { --navy:#0f2557; }
        body { background: #f8fafc; }
        .sidebar { background: linear-gradient(180deg, #0f2557 0%, #091840 100%); transition: transform 0.3s cubic-bezier(0.4,0,0.2,1); }
        .sidebar-link { transition: all 0.2s; }
        .sidebar-link:hover, .sidebar-link.active { background: rgba(255,255,255,0.1); color: #fff; }
        .card-stat { background: #fff; border-radius: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        @media (max-width: 1024px) { .sidebar { transform: translateX(-100%); } .sidebar.open { transform: translateX(0); } }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body x-data="{ sidebarOpen: window.innerWidth >= 1024 }">

    {{-- Overlay Mobile --}}
    <div x-show="sidebarOpen && window.innerWidth < 1024"
         @click="sidebarOpen=false"
         class="fixed inset-0 z-30 bg-black/40 lg:hidden"
         x-cloak></div>

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="sidebar fixed top-0 left-0 h-full w-72 z-40 flex flex-col shadow-xl">
        <div class="px-8 py-8">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-blue-500 flex items-center justify-center shadow-lg shadow-blue-500/30">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                </div>
                <div>
                    <h1 class="text-white font-bold text-lg leading-none">Puri Bunga 2</h1>
                    <p class="text-blue-400 text-[10px] font-bold uppercase tracking-widest mt-1">Portal Warga</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 px-4 space-y-2">
            <a href="{{ route('user.dashboard') }}" class="sidebar-link flex items-center gap-4 px-4 py-3.5 rounded-2xl text-blue-100/70 text-sm font-medium {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="{{ route('user.iuran') }}" class="sidebar-link flex items-center gap-4 px-4 py-3.5 rounded-2xl text-blue-100/70 text-sm font-medium {{ request()->routeIs('user.iuran') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Iuran Saya
            </a>
            <a href="{{ route('user.pengumuman') }}" class="sidebar-link flex items-center gap-4 px-4 py-3.5 rounded-2xl text-blue-100/70 text-sm font-medium {{ request()->routeIs('user.pengumuman') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                Pengumuman
            </a>
        </nav>

        <div class="p-6">
            <div class="p-5 bg-white/5 rounded-3xl border border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center font-bold text-white shadow-lg">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-white text-sm font-bold truncate">{{ auth()->user()->name }}</p>
                        <p class="text-blue-400 text-[10px] font-bold uppercase tracking-wider">Warga</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl bg-red-500/10 text-red-400 text-xs font-bold hover:bg-red-500 hover:text-white transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        LOGOUT
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main Content --}}
    <div :class="sidebarOpen && window.innerWidth >= 1024 ? 'lg:ml-72' : ''" class="min-h-screen transition-all duration-300">
        <header class="bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-20">
            <div class="flex items-center justify-between px-6 h-20">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 text-slate-500 hover:bg-slate-100 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div>
                        <h2 class="text-slate-800 font-extrabold text-lg">@yield('page-title', 'Halo, Tetangga!')</h2>
                        <p class="text-slate-400 text-xs font-medium">{{ date('l, d F Y') }}</p>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-6 md:p-8">
            @yield('content')
        </main>
    </div>
</body>
</html>
