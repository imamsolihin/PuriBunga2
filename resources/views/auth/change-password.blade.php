@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.user')

@section('title', 'Ganti Password')
@section('page-title', 'Ganti Password')

@section('content')
<div class="max-w-md mx-auto">
    <div class="card-stat p-6">
        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf
            @method('put')

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Password Saat Ini (Password Awal) <span class="text-red-500">*</span></label>
                <input type="password" name="current_password" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @if($errors->updatePassword->has('current_password'))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->updatePassword->first('current_password') }}</p>
                @endif
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Password Baru <span class="text-red-500">*</span></label>
                <input type="password" name="password" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @if($errors->updatePassword->has('password'))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->updatePassword->first('password') }}</p>
                @endif
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi Password Baru <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @if($errors->updatePassword->has('password_confirmation'))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                @endif
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-[#0f2557] text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-[#1a3a8f] transition-all shadow">
                    Simpan Password
                </button>
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}" class="text-slate-500 hover:text-slate-700 px-4 py-2.5 rounded-xl text-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
