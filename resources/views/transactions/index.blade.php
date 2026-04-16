@extends('layouts.app')

@section('content')
<div class="glass-panel p-4" style="padding: 2rem;">
    <div class="flex justify-between items-center" style="margin-bottom: 2rem;">
        <h2>Daftar Transaksi (Order)</h2>
        <br></br>
        <a href="/transactions/create" class="btn btn-primary">Order Baru</a>
    </div>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Kode Order</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Total Pembayaran</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $t)
                <tr>
                    <td><strong>{{ $t->order_code }}</strong></td>
                    <td>{{ $t->order_date }}</td>
                    <td>{{ $t->customer->customer_name ?? 'N/A' }}</td>
                    <td>Rp. {{ number_format($t->total, 0, ',', '.') }}</td>
                    <td>
                        @if($t->order_status == 0)
                            <span style="background: rgba(245, 158, 11, 0.2); color: #fbbf24; padding: 0.25rem 0.75rem; border-radius: 99px; font-size: 0.85rem;">Baru</span>
                        @else
                            <span style="background: rgba(16, 185, 129, 0.2); color: #34d399; padding: 0.25rem 0.75rem; border-radius: 99px; font-size: 0.85rem;">Diambil</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
