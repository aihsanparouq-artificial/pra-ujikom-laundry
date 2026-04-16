@extends('layouts.app')

@section('content')
<div class="auth-wrapper">
    <div class="glass-panel auth-card">
        <h2 class="auth-title">Asal Muasal Laundry</h2>
        <p class="text-center text-muted" style="color: #94a3b8; margin-top: -1rem; margin-bottom: 2rem;">Silakan Mendaftar</p>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="id_level">Daftar Sebagai / Role</label>
                <select id="id_level" name="id_level" class="form-control" required style="width: 100%; padding: 0.5rem; border-radius: 0.375rem; border: 1px solid #d1d5db;">
                    <option value="">-- Pilih Role --</option>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}" {{ old('id_level') == $level->id ? 'selected' : '' }}>
                            {{ $level->level_name }}
                        </option>
                    @endforeach
                </select>
                @error('id_level')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-4">Register</button>
            <div class="mt-3 text-center">
                Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
            </div>
        </form>
    </div>
</div>
@endsection
