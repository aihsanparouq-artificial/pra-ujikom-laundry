@extends('layouts.app')

@section('content')
<div class="glass-panel p-4" style="padding: 2rem;">
    <h2 style="margin-bottom: 2rem;">Master Data Pelanggan</h2>

    <div style="display: grid; grid-template-columns: 1fr 350px; gap: 2rem;">
        <!-- Tabe List -->
        <div style="overflow-x: auto;">
            <table>
                <thead>
                        <th>Nama Pelanggan</th>
                        <th>No Telepon</th>
                        <th>Alamat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $c)
                    <tr>
                        <td><strong>{{ $c->customer_name }}</strong></td>
                        <td>{{ $c->phone }}</td>
                        <td>{{ $c->address }}</td>
                        <td>
                            @if($c->is_member)
                                <span style="color: var(--primary); font-weight: bold;">Member</span>
                            @else
                                <span style="color: var(--text-muted);">Non-Member</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @if($customers->isEmpty())
                        <tr><td colspan="4" style="text-align: center; color: var(--text-muted);">Belum ada pelanggan.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Form Adds -->
        <div class="glass-panel" style="padding: 1.5rem; background: rgba(0,0,0,0.2); height: max-content;">
            <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Tambah Pelanggan Baru</h3>
            <form method="POST" action="/master/customers">
                @csrf
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="customer_name" class="form-control" required>
                </div>
                <div class="form-group mt-4">
                    <label>No Telepon</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>
                <div class="form-group mt-4">
                    <label>Alamat Lengkap</label>
                    <textarea name="address" class="form-control" rows="3" required></textarea>
                </div>
                <div class="form-group mt-3">
                    <label style="cursor: pointer; display:flex; align-items:center; gap: 0.5rem; font-weight: bold; color: var(--primary);">
                        <input type="checkbox" name="join_member" value="1"> Daftar sebagai Member
                    </label>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-4">Simpan Pelanggan</button>
            </form>
        </div>
    </div>
</div>
@endsection
