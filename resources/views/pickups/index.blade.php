@extends('layouts.app')

@section('content')
<div class="glass-panel p-4" style="padding: 2rem;">
    <div class="flex justify-between items-center" style="margin-bottom: 2rem;">
        <h2>Riwayat Pengambilan Laundry</h2>
        <br></br>
        <a href="/pickups/create" class="btn btn-primary">Input Pengambilan</a>
    </div>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Tanggal Pickup</th>
                    <th>Kode Order</th>
                    <th>Pelanggan</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pickups as $p)
                <tr>
                    <td>{{ $p->pickup_date }}</td>
                    <td><strong>{{ $p->order->order_code ?? '-' }}</strong></td>
                    <td>
                        {{ $p->customer->customer_name ?? '-' }}
                        <br>
                        <small style="color: var(--primary);">
                            @if($p->customer && $p->customer->is_member)
                                (Member)
                            @else
                                (Non-Member)
                            @endif
                        </small>
                    </td>
                    <td>{{ $p->notes ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
