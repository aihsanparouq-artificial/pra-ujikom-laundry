@extends('layouts.app')

@section('content')
<div class="glass-panel" style="padding: 2rem; max-width: 600px; margin: 0 auto;">
    <h2 style="margin-bottom: 1.5rem;">Tambah Pelayanan Baru</h2>

    @if($errors->any())
        <div class="alert alert-danger" style="color: #ef4444; margin-bottom: 1rem;">
            <ul style="margin: 0; padding-left: 1.5rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/master/services">
        @csrf
        <div class="form-group" style="margin-bottom: 1rem;">
            <label>Nama Pelayanan</label>
            <input type="text" name="service_name" class="form-control" value="{{ old('service_name') }}" required>
        </div>

        <div class="form-group" style="margin-bottom: 1rem;">
            <label>Harga (Rp)</label>
            <input type="number" name="price" class="form-control" value="{{ old('price') }}" required min="0">
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label>Deskripsi Keterangan</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">Simpan</button>
            <a href="/master/services" class="btn" style="flex: 1; text-align: center; background: rgba(255,255,255,0.1); color: var(--text);">Batal</a>
        </div>
    </form>
</div>
@endsection
