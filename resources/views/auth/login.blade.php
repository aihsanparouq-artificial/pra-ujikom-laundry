@extends('layouts.app')

@section('content')
<div class="auth-wrapper">
    <div class="glass-panel auth-card">
        <h2 class="auth-title">Asal Muasal Laundry</h2>
        <p class="text-center text-muted" style="color: #94a3b8; margin-top: -1rem; margin-bottom: 2rem;">Silakan Login ke Akun Anda</p>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-4">Login</button>
            <div class="mt-3 text-center">
                Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
            </div>
        </form>
    </div>
</div>
@endsection
