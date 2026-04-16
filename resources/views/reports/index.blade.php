@extends('layouts.app')

@section('content')
<div class="glass-panel p-4" style="padding: 2rem;">
    <div class="flex justify-between items-center" style="margin-bottom: 2rem;">
        <h2>Laporan Penjualan (Order Selesai / Diambil)</h2>
        <br></br>
        <button onclick="window.print()" class="btn btn-primary">Print Laporan</button>
    </div>

    @php
        $totalRev = $reports->sum('total');
    @endphp

    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid var(--success); padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
        <span style="font-size: 1.25rem; font-weight: 500; color: #34d399;">Total Pendapatan Selesai</span>
        <span style="font-size: 2rem; font-weight: 700; color: #10b981;">Rp. {{ number_format($totalRev, 0, ',', '.') }}</span>
    </div>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Kode Order</th>
                    <th>Tgl Selesai / Diambil</th>
                    <th>Pelanggan</th>
                    <th>Status</th>
                    <th style="text-align: right;">Total Nilai (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $r)
                <tr>
                    <td><strong>{{ $r->order_code }}</strong></td>
                    <td>{{ $r->order_end_date }}</td>
                    <td>{{ $r->customer->customer_name ?? '-' }}</td>
                    <td><span style="color: #34d399;">Diambil (Selesai)</span></td>
                    <td style="text-align: right; font-weight: 500;">{{ number_format($r->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
    @media print {
        header { display: none; }
        .btn { display: none; }
        body { background: #fff !important; color: #000; }
        .glass-panel { background: #fff !important; border: none !important; box-shadow: none !important; color: #000 !important; }
        table { border: 1px solid #ddd; }
        th, td { border: 1px solid #ccc; color: #000 !important; }
        .text-muted { color: #555 !important; }
        span { color: #000 !important; text-shadow: none !important; }
    }
</style>
@endsection
