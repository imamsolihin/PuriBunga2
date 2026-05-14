<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Puri Bunga 2 - Hunian Mewah, Aman, dan Nyaman untuk Keluarga Anda.">
    <title>Puri Bunga 2 - Premium Residence</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        :root {
            --primary: #0A192F;
            --primary-light: #112240;
            --accent: #C5A059; /* Luxury Gold */
            --accent-hover: #D4AF37;
            --text-main: #1A202C;
            --text-dim: #4A5568;
            --white: #FFFFFF;
            --bg-luxury: #FDFCFB; /* Soft Ivory/Linen */
            --bg-accent: #F4F1EA; /* Champagne */
        }

        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
            scroll-behavior: smooth;
        }

        h1, h2, h3, .font-serif {
            font-family: 'Playfair Display', serif;
        }

        body {
            background: linear-gradient(to bottom, var(--bg-luxury), var(--bg-accent));
            color: var(--primary);
            overflow-x: hidden;
            position: relative;
        }

        /* Subtle Luxury Pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("https://www.transparenttextures.com/patterns/cubes.png");
            opacity: 0.03;
            pointer-events: none;
            z-index: -1;
        }

        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .glass-dark {
            background: rgba(10, 25, 47, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .hero-section {
            background: linear-gradient(rgba(10, 25, 47, 0.6), rgba(10, 25, 47, 0.8)), 
                        url('{{ asset('images/FotoPuriBunga2.png') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .btn-premium {
            background: var(--accent);
            color: var(--primary);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .btn-premium::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
            z-index: -1;
        }

        .btn-premium:hover::before {
            left: 100%;
        }

        .btn-premium:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(204, 172, 0, 0.3);
            background: var(--accent-hover);
        }

        .card-premium {
            background: var(--white);
            border: 1px solid #E2E8F0;
            transition: all 0.4s ease;
        }

        .card-premium:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
            border-color: var(--accent);
        }

        .nav-link {
            position: relative;
            color: var(--text-dim);
            transition: color 0.3s;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent);
            transition: width 0.3s;
        }

        .nav-link:hover {
            color: var(--white);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        /* Bulletin Board Style */
        .bulletin-board {
            background: #fdf6e3;
            border: 12px solid #8b4513;
            box-shadow: inset 0 0 50px rgba(0,0,0,0.1), 0 10px 20px rgba(0,0,0,0.2);
            position: relative;
        }

        .announcement-pin {
            width: 20px;
            height: 20px;
            background: #ff4444;
            border-radius: 50%;
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .announcement-paper {
            background: #fff;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
            transform: rotate(-1deg);
            transition: transform 0.3s;
        }

        .announcement-paper:nth-child(even) {
            transform: rotate(1.5deg);
        }

        .announcement-paper:hover {
            transform: scale(1.02) rotate(0deg);
            z-index: 10;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-in {
            animation: fadeIn 0.8s ease forwards;
        }
    </style>
</head>
<body x-data="{ mobileMenu: false }">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 transition-all duration-300 glass-dark py-4">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <a href="#" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center">
                    <i data-lucide="home" class="text-primary w-6 h-6"></i>
                </div>
                <span class="text-white font-bold text-xl tracking-tight">Puri Bunga <span class="text-accent">2</span></span>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-8">
                <a href="#home" class="nav-link text-sm font-semibold">Beranda</a>
                <a href="#keuangan" class="nav-link text-sm font-semibold">Keuangan</a>
                <a href="#tentang" class="nav-link text-sm font-semibold">Tentang</a>
                <a href="#pengumuman" class="nav-link text-sm font-semibold">Pengumuman</a>
                <a href="#lokasi" class="nav-link text-sm font-semibold">Lokasi</a>
                <a href="{{ route('login') }}" class="btn-premium px-6 py-2.5 rounded-full text-sm font-bold ml-4">
                    Member Area
                </a>
            </div>

            <!-- Mobile Toggle -->
            <button @click="mobileMenu = !mobileMenu" class="md:hidden text-white">
                <i data-lucide="menu" x-show="!mobileMenu"></i>
                <i data-lucide="x" x-show="mobileMenu"></i>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenu" x-cloak class="md:hidden glass-dark absolute w-full top-full left-0 py-6 px-6 flex flex-col gap-4">
            <a href="#home" @click="mobileMenu = false" class="text-white font-medium">Beranda</a>
            <a href="#keuangan" @click="mobileMenu = false" class="text-white font-medium">Keuangan</a>
            <a href="#tentang" @click="mobileMenu = false" class="text-white font-medium">Tentang</a>
            <a href="#pengumuman" @click="mobileMenu = false" class="text-white font-medium">Pengumuman</a>
            <a href="#lokasi" @click="mobileMenu = false" class="text-white font-medium">Lokasi</a>
            <a href="{{ route('login') }}" class="btn-premium px-6 py-3 rounded-xl text-center font-bold">Member Area</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section min-h-screen flex items-center justify-center text-center px-6 pt-20">
        <div class="max-w-4xl">
            <span class="text-accent/90 font-bold tracking-[0.4em] uppercase text-xs mb-8 block animate-in" style="animation-delay: 0.1s">
                Elegance & Comfort
            </span>
            <h1 class="text-5xl md:text-8xl text-white font-extrabold mb-8 leading-tight animate-in" style="animation-delay: 0.2s">
                Hunian Eksklusif di <br>
                <span class="text-accent">Puri Bunga 2</span>
            </h1>
            <p class="text-white/80 text-lg md:text-2xl mb-12 max-w-3xl mx-auto leading-relaxed font-light animate-in" style="animation-delay: 0.3s">
                Kombinasi sempurna antara kemewahan arsitektur modern dan lingkungan yang asri untuk kebahagiaan keluarga Anda.
            </p>
            <div class="flex flex-col sm:flex-row gap-6 justify-center animate-in" style="animation-delay: 0.4s">
                <a href="#tentang" class="btn-premium px-12 py-5 rounded-full font-bold text-lg tracking-wide">Jelajahi Hunian</a>
                <a href="https://wa.me/6281332544545" class="glass text-white px-12 py-5 rounded-full font-bold text-lg border border-white/30 hover:bg-white/10 transition-all backdrop-blur-md">
                    Hubungi Marketing
                </a>
            </div>
        </div>
    </section>

    <!-- Financial Dashboard Section -->
    <section id="keuangan" class="py-24 bg-bg-light">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-primary mb-4">Transparansi Keuangan</h2>
                <div class="w-20 h-1 bg-accent mx-auto"></div>
                <p class="text-gray-500 mt-6 max-w-2xl mx-auto">Data real-time rekapitulasi keuangan perumahan yang dapat dipantau oleh seluruh warga.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Pemasukan -->
                <div class="card-premium p-8 rounded-3xl relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-green-50 rounded-bl-full -mr-8 -mt-8 transition-all group-hover:w-32 group-hover:h-32"></div>
                    <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center text-green-600 mb-6">
                        <i data-lucide="trending-up" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-gray-500 font-semibold mb-2">Total Pemasukan</h3>
                    <div class="text-3xl font-extrabold text-primary">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
                    <p class="text-xs text-green-600 mt-4 flex items-center gap-1 font-bold">
                        <i data-lucide="check-circle" class="w-3 h-3"></i> Terverifikasi Sistem
                    </p>
                </div>

                <!-- Pengeluaran -->
                <div class="card-premium p-8 rounded-3xl relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-red-50 rounded-bl-full -mr-8 -mt-8 transition-all group-hover:w-32 group-hover:h-32"></div>
                    <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center text-red-600 mb-6">
                        <i data-lucide="trending-down" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-gray-500 font-semibold mb-2">Total Pengeluaran</h3>
                    <div class="text-3xl font-extrabold text-primary">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
                    <p class="text-xs text-red-600 mt-4 flex items-center gap-1 font-bold">
                        <i data-lucide="info" class="w-3 h-3"></i> Sesuai Jurnal
                    </p>
                </div>

                <!-- Saldo Kas -->
                <div class="card-premium p-8 rounded-3xl relative overflow-hidden group border-accent/30 bg-primary shadow-2xl">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-accent/10 rounded-bl-full -mr-8 -mt-8 transition-all group-hover:w-32 group-hover:h-32"></div>
                    <div class="w-14 h-14 bg-accent rounded-2xl flex items-center justify-center text-primary mb-6">
                        <i data-lucide="wallet" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-text-dim font-semibold mb-2">Saldo Kas Saat Ini</h3>
                    <div class="text-3xl font-extrabold text-accent">Rp {{ number_format($totalKas, 0, ',', '.') }}</div>
                    <div class="h-1.5 bg-primary-light rounded-full mt-6 overflow-hidden">
                        <div class="h-full bg-accent" style="width: 70%"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang Perumahan Section -->
    <section id="tentang" class="py-24 bg-transparent">
        <div class="max-w-5xl mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-accent font-bold tracking-widest text-sm mb-4 block uppercase">Our Residence</span>
                <h2 class="text-4xl md:text-5xl font-bold text-primary mb-6">Tentang Puri Bunga 2</h2>
                <div class="w-20 h-1 bg-accent mx-auto"></div>
            </div>

            <!-- 1. Foto Perumahan -->
            <div class="mb-16">
                <div class="relative group overflow-hidden rounded-[2.5rem] shadow-2xl">
                    <img src="{{ asset('images/FotoPuriBunga2.png') }}" 
                         alt="Puri Bunga 2 Residence" 
                         class="w-full h-auto transform transition-transform duration-700 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-primary/40 to-transparent"></div>
                </div>
            </div>

            <!-- 2. Deskripsi -->
            <div class="text-center mb-20">
                <p class="text-gray-600 text-xl md:text-2xl leading-relaxed max-w-4xl mx-auto italic font-serif">
                    "Puri Bunga 2 adalah perumahan dengan lingkungan nyaman, aman, dan cocok untuk keluarga. Memiliki akses strategis, lingkungan tertata rapi, dan fasilitas sekitar yang lengkap."
                </p>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mt-16">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-16 h-16 bg-bg-light rounded-2xl flex items-center justify-center text-accent shadow-sm">
                            <i data-lucide="shield-check" class="w-8 h-8"></i>
                        </div>
                        <span class="font-bold text-primary">Security 24/7</span>
                    </div>
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-16 h-16 bg-bg-light rounded-2xl flex items-center justify-center text-accent shadow-sm">
                            <i data-lucide="map-pin" class="w-8 h-8"></i>
                        </div>
                        <span class="font-bold text-primary">Akses Strategis</span>
                    </div>
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-16 h-16 bg-bg-light rounded-2xl flex items-center justify-center text-accent shadow-sm">
                            <i data-lucide="leaf" class="w-8 h-8"></i>
                        </div>
                        <span class="font-bold text-primary">Lingkungan Asri</span>
                    </div>
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-16 h-16 bg-bg-light rounded-2xl flex items-center justify-center text-accent shadow-sm">
                            <i data-lucide="wifi" class="w-8 h-8"></i>
                        </div>
                        <span class="font-bold text-primary">Fiber Optic Ready</span>
                    </div>
                </div>
            </div>

            <!-- 3. Video -->
            <div class="relative rounded-[3rem] overflow-hidden shadow-2xl aspect-video bg-primary group">
                <video class="w-full h-full object-cover" controls poster="{{ asset('images/FotoPuriBunga2.png') }}">
                    <source src="{{ asset('videos/VideoPuriBunga2.mp4') }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <div class="absolute bottom-6 right-6 pointer-events-none">
                    <div class="bg-accent/90 backdrop-blur px-4 py-2 rounded-lg flex items-center gap-2">
                        <i data-lucide="play-circle" class="w-4 h-4 text-primary"></i>
                        <span class="text-[10px] font-bold text-primary uppercase tracking-widest">Official Video</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- News / Announcements Section -->
    <section id="pengumuman" class="py-24 bg-bg-light">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-primary mb-4">Papan Pengumuman</h2>
                <div class="w-20 h-1 bg-accent mx-auto"></div>
                <p class="text-gray-500 mt-6">Informasi terbaru dan berita seputar perumahan Puri Bunga 2.</p>
            </div>

            <div class="bulletin-board p-8 md:p-16 rounded-xl grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($pengumumans as $info)
                <div class="announcement-paper p-6 min-h-[250px] flex flex-col relative">
                    <div class="announcement-pin"></div>
                    <div class="text-xs font-bold text-accent mb-2 uppercase tracking-wider">{{ $info->created_at->format('d M Y') }}</div>
                    <h4 class="font-bold text-lg text-primary mb-4">{{ $info->judul }}</h4>
                    <p class="text-gray-600 text-sm line-clamp-6 leading-relaxed">
                        {{ $info->isi }}
                    </p>
                    <div class="mt-auto pt-4 flex justify-between items-center">
                        <span class="text-[10px] text-gray-400 font-mono">ID: PB2-{{ $info->id }}</span>
                        <i data-lucide="paperclip" class="w-4 h-4 text-gray-300"></i>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-20 text-gray-400">
                    <i data-lucide="info" class="w-12 h-12 mx-auto mb-4 opacity-20"></i>
                    <p>Belum ada pengumuman terbaru.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Location Section -->
    <section id="lokasi" class="py-24 bg-transparent">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-3 gap-12 items-center">
                <div class="lg:col-span-1">
                    <h2 class="text-4xl font-bold text-primary mb-6">Lokasi Strategis</h2>
                    <p class="text-gray-600 mb-8 leading-relaxed">
                        Terletak di kawasan yang berkembang pesat dengan akses mudah ke berbagai fasilitas umum seperti pusat perbelanjaan, sekolah, dan rumah sakit.
                    </p>
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div class="text-accent"><i data-lucide="map-pin"></i></div>
                            <div>
                                <h4 class="font-bold text-primary">Alamat</h4>
                                <p class="text-sm text-gray-500">Puri Bunga 2 Residence, Malang, Jawa Timur</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="text-accent"><i data-lucide="phone"></i></div>
                            <div>
                                <h4 class="font-bold text-primary">WhatsApp</h4>
                                <p class="text-sm text-gray-500">0813-3254-4545</p>
                            </div>
                        </div>
                    </div>
                    <a href="https://maps.app.goo.gl/Pm1YMoSoZZmvF6Fs8" target="_blank" class="btn-premium inline-flex items-center gap-2 px-8 py-4 rounded-full font-bold mt-10">
                        Buka di Google Maps <i data-lucide="external-link" class="w-4 h-4"></i>
                    </a>
                </div>
                <div class="lg:col-span-2 rounded-[2.5rem] overflow-hidden shadow-2xl border-8 border-white h-[500px]">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.48128913342!2d112.60819067586523!3d-7.930632478951834!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7882051732e58d%3A0x192ac54dcc2fce4d!2sPuri%20Bunga%20II!5e0!3m2!1sen!2sid!4v1715652000000!5m2!1sen!2sid" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-primary text-white py-20 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-4 gap-12 mb-16">
                <div class="col-span-2">
                    <a href="#" class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center">
                            <i data-lucide="home" class="text-primary w-6 h-6"></i>
                        </div>
                        <span class="text-white font-bold text-2xl">Puri Bunga <span class="text-accent">2</span></span>
                    </a>
                    <p class="text-text-dim max-w-sm leading-relaxed">
                        Hunian mewah dengan fasilitas lengkap dan keamanan 24 jam. Investasi terbaik untuk masa depan keluarga Anda di Malang.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-6">Quick Links</h4>
                    <ul class="space-y-4 text-text-dim">
                        <li><a href="#keuangan" class="hover:text-accent transition-colors">Laporan Keuangan</a></li>
                        <li><a href="#tentang" class="hover:text-accent transition-colors">Tentang Kami</a></li>
                        <li><a href="#pengumuman" class="hover:text-accent transition-colors">Pengumuman</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-accent transition-colors">Login Warga</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-6">Social Media</h4>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center hover:bg-accent hover:text-primary transition-all"><i data-lucide="instagram" class="w-5 h-5"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center hover:bg-accent hover:text-primary transition-all"><i data-lucide="facebook" class="w-5 h-5"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center hover:bg-accent hover:text-primary transition-all"><i data-lucide="youtube" class="w-5 h-5"></i></a>
                    </div>
                </div>
            </div>
            <div class="pt-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-text-dim text-sm text-center md:text-left">
                    &copy; {{ date('Y') }} Puri Bunga 2 Residence. All rights reserved. 
                    <span class="block md:inline mt-2 md:mt-0">Developed with Luxury in Mind.</span>
                </p>
                <div class="flex gap-8 text-sm text-text-dim">
                    <a href="#" class="hover:text-white">Privacy Policy</a>
                    <a href="#" class="hover:text-white">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();

        // Scroll reveal logic can be added here
        window.addEventListener('scroll', () => {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('py-2', 'bg-primary/95');
                nav.classList.remove('py-4', 'glass-dark');
            } else {
                nav.classList.remove('py-2', 'bg-primary/95');
                nav.classList.add('py-4', 'glass-dark');
            }
        });
    </script>
</body>
</html>
