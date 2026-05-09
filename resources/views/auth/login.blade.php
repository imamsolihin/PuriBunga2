<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Puri Bunga 2</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.95); backdrop-blur: 10px; }
        .bg-pattern {
            background-color: #0f2557;
            background-image: radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%),
                              radial-gradient(at 50% 0%, hsla(225,39%,30%,1) 0, transparent 50%),
                              radial-gradient(at 100% 0%, hsla(339,49%,30%,1) 0, transparent 50%);
        }
        [x-cloak] { display: none !important; }
        @keyframes spinner { to { transform: rotate(360deg); } }
        .animate-spin-custom { animation: spinner 0.8s linear infinite; }
    </style>
</head>
<body class="bg-pattern min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md animate-in" x-data="{ show: false, loading: false, forgotModal: false }">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-white/10 backdrop-blur-md mb-6 shadow-2xl border border-white/10">
                <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
            </div>
            <h1 class="text-3xl font-black text-white tracking-tight">Puri Bunga 2</h1>
            <p class="text-blue-200/60 font-medium mt-2">Sistem Laporan Keuangan Perumahan</p>
        </div>

        <div class="glass p-8 rounded-[2rem] shadow-2xl border border-white/20">
            <form method="POST" action="{{ route('login') }}" @submit="loading = true">
                @csrf

                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl text-red-600 text-xs font-bold flex items-start gap-3">
                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-6">
                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 px-1">Email Address</label>
                        <div class="relative">
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                   class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-blue-500 placeholder:text-slate-300 transition-all"
                                   placeholder="nama@email.com">
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                            </div>
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex justify-between px-1 mb-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Password</label>
                            <button type="button" @click="forgotModal = true" class="text-xs font-bold text-blue-600 hover:underline focus:outline-none">Lupa?</button>
                        </div>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password" required
                                   class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-blue-500 placeholder:text-slate-300 transition-all"
                                   placeholder="••••••••">
                            <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-500 transition-colors">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Remember Me --}}
                    <label class="flex items-center gap-3 cursor-pointer group px-1">
                        <div class="relative w-5 h-5">
                            <input type="checkbox" name="remember" class="peer appearance-none w-5 h-5 bg-slate-100 rounded-md checked:bg-blue-600 transition-all cursor-pointer">
                            <svg class="absolute inset-0 w-5 h-5 text-white scale-0 peer-checked:scale-75 transition-transform pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="text-xs font-bold text-slate-500 group-hover:text-slate-800 transition-colors">Ingat Saya</span>
                    </label>

                    {{-- Submit --}}
                    <button type="submit"
                            class="w-full bg-[#0f2557] hover:bg-[#1a3a8f] text-white py-4 rounded-2xl font-bold tracking-wider shadow-lg shadow-blue-900/20 active:scale-[0.98] transition-all flex items-center justify-center gap-3 disabled:opacity-70 disabled:cursor-wait"
                            :disabled="loading">
                        <svg x-show="loading" x-cloak class="w-5 h-5 animate-spin-custom" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span x-text="loading ? 'MENGECEK...' : 'MASUK KE SISTEM'"></span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Lupa Password Modal --}}
        <div x-show="forgotModal" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="forgotModal = false"></div>
            <div class="relative w-full max-w-sm glass p-8 rounded-[2rem] shadow-2xl border border-white/20 animate-in">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-800">Lupa Password?</h3>
                    <p class="text-slate-500 text-sm mt-2">Silakan hubungi Pengurus Perumahan atau Admin untuk melakukan reset password akun Anda.</p>
                </div>
                <button @click="forgotModal = false" class="w-full bg-slate-800 text-white py-3 rounded-xl font-bold text-sm">OKE, MENGERTI</button>
            </div>
        </div>

        <div class="text-center mt-8">
            <p class="text-blue-200/40 text-[10px] font-bold uppercase tracking-[0.3em]">Official Management System v1.0</p>
        </div>
    </div>

</body>
</html>
