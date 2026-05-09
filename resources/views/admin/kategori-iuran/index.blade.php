@extends('layouts.admin')
@section('title', 'Kategori Iuran')
@section('page-title', 'Kategori Iuran')
@section('content')
<div class="grid lg:grid-cols-3 gap-6">
    {{-- Form --}}
    <div class="card-stat p-6">
        <h3 class="font-bold text-slate-800 mb-4">Tambah Kategori</h3>
        <form method="POST" action="{{ route('admin.kategori-iuran.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Kategori</label>
                <input type="text" name="nama_kategori" value="{{ old('nama_kategori') }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Keamanan" required>
                @error('nama_kategori')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nominal Default (Rp)</label>
                <input type="number" name="nominal_default" value="{{ old('nominal_default', 0) }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" min="0" required>
            </div>
            <button type="submit" class="w-full bg-[#0f2557] hover:bg-[#1a3a8f] text-white py-2.5 rounded-xl text-sm font-semibold transition-colors">Tambah Kategori</button>
        </form>
    </div>
    {{-- Table --}}
    <div class="lg:col-span-2 card-stat overflow-hidden">
        <div class="p-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800">Daftar Kategori Iuran</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-left py-3 px-4 text-xs text-slate-500 font-semibold uppercase">Nama Kategori</th>
                    <th class="text-right py-3 px-4 text-xs text-slate-500 font-semibold uppercase">Nominal Default</th>
                    <th class="text-right py-3 px-4 text-xs text-slate-500 font-semibold uppercase">Jumlah Iuran</th>
                    <th class="text-right py-3 px-4 text-xs text-slate-500 font-semibold uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($kategoris as $kat)
                <tr class="hover:bg-slate-50">
                    <td class="py-3 px-4 font-semibold text-slate-700">{{ $kat->nama_kategori }}</td>
                    <td class="py-3 px-4 text-right text-slate-600">Rp {{ number_format($kat->nominal_default, 0, ',', '.') }}</td>
                    <td class="py-3 px-4 text-right"><span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full">{{ $kat->iurans_count }}</span></td>
                    <td class="py-3 px-4">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.kategori-iuran.edit', $kat) }}" class="text-amber-600 hover:text-amber-800 p-1.5 rounded-lg hover:bg-amber-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.kategori-iuran.destroy', $kat) }}" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 p-1.5 rounded-lg hover:bg-red-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-12 text-center text-slate-400">Belum ada kategori</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
