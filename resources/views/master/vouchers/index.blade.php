@extends('layouts.app')

@section('content')
<div class="glass-panel p-4" style="padding: 2rem;">
    <div class="flex justify-between items-center" style="margin-bottom: 2rem;">
        <h2>Daftar Voucher</h2>
        @if(Auth::user()->id_level == 1)
            <a href="/master/vouchers/create" class="btn btn-primary">Tambah Voucher</a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 1rem; color: #4ade80;">{{ session('success') }}</div>
    @endif

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Kode Voucher</th>
                    <th>Diskon</th>
                    <th>Kadaluarsa</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vouchers as $v)
                <tr>
                    <td><strong>{{ $v->code }}</strong></td>
                    <td>{{ $v->discount_percent }}%</td>
                    <td>{{ \Carbon\Carbon::parse($v->expired_at)->format('d M Y') }}</td>
                    <td>
                        @if($v->is_used)
                            <span style="color: var(--danger);">Terpakai</span>
                        @elseif(\Carbon\Carbon::parse($v->expired_at)->isPast())
                            <span style="color: var(--danger);">Kadaluarsa</span>
                        @else
                            <span style="color: var(--success);">Berlaku</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
