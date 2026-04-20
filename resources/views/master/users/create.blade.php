@extends('layouts.app')

@section('content')
<div class="glass-panel" style="padding: 2rem; max-width: 600px; margin: 0 auto;">
    <h2 style="margin-bottom: 1.5rem;">Tambah User Baru</h2>

    @if($errors->any())
        <div class="alert alert-danger" style="color: #ef4444; margin-bottom: 1rem;">
            <ul style="margin: 0; padding-left: 1.5rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/master/users">
        @csrf
        <div class="form-group" style="margin-bottom: 1rem;">
            <label>Nama Lengkap</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="form-group" style="margin-bottom: 1rem;">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="form-group" style="margin-bottom: 1rem;">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required minlength="4">
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label>Peran / Level</label>
            <select name="id_level" class="form-control" required>
                <option value="">Pilih Peran...</option>
                @foreach($levels as $level)
                    <option value="{{ $level->id }}" {{ old('id_level') == $level->id ? 'selected' : '' }}>
                        {{ $level->level_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">Simpan</button>
            <a href="/master/users" class="btn" style="flex: 1; text-align: center; background: rgba(255,255,255,0.1); color: var(--text);">Batal</a>
        </div>
    </form>
</div>
@endsection
