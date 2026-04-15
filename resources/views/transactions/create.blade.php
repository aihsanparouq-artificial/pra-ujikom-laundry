@extends('layouts.app')

@section('content')
<div class="glass-panel p-4" style="padding: 2rem;">
    <div class="flex justify-between items-center" style="margin-bottom: 2rem;">
        <h2>Buat Transaksi Baru</h2>
        <a href="/transactions" class="btn btn-primary" style="background: transparent; border: 1px solid var(--primary); box-shadow: none;">Batal</a>
    </div>

    <form method="POST" action="/transactions">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <!-- Left Info -->
            <div>
                <div class="form-group">
                    <label>Pilih Pelanggan</label>
                    <div style="display: flex; gap: 1rem;">
                        <select name="id_customer" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->customer_name }} ({{ $c->phone }})</option>
                            @endforeach
                        </select>
                        <a href="/master/customers" class="btn btn-primary" style="padding: 0.75rem;">+</a>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <label>Jenis Layanan & Kuantitas (Kg/Pcs)</label>
                    <table style="width: 100%; border: none; margin-top: 0.5rem; background: rgba(0,0,0,0.2); border-radius: 8px;">
                        @foreach($services as $s)
                        <tr>
                            <td style="border: none; padding: 0.75rem;">
                                <strong>{{ $s->service_name }}</strong><br>
                                <small class="text-muted">Rp. {{ number_format($s->price,0,',','.') }} / unit</small>
                                <input type="hidden" id="price_{{ $s->id }}" value="{{ $s->price }}">
                            </td>
                            <td style="border: none; width: 100px;">
                                <input type="number" name="services[{{ $s->id }}]" id="qty_{{ $s->id }}" class="form-control qty-input" value="0" min="0" data-id="{{ $s->id }}">
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>

            <!-- Right Info / Payment -->
            <div class="glass-panel" style="padding: 1.5rem; background: rgba(15,23,42,0.5);">
                <h3 style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--card-border); padding-bottom: 0.5rem;">Ringkasan Pembayaran</h3>
                
                <div class="form-group" style="display: flex; justify-content: space-between; font-size: 1.25rem;">
                    <span>Total Tagihan:</span>
                    <strong style="color: var(--primary);" id="display_total">Rp. 0</strong>
                    <input type="hidden" name="total" id="input_total" value="0">
                </div>

                <div class="form-group mt-4">
                    <label>Uang Dibayar (Rp)</label>
                    <input type="number" name="order_pay" id="order_pay" class="form-control" required min="0" style="font-size: 1.25rem;">
                </div>

                <div class="form-group" style="display: flex; justify-content: space-between; font-size: 1.1rem; color: var(--text-muted);">
                    <span>Kembalian:</span>
                    <strong id="display_change">Rp. 0</strong>
                </div>

                <button type="submit" class="btn btn-primary w-100" style="margin-top: 1rem; font-size: 1.1rem; padding: 1rem;">Simpan Transaksi</button>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const qtyInputs = document.querySelectorAll('.qty-input');
        const displayTotal = document.getElementById('display_total');
        const inputTotal = document.getElementById('input_total');
        const orderPay = document.getElementById('order_pay');
        const displayChange = document.getElementById('display_change');

        function calculateTotal() {
            let total = 0;
            qtyInputs.forEach(input => {
                const qty = parseInt(input.value) || 0;
                const id = input.getAttribute('data-id');
                const price = parseInt(document.getElementById('price_' + id).value) || 0;
                total += qty * price;
            });
            inputTotal.value = total;
            displayTotal.innerText = 'Rp. ' + total.toLocaleString('id-ID');
            calculateChange();
        }

        function calculateChange() {
            const total = parseInt(inputTotal.value) || 0;
            const pay = parseInt(orderPay.value) || 0;
            let change = pay - total;
            displayChange.innerText = 'Rp. ' + (change >= 0 ? change.toLocaleString('id-ID') : 0);
            displayChange.style.color = change < 0 ? 'var(--danger)' : 'var(--success)';
        }

        qtyInputs.forEach(input => {
            input.addEventListener('input', calculateTotal);
        });

        orderPay.addEventListener('input', calculateChange);
    });
</script>
@endsection
