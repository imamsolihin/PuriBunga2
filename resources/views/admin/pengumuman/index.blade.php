@extends('layouts.admin')
@section('title', 'Pengumuman')
@section('page-title', 'Manajemen Pengumuman')
@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <p class="text-slate-500 text-sm">Informasi terbaru untuk warga</p>
        <a href="{{ route('admin.pengumuman.create') }}" class="inline-flex items-center gap-2 bg-[#0f2557] text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-[#1a3a8f] transition-colors shadow">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Buat Pengumuman
        </a>
    </div>

    <div class="grid gap-4">
        @forelse($pengumumans as $p)
        <div class="card-stat p-5 hover:border-blue-200 transition-all border border-transparent">
            <div class="flex flex-col md:flex-row justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                            {{ $p->status === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ $p->status }}
                        </span>
                        <span class="text-xs text-slate-400 font-medium">{{ $p->created_at->format('d M Y • H:i') }}</span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-2">{{ $p->judul }}</h3>
                    <p class="text-slate-600 text-sm line-clamp-2 leading-relaxed">
                        {{ $p->isi }}
                    </p>
                </div>
                <div class="flex items-center gap-2 self-end md:self-center">
                    <a href="{{ route('admin.pengumuman.edit', $p) }}" class="p-2 text-amber-600 hover:bg-amber-50 rounded-xl transition-colors" title="Edit">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>
                    <form method="POST" action="{{ route('admin.pengumuman.destroy', $p) }}" onsubmit="return confirm('Hapus pengumuman ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-xl transition-colors" title="Hapus">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="card-stat p-12 text-center text-slate-400">
            <svg class="w-16 h-16 mx-auto mb-4 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
            Belum ada pengumuman yang dibuat
        </div>
        @endforelse

        @if($pengumumans->hasPages())
        <div class="mt-4">{{ $pengumumans->links() }}</div>
        @endif
    </div>
</div>
@endsection
