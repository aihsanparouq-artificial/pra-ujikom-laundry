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
                    <label>Tipe Pelanggan</label>
                    <div style="display: flex; gap: 1.5rem; margin-top: 0.5rem; margin-bottom: 1.5rem; color: #cbd5e1;">
                        <label style="cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
                            <input type="radio" name="customer_type" value="member" checked onchange="toggleCustomerType()"> Customer Member
                        </label>
                        <label style="cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
                            <input type="radio" name="customer_type" value="new" onchange="toggleCustomerType()"> Customer non-Member
                        </label>
                    </div>
                </div>

                <!-- SELECT MEMBER -->
                <div id="section_member">
                    <div class="form-group">
                        <label>Pilih Pelanggan</label>
                        <select name="id_customer" id="id_customer" class="form-control">
                            <option value="" data-is-member="0">-- Pilih --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" data-is-member="{{ $c->is_member ? '1' : '0' }}">{{ $c->customer_name }} ({{ $c->phone }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- NEW CUSTOMER -->
                <div id="section_non_member" style="display: none;">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="customer_name" id="customer_name" class="form-control">
                    </div>
                    <div class="form-group mt-3">
                        <label>No Telepon</label>
                        <input type="text" name="phone" id="phone" class="form-control">
                    </div>
                    <div class="form-group mt-3">
                        <label>Alamat Lengkap</label>
                        <textarea name="address" id="address" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="form-group mt-3">
                        <label style="cursor: pointer; display:flex; align-items:center; gap: 0.5rem; font-weight: bold; color: var(--primary);">
                            <input type="checkbox" name="join_member" id="join_member" value="1" onchange="calculateTotal()"> Daftar sebagai Member
                        </label>
                        <small style="color: var(--text-muted); display: block; margin-top: 0.25rem;">Member otomatis mendapat diskon 5%.</small>
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
                    <div class="flex justify-between" style="color: #38bdf8;"><span>Diskon Voucher:</span> <span id="display_disc_voucher">0%</span></div>
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
    let isVoucherValid = false;
    const TAX_RATE = 0.10;
    let actualVoucherRate = 0;

    function toggleCustomerType() {
        const type = document.querySelector('input[name="customer_type"]:checked').value;
        const sMember = document.getElementById('section_member');
        const sNew = document.getElementById('section_non_member');
        
        if (type === 'member') {
            sMember.style.display = 'block';
            sNew.style.display = 'none';
            document.getElementById('id_customer').setAttribute('required', 'required');
            document.getElementById('customer_name').removeAttribute('required');
            document.getElementById('phone').removeAttribute('required');
        } else {
            sMember.style.display = 'none';
            sNew.style.display = 'block';
            document.getElementById('id_customer').removeAttribute('required');
            document.getElementById('customer_name').setAttribute('required', 'required');
            document.getElementById('phone').setAttribute('required', 'required');
        }
        calculateTotal();
    }

    function calculateTotal() {
        let subtotal = 0;
        document.querySelectorAll('.qty-input').forEach(input => {
            const qty = parseInt(input.value) || 0;
            const price = parseFloat(document.getElementById('price_' + input.dataset.id).value);
            subtotal += qty * price;
        });

        const taxAmount = subtotal * TAX_RATE;
        const finalBeforeDiscount = subtotal + taxAmount;

        let memberDiscRate = 0;
        let voucherDiscRate = 0;

        // Check if member discount applies
        const type = document.querySelector('input[name="customer_type"]:checked').value;
        if (type === 'member') {
            const cs = document.getElementById('id_customer');
            if (cs.selectedIndex > 0) {
                const isMember = cs.options[cs.selectedIndex].getAttribute('data-is-member') == "1";
                if (isMember) memberDiscRate = 0.05;
            }
        } else {
            if (document.getElementById('join_member').checked) {
                memberDiscRate = 0.05;
            }
        }

        if (isVoucherValid) voucherDiscRate = actualVoucherRate;

        const totalDiscRate = memberDiscRate + voucherDiscRate;
        const discountAmount = finalBeforeDiscount * totalDiscRate;
        const grandFinalTotal = finalBeforeDiscount - discountAmount;

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

    document.getElementById('btn_apply_voucher').addEventListener('click', () => {
        const code = document.getElementById('voucher_code').value.trim();
        const msg = document.getElementById('voucher_msg');
        
        if (!code) {
            msg.innerText = "Masukkan kode voucher!";
            msg.style.color = "red";
            return;
        }

        fetch('{{ route("vouchers.check") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ voucher_code: code })
        })
        .then(res => res.json())
        .then(data => {
            if (data.valid) {
                isVoucherValid = true;
                actualVoucherRate = data.discount_percent / 100;
                msg.innerText = "Voucher Berhasil (" + data.discount_percent + "%)!";
                msg.style.color = "green";
            } else {
                isVoucherValid = false;
                actualVoucherRate = 0;
                msg.innerText = "Voucher Tidak Valid / Expired.";
                msg.style.color = "red";
            }
            calculateTotal();
        });
    });

    document.querySelectorAll('.qty-input').forEach(i => i.addEventListener('input', calculateTotal));
    document.getElementById('id_customer').addEventListener('change', calculateTotal);
    document.getElementById('order_pay').addEventListener('input', calculateChange);

    // Initial check
    toggleCustomerType();
</script>
@endsection
