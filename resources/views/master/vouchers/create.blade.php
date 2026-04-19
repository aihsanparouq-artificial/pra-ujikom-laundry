@extends('layouts.app')

@section('content')
<div class="glass-panel p-4" style="padding: 2rem; max-width: 600px; margin: 0 auto;">
    <h2>Tambah Voucher Baru</h2>

    @if($errors->any())
        <div class="alert alert-danger" style="color: var(--danger); margin-bottom: 1rem;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/master/vouchers">
        @csrf
        <div class="form-group">
            <label>Kode Voucher (Manual)</label>
            <input type="text" name="code" class="form-control" placeholder="Contoh: AKHIRTAHUN" style="text-transform: uppercase;">
            <small class="text-muted" style="display:block; margin-top:0.25rem;">Isi jika ingin mengatur kode spesifik.</small>
        </div>

        <div class="form-group mt-3">
            <label>Tanggal Kadaluarsa</label>
            <input type="date" name="expired_at" id="expired_at" class="form-control" required>
        </div>

        <div class="flex items-center gap-2 mt-4" style="gap: 1rem;">
            <button type="submit" class="btn btn-primary">Simpan Kode Manual</button>
            <button type="button" class="btn btn-secondary" onclick="generateRandom()">Generate Kode Acak</button>
        </div>
    </form>
    
    <form id="generateForm" method="POST" action="{{ route('vouchers.generate') }}" style="display: none;">
        @csrf
        <input type="hidden" name="expired_at" id="generate_expired_at">
    </form>
</div>

<script>
    function generateRandom() {
        const expiredDate = document.getElementById('expired_at').value;
        if (!expiredDate) {
            alert('Silakan isi Tanggal Kadaluarsa terlebih dahulu sebelum generate otomatis.');
            return;
        }
        document.getElementById('generate_expired_at').value = expiredDate;
        document.getElementById('generateForm').submit();
    }
</script>
@endsection
