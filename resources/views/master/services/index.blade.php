@extends('layouts.app')

@section('content')
<div class="glass-panel p-4" style="padding: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="margin: 0;">Layanan Laundry (Services)</h2>
        <a href="/master/services/create" class="btn btn-primary" style="margin: 0;">Tambah Service</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 1rem; color: #4ade80;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger" style="margin-bottom: 1rem; color: #ef4444;">{{ session('error') }}</div>
    @endif

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Nama Pelayanan</th>
                    <th>Harga/Kg</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $service)
                <tr>
                    <td><strong>{{ $service->service_name }}</strong></td>
                    <td>Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                    <td>{{ $service->description ?? '-' }}</td>
                    <td style="display:flex; gap:0.5rem;">
                        <a href="/master/services/{{ $service->id }}/edit" class="btn" style="background: rgba(255,255,255,0.1); color: var(--text);">Edit</a>
                        <form action="/master/services/{{ $service->id }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelayanan ini?');" style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn" style="background: rgba(239, 68, 68, 0.2); color: #ef4444;">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                @if($services->isEmpty())
                <tr>
                    <td colspan="4" style="text-align: center; color: var(--text-muted);">Belum ada pelayanan.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
