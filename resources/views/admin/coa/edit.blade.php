@extends('layouts.admin')
@section('title', 'Edit COA')
@section('page-title', 'Edit Akun COA')
@section('content')
<div class="max-w-md">
    <div class="card-stat p-6">
        <form method="POST" action="{{ route('admin.coa.update', $coa) }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kode Akun</label>
                <input type="text" name="kode_akun" value="{{ old('kode_akun', $coa->kode_akun) }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('kode_akun')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Akun</label>
                <input type="text" name="nama_akun" value="{{ old('nama_akun', $coa->nama_akun) }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tipe</label>
                <select name="tipe" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @foreach(['aset','kewajiban','ekuitas','pendapatan','beban'] as $t)
                    <option value="{{ $t }}" {{ $coa->tipe === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-[#0f2557] hover:bg-[#1a3a8f] text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors">Update COA</button>
                <a href="{{ route('admin.coa.index') }}" class="text-slate-500 hover:text-slate-700 px-4 py-2.5 rounded-xl text-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
