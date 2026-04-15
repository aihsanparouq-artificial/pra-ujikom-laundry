@extends('layouts.app')

@section('content')
<div class="auth-wrapper">
    <div class="glass-panel auth-card">
        <h2 class="auth-title">Welcome Back</h2>
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
        </form>
    </div>
</div>
@endsection
