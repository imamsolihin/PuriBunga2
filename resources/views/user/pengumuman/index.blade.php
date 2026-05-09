@extends('layouts.user')
@section('title', 'Pengumuman')
@section('page-title', 'Pusat Informasi')
@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="text-center">
        <h3 class="text-slate-800 font-black text-3xl tracking-tight">Kabar Terbaru</h3>
        <p class="text-slate-400 text-sm font-medium mt-2">Dapatkan informasi terkini mengenai lingkungan Puri Bunga 2</p>
    </div>

    <div class="space-y-6">
        @forelse($pengumumans as $p)
        <article class="card-stat p-8 hover:shadow-xl transition-all duration-300 group border border-slate-50">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                </div>
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">{{ $p->created_at ? $p->created_at->format('d F Y') : '-' }}</p>
                    <h4 class="text-slate-800 font-black text-xl group-hover:text-blue-600 transition-colors">{{ $p->judul }}</h4>
                </div>
            </div>
            <div class="prose prose-slate max-w-none">
                <p class="text-slate-600 text-base leading-relaxed whitespace-pre-line">
                    {{ $p->isi }}
                </p>
            </div>
            <div class="mt-8 pt-6 border-t border-slate-50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                    </div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Pengurus Perumahan</span>
                </div>
                <button class="text-blue-600 text-xs font-black uppercase tracking-widest hover:translate-x-1 transition-transform flex items-center gap-1">
                    Bagikan
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                </button>
            </div>
        </article>
        @empty
        <div class="card-stat p-20 text-center flex flex-col items-center">
            <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center text-slate-200 mb-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            </div>
            <h4 class="text-slate-800 font-bold text-lg">Belum Ada Pengumuman</h4>
            <p class="text-slate-400 text-sm mt-1">Kami akan mengabari Anda jika ada informasi terbaru.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
