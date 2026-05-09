<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Laporan Keuangan Perumahan - Puri Bunga. Kelola keuangan perumahan Anda secara transparan dan profesional.">
    <title>Puri Bunga - Sistem Laporan Keuangan Perumahan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        :root {
            --navy: #0f2557;
            --navy-light: #1a3a8f;
            --navy-dark: #091840;
            --accent: #3b82f6;
            --accent-light: #60a5fa;
            --gray-bg: #f1f5f9;
        }
        body { background: #fff; color: #1e293b; }
        .nav-blur { backdrop-filter: blur(12px); background: rgba(15,37,87,0.92); }
        .hero-gradient { background: linear-gradient(135deg, #0f2557 0%, #1a3a8f 50%, #1e40af 100%); }
        .card-hover { transition: transform 0.3s, box-shadow 0.3s; }
        .card-hover:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(15,37,87,0.2); }
        .sidebar-overlay { transition: opacity 0.3s; }
        .sidebar-panel { transition: transform 0.35s cubic-bezier(0.4,0,0.2,1); }
        .btn-primary { background: linear-gradient(135deg, #1a3a8f, #3b82f6); transition: all 0.3s; }
        .btn-primary:hover { background: linear-gradient(135deg, #0f2557, #1a3a8f); transform: translateY(-1px); box-shadow: 0 8px 20px rgba(59,130,246,0.4); }
        @keyframes fadeInUp { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:translateY(0); } }
        @keyframes float { 0%,100% { transform:translateY(0px); } 50% { transform:translateY(-12px); } }
        .animate-fade-up { animation: fadeInUp 0.8s ease forwards; }
        .animate-float { animation: float 4s ease-in-out infinite; }
        .delay-1 { animation-delay: 0.2s; }
        .delay-2 { animation-delay: 0.4s; }
        .delay-3 { animation-delay: 0.6s; }
    </style>
</head>
<body x-data="{ sidebarOpen: false }">

    {{-- Overlay --}}
    <div x-show="sidebarOpen"
         x-cloak
         @click="sidebarOpen = false"
         class="fixed inset-0 z-40 bg-black/50 sidebar-overlay"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="backdrop-filter:blur(4px);">
    </div>

    {{-- Sidebar --}}
    <div x-show="sidebarOpen"
         x-cloak
         class="fixed top-0 left-0 h-full w-72 z-50 bg-[#0f2557] shadow-2xl sidebar-panel"
         x-transition:enter="transition ease-out duration-350"
         x-transition:enter-start="transform -translate-x-full"
         x-transition:enter-end="transform translate-x-0"
         x-transition:leave="transition ease-in duration-250"
         x-transition:leave-start="transform translate-x-0"
         x-transition:leave-end="transform -translate-x-full">
        <div class="flex items-center justify-between p-6 border-b border-blue-800">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                </div>
                <span class="text-white font-bold text-lg">Puri Bunga</span>
            </div>
            <button @click="sidebarOpen = false" class="text-blue-300 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <nav class="p-6 space-y-2">
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-blue-100 hover:bg-blue-800/60 hover:text-white transition-all duration-200 group">
                <svg class="w-5 h-5 text-blue-400 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                <span class="font-medium">Beranda</span>
            </a>
            <a href="#tentang" @click="sidebarOpen = false" class="flex items-center gap-3 px-4 py-3 rounded-xl text-blue-100 hover:bg-blue-800/60 hover:text-white transition-all duration-200 group">
                <svg class="w-5 h-5 text-blue-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="font-medium">Tentang Sistem</span>
            </a>
            <a href="{{ route('login') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-blue-100 hover:bg-blue-800/60 hover:text-white transition-all duration-200 group">
                <svg class="w-5 h-5 text-blue-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                <span class="font-medium">Login</span>
            </a>
        </nav>
        <div class="absolute bottom-8 left-6 right-6">
            <div class="bg-blue-800/40 rounded-xl p-4 text-center">
                <p class="text-blue-300 text-xs">Puri Bunga 2 Residence</p>
                <p class="text-blue-100 text-sm font-semibold mt-1">Pengelolaan Keuangan Modern</p>
            </div>
        </div>
    </div>

    {{-- Navbar --}}
    <nav class="fixed top-0 left-0 right-0 z-30 nav-blur border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" id="hamburger-btn"
                            class="w-10 h-10 flex flex-col items-center justify-center gap-1.5 rounded-xl hover:bg-white/10 transition-all duration-200 group">
                        <span class="w-5 h-0.5 bg-white rounded-full transition-all"></span>
                        <span class="w-5 h-0.5 bg-white rounded-full transition-all"></span>
                        <span class="w-5 h-0.5 bg-white rounded-full transition-all"></span>
                    </button>
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                        </div>
                        <span class="text-white font-bold text-lg hidden sm:block">Puri Bunga</span>
                    </a>
                </div>
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('home') }}" class="text-blue-200 hover:text-white text-sm font-medium transition-colors">Beranda</a>
                    <a href="#tentang" class="text-blue-200 hover:text-white text-sm font-medium transition-colors">Tentang</a>
                    <a href="#fitur" class="text-blue-200 hover:text-white text-sm font-medium transition-colors">Fitur</a>
                </div>
                <a href="{{ route('login') }}" class="btn-primary text-white px-5 py-2 rounded-xl text-sm font-semibold shadow-lg">
                    Login
                </a>
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="hero-gradient min-h-screen flex items-center pt-16 relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-20 right-10 w-72 h-72 bg-blue-400/10 rounded-full blur-3xl animate-float"></div>
            <div class="absolute bottom-20 left-10 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl animate-float delay-2"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div>
                    <div class="inline-flex items-center gap-2 bg-blue-500/20 border border-blue-400/30 rounded-full px-4 py-2 mb-6 animate-fade-up">
                        <div class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></div>
                        <span class="text-blue-200 text-sm font-medium">Sistem Keuangan Modern</span>
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight animate-fade-up delay-1">
                        Kelola Keuangan<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-cyan-300">Perumahan</span><br>
                        Lebih Mudah
                    </h1>
                    <p class="mt-6 text-blue-200 text-lg leading-relaxed animate-fade-up delay-2">
                        Sistem laporan keuangan perumahan yang transparan, profesional, dan mudah digunakan untuk pengurus dan warga Puri Bunga 2 Residence.
                    </p>
                    <div class="mt-10 flex flex-col sm:flex-row gap-4 animate-fade-up delay-3">
                        <a href="{{ route('login') }}"
                           class="btn-primary text-white px-8 py-4 rounded-2xl font-semibold text-base shadow-xl text-center">
                            Masuk ke Sistem →
                        </a>
                        <a href="#fitur"
                           class="px-8 py-4 rounded-2xl font-semibold text-base border-2 border-blue-400/40 text-blue-200 hover:bg-blue-800/40 transition-all text-center">
                            Lihat Fitur
                        </a>
                    </div>
                    <div class="mt-12 grid grid-cols-3 gap-6 animate-fade-up delay-3">
                        <div class="text-center">
                            <div class="text-3xl font-extrabold text-white">100%</div>
                            <div class="text-blue-300 text-xs mt-1">Transparan</div>
                        </div>
                        <div class="text-center border-x border-blue-700">
                            <div class="text-3xl font-extrabold text-white">Real-time</div>
                            <div class="text-blue-300 text-xs mt-1">Data Keuangan</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-extrabold text-white">Aman</div>
                            <div class="text-blue-300 text-xs mt-1">Role-based Access</div>
                        </div>
                    </div>
                </div>
                <div class="hidden lg:block animate-float">
                    <div class="bg-white/10 backdrop-blur-md rounded-3xl p-6 border border-white/20 shadow-2xl">
                        <div class="bg-[#0f2557]/80 rounded-2xl p-5">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-blue-200 text-sm font-semibold">Dashboard Keuangan</span>
                                <span class="text-xs bg-green-500/20 text-green-300 px-3 py-1 rounded-full">Live</span>
                            </div>
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div class="bg-green-500/10 border border-green-500/20 rounded-xl p-3">
                                    <div class="text-green-400 text-xs mb-1">Total Pemasukan</div>
                                    <div class="text-white font-bold text-lg">Rp 12.5 Jt</div>
                                </div>
                                <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-3">
                                    <div class="text-red-400 text-xs mb-1">Total Pengeluaran</div>
                                    <div class="text-white font-bold text-lg">Rp 8.2 Jt</div>
                                </div>
                            </div>
                            <div class="bg-blue-600/20 rounded-xl p-3 mb-3">
                                <div class="text-blue-300 text-xs mb-1">Saldo Kas</div>
                                <div class="text-white font-extrabold text-2xl">Rp 4.3 Jt</div>
                                <div class="h-1.5 bg-blue-900 rounded-full mt-2"><div class="h-1.5 bg-gradient-to-r from-blue-400 to-cyan-400 rounded-full w-2/3"></div></div>
                            </div>
                            <div class="space-y-2">
                                @foreach([['Iuran Keamanan','Lunas','text-green-400'],['Iuran Kebersihan','Lunas','text-green-400'],['Kas Lingkungan','Belum','text-yellow-400']] as $item)
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-blue-200">{{ $item[0] }}</span>
                                    <span class="{{ $item[2] }}">{{ $item[1] }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 60L1440 60L1440 0C1440 0 1080 60 720 60C360 60 0 0 0 0L0 60Z" fill="white"/>
            </svg>
        </div>
    </section>

    {{-- Tentang --}}
    <section id="tentang" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block text-sm font-semibold text-blue-600 bg-blue-50 px-4 py-2 rounded-full mb-4">Tentang Sistem</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-[#0f2557] mb-6">Pengelolaan Keuangan yang Transparan</h2>
            <p class="text-slate-500 text-lg max-w-3xl mx-auto leading-relaxed">
                Sistem Laporan Keuangan Perumahan Puri Bunga 2 dirancang untuk memberikan kemudahan bagi pengurus dalam mencatat dan mengelola keuangan perumahan, serta transparansi bagi warga dalam memantau status pembayaran iuran mereka.
            </p>
            <div class="mt-16 grid md:grid-cols-3 gap-8">
                @foreach([
                    ['Untuk Pengurus','Kelola iuran, jurnal keuangan, kas kecil, dan COA dalam satu sistem terintegrasi.','M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                    ['Untuk Warga','Pantau status pembayaran, riwayat iuran, dan baca pengumuman terbaru dari pengurus.','M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'],
                    ['Data Real-time','Seluruh laporan keuangan sinkron secara otomatis. Tidak ada data yang tertinggal.','M13 10V3L4 14h7v7l9-11h-7z'],
                ] as [$judul, $desc, $icon])
                <div class="bg-[#f8faff] rounded-2xl p-8 text-left card-hover border border-slate-100">
                    <div class="w-12 h-12 bg-gradient-to-br from-[#1a3a8f] to-[#3b82f6] rounded-xl flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                    </div>
                    <h3 class="text-[#0f2557] font-bold text-xl mb-3">{{ $judul }}</h3>
                    <p class="text-slate-500 leading-relaxed">{{ $desc }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Fitur --}}
    <section id="fitur" class="py-24 bg-[#f1f5f9]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block text-sm font-semibold text-blue-600 bg-blue-50 px-4 py-2 rounded-full mb-4">Fitur Utama</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#0f2557]">Semua yang Anda Butuhkan</h2>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach([
                    ['💰','Manajemen Iuran','Kelola 5 kategori iuran: Keamanan, Kebersihan, Kas Lingkungan, Perawatan, dan Lainnya. Total otomatis per warga.'],
                    ['📒','Jurnal Keuangan','Pencatatan jurnal double-entry yang akurat. Setiap transaksi tercatat dengan Debit & Kredit yang seimbang.'],
                    ['💼','Kas Kecil Terintegrasi','Setiap pengeluaran kas kecil otomatis membuat entri jurnal dan mempengaruhi saldo COA.'],
                    ['📊','Dashboard Statistik','Grafik visual pemasukan vs pengeluaran 6 bulan terakhir, total kas, dan transaksi terbaru.'],
                    ['👥','Manajemen Warga','CRUD data warga lengkap dengan akun login terintegrasi per warga.'],
                    ['📢','Pengumuman','Admin dapat menerbitkan pengumuman penting yang langsung terlihat oleh warga.'],
                ] as [$icon, $judul, $desc])
                <div class="bg-white rounded-2xl p-6 card-hover border border-slate-100 shadow-sm">
                    <div class="text-3xl mb-4">{{ $icon }}</div>
                    <h3 class="font-bold text-[#0f2557] text-lg mb-2">{{ $judul }}</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">{{ $desc }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20 hero-gradient">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-6">Siap Mengelola Keuangan Perumahan?</h2>
            <p class="text-blue-200 text-lg mb-10">Masuk sekarang menggunakan akun yang diberikan oleh pengurus perumahan.</p>
            <a href="{{ route('login') }}" class="inline-block bg-white text-[#0f2557] px-10 py-4 rounded-2xl font-bold text-base shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-200">
                Masuk ke Sistem →
            </a>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-[#091840] text-blue-200 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                    </div>
                    <span class="text-white font-bold">Puri Bunga 2</span>
                </div>
                <p class="text-sm text-blue-400">© {{ date('Y') }} Sistem Laporan Keuangan Perumahan. All rights reserved.</p>
                <a href="{{ route('login') }}" class="text-sm text-blue-300 hover:text-white transition-colors">Login →</a>
            </div>
        </div>
    </footer>

</body>
</html>
