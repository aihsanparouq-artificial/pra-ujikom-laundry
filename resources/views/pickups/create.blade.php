@extends('layouts.app')

@section('content')
<div class="glass-panel p-4" style="padding: 2rem; max-width: 600px; margin: 0 auto;">
    <h2 style="margin-bottom: 2rem; text-align: center;">Proses Pengambilan Cucian</h2>

    <form method="POST" action="/pickups">
        @csrf
        <div class="form-group">
            <label>Pilih Transaksi (Order)</label>
            <select name="id_order" class="form-control" required>
                <option value="">-- Pilih Transaksi --</option>
                @foreach($orders as $o)
                    <option value="{{ $o->id }}">{{ $o->order_code }} - {{ $o->customer->customer_name ?? '' }} (Rp {{ number_format($o->total,0,',','.') }})</option>
                @endforeach
            </select>
            <small class="text-muted" style="margin-top: 0.5rem; display: block;">Hanya menampilkan order yang berstatus "Baru".</small>
        </div>

        <div class="form-group" style="margin-top: 1.5rem;">
            <label>Catatan Pengambilan</label>
            <textarea name="notes" class="form-control" rows="3" placeholder="Opsional..."></textarea>
        </div>

        <button type="submit" class="btn btn-primary w-100 mt-4" style="padding: 1rem;">Selesaikan Pengambilan</button>
    </form>
</div>
@endsection
