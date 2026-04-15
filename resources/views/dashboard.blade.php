@extends('layouts.app')

@section('content')
<div class="glass-panel p-4" style="padding: 2rem;">
    <h2>Selamat Datang, {{ Auth::user()->name }}!</h2>
    <p class="mt-4 text-muted" style="color: #94a3b8;">
        Anda login sebagai 
        @if(Auth::user()->id_level == 1) Super Admin
        @elseif(Auth::user()->id_level == 2) Operator
        @else Pimpinan
        @endif.
    </p>

    <div class="mt-4" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
        
        @if(in_array(Auth::user()->id_level, [1, 2]))
        <div class="glass-panel" style="padding: 1.5rem;">
            <h3 style="margin-bottom: 0.5rem; color: #818cf8;">Transaksi Baru</h3>
            <p style="font-size: 0.9rem; margin-bottom: 1.5rem; color: #94a3b8;">Buat order laundry baru untuk pelanggan.</p>
            <a href="/transactions/create" class="btn btn-primary w-100">Buat Transaksi</a>
        </div>

        <div class="glass-panel" style="padding: 1.5rem;">
            <h3 style="margin-bottom: 0.5rem; color: #c084fc;">Ambil Laundry</h3>
            <p style="font-size: 0.9rem; margin-bottom: 1.5rem; color: #94a3b8;">Proses pengambilan baju yang sudah selesai.</p>
            <a href="/pickups/create" class="btn btn-primary w-100">Ambil Loundry</a>
        </div>
        @endif
        
        @if(in_array(Auth::user()->id_level, [1, 3]))
        <div class="glass-panel" style="padding: 1.5rem;">
            <h3 style="margin-bottom: 0.5rem; color: #34d399;">Laporan Keuangan</h3>
            <p style="font-size: 0.9rem; margin-bottom: 1.5rem; color: #94a3b8;">Lihat ringkasan pendapatan laundry.</p>
            <a href="/reports" class="btn btn-primary w-100">Lihat Laporan</a>
        </div>
        @endif
    </div>
</div>
@endsection
