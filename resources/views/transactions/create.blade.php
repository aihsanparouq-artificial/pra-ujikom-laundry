@extends('layouts.app')

@section('content')
<div class="glass-panel p-4" style="padding: 2rem;">
    <div class="flex justify-between items-center" style="margin-bottom: 2rem;">
        <h2>Buat Transaksi Baru</h2>
        <a href="/transactions" class="btn btn-primary" style="background: transparent; border: 1px solid var(--primary); box-shadow: none;">Batal</a>
    </div>

    <form method="POST" action="/transactions" id="transactionForm">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div>
                <div class="form-group">
                    <label>Pilih Pelanggan</label>
                    <div style="display: flex; gap: 1rem;">
                        <select name="id_customer" id="id_customer" class="form-control" required>
                            <option value="" data-is-member="0">-- Pilih --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" data-is-member="{{ $c->is_member }}">{{ $c->customer_name }} ({{ $c->phone }})</option>
                            @endforeach
                        </select>
                        <a href="/master/customers" class="btn btn-primary" style="padding: 0.75rem;">+</a>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <label>Jenis Layanan & Kuantitas</label>
                    <table style="width: 100%; border: none; background: rgba(0,0,0,0.2); border-radius: 8px;">
                        @foreach($services as $s)
                        <tr>
                            <td style="border: none; padding: 0.75rem;">
                                <strong>{{ $s->service_name }}</strong><br>
                                <small class="text-muted">Rp. {{ number_format($s->price,0,',','.') }}</small>
                                <input type="hidden" id="price_{{ $s->id }}" value="{{ $s->price }}">
                            </td>
                            <td style="border: none; width: 100px;">
                                <input type="number" name="services[{{ $s->id }}]" class="form-control qty-input" value="0" min="0" data-id="{{ $s->id }}">
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>

            <div class="glass-panel" style="padding: 1.5rem; background: rgba(15,23,42,0.5);">
                <h3 style="border-bottom: 1px solid var(--card-border); padding-bottom: 0.5rem;">Ringkasan</h3>

                <div class="form-group mt-3">
                    <label>Kode Voucher</label>
                    <div style="display: flex; gap: 0.5rem;">
                        <input type="text" name="voucher_code" id="voucher_code" class="form-control" placeholder="Masukkan kode...">
                        <button type="button" id="btn_apply_voucher" class="btn btn-secondary">Cek</button>
                    </div>
                    <small id="voucher_msg" style="display: block; margin-top: 5px;"></small>
                </div>

                <hr style="border-color: var(--card-border);">

                <div style="color: var(--text-muted); font-size: 0.9rem;">
                    <div class="flex justify-between"><span>Subtotal:</span> <span id="display_subtotal">Rp. 0</span></div>
                    <div class="flex justify-between"><span>Pajak (10%):</span> <span id="display_tax">Rp. 0</span></div>
                    <div class="flex justify-between" style="color: #4ade80;"><span>Diskon Member:</span> <span id="display_disc_member">0%</span></div>
                    <div class="flex justify-between" style="color: #4ade80;"><span>Diskon Voucher:</span> <span id="display_disc_voucher">0%</span></div>
                </div>

                <div class="form-group mt-4" style="display: flex; justify-content: space-between; font-size: 1.5rem;">
                    <span>Total:</span>
                    <strong style="color: var(--primary);" id="display_total">Rp. 0</strong>
                    <input type="hidden" name="total" id="input_total" value="0">
                </div>

                <div class="form-group mt-4">
                    <label>Uang Dibayar</label>
                    <input type="number" name="order_pay" id="order_pay" class="form-control" required style="font-size: 1.25rem;">
                </div>

                <div class="flex justify-between mt-2">
                    <span>Kembalian:</span>
                    <strong id="display_change">Rp. 0</strong>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-4" style="padding: 1rem;">Simpan Transaksi</button>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const qtyInputs = document.querySelectorAll('.qty-input');
        const customerSelect = document.getElementById('id_customer');
        const voucherInput = document.getElementById('voucher_code');
        const btnVoucher = document.getElementById('btn_apply_voucher');

        let isVoucherValid = false;
        const TAX_RATE = 0.10;

        function calculateTotal() {
            let subtotal = 0;
            qtyInputs.forEach(input => {
                const qty = parseInt(input.value) || 0;
                const price = parseFloat(document.getElementById('price_' + input.dataset.id).value);
                subtotal += qty * price;
            });

            // 1. Hitung Pajak dari Subtotal
            const taxAmount = subtotal * TAX_RATE;
            const finalBeforeDiscount = subtotal + taxAmount;

            // 2. Hitung Persentase Diskon (Ketentuan 1 & 2 & 3)
            let memberDiscRate = 0;
            let voucherDiscRate = 0;

            const isMember = customerSelect.options[customerSelect.selectedIndex].getAttribute('data-is-member') == "1";
            if (isMember) memberDiscRate = 0.05; // Member 5%

            if (isVoucherValid) voucherDiscRate = 0.10; // Voucher 10%

            const totalDiscRate = memberDiscRate + voucherDiscRate; // Jika keduanya, jadi 15%

            // 3. Hitung Nominal Diskon (Dihitung dari Total + Pajak)
            const discountAmount = finalBeforeDiscount * totalDiscRate;
            const grandFinalTotal = finalBeforeDiscount - discountAmount;

            // UI Update
            document.getElementById('display_subtotal').innerText = 'Rp. ' + subtotal.toLocaleString('id-ID');
            document.getElementById('display_tax').innerText = 'Rp. ' + taxAmount.toLocaleString('id-ID');
            document.getElementById('display_disc_member').innerText = (memberDiscRate * 100) + '%';
            document.getElementById('display_disc_voucher').innerText = (voucherDiscRate * 100) + '%';
            document.getElementById('display_total').innerText = 'Rp. ' + Math.round(grandFinalTotal).toLocaleString('id-ID');
            document.getElementById('input_total').value = Math.round(grandFinalTotal);

            calculateChange();
        }

        function calculateChange() {
            const total = parseInt(document.getElementById('input_total').value) || 0;
            const pay = parseInt(document.getElementById('order_pay').value) || 0;
            const change = pay - total;
            const el = document.getElementById('display_change');
            el.innerText = 'Rp. ' + (change >= 0 ? change.toLocaleString('id-ID') : 0);
            el.style.color = change < 0 ? 'var(--danger)' : 'var(--success)';
        }

        // Mocking Voucher Check (Di dunia nyata gunakan Fetch/AJAX ke Database)
        btnVoucher.addEventListener('click', () => {
            const code = voucherInput.value.toUpperCase();
            if(code === "PROMO10") { // Contoh kode statis untuk tes
                isVoucherValid = true;
                document.getElementById('voucher_msg').innerText = "Voucher Berhasil!";
                document.getElementById('voucher_msg').style.color = "green";
            } else {
                isVoucherValid = false;
                document.getElementById('voucher_msg').innerText = "Voucher Tidak Valid/Habis.";
                document.getElementById('voucher_msg').style.color = "red";
            }
            calculateTotal();
        });

        qtyInputs.forEach(i => i.addEventListener('input', calculateTotal));
        customerSelect.addEventListener('change', calculateTotal);
        document.getElementById('order_pay').addEventListener('input', calculateChange);
    });
</script>
@endsection
