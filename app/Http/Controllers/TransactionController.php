<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransOrder;
use App\Models\TransOrderDetail;
use App\Models\Customer;
use App\Models\TypeOfService;
use App\Models\Voucher;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = TransOrder::with('customer')->orderBy('id', 'desc')->get();
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $customers = Customer::all();
        $services = TypeOfService::all();
        return view('transactions.create', compact('customers', 'services'));
    }

    public function store(Request $request)
    {
        // 1. Hitung Subtotal Awal dari Layanan
        $subtotal_items = 0;
        $order_details = [];

        if ($request->has('services')) {
            foreach ($request->services as $service_id => $qty) {
                if ($qty > 0) {
                    $service = TypeOfService::findOrFail($service_id);
                    $item_subtotal = $service->price * $qty;

                    $subtotal_items += $item_subtotal;
                    $order_details[] = [
                        'id_service' => $service_id,
                        'qty' => $qty,
                        'subtotal' => $item_subtotal
                    ];
                }
            }
        }

        // 2. Hitung Pajak (10%) dari Subtotal
        $tax_rate = 0.10;
        $tax_amount = $subtotal_items * $tax_rate;
        $total_before_discount = $subtotal_items + $tax_amount;

        // 3. Logika Diskon Member & Voucher (Ketentuan Baru)
        $member_disc_rate = 0;
        $voucher_disc_rate = 0;

        // Cek Status Member (Ketentuan 1)
        if ($request->customer_type === 'new') {
            $customer = Customer::create([
                'customer_name' => $request->customer_name,
                'phone' => $request->phone,
                'address' => $request->address,
                'is_member' => $request->has('join_member') ? true : false
            ]);
            $request->merge(['id_customer' => $customer->id]);
        } else {
            $customer = Customer::findOrFail($request->id_customer);
        }

        if ($customer->is_member) {
            $member_disc_rate = 0.05; // 5% untuk member
        }

        // Cek Validitas Voucher (Ketentuan 2 & 4)
        $voucher = null;
        if ($request->voucher_code) {
            $voucher = Voucher::where('code', $request->voucher_code)
                ->where('is_used', false)
                ->where('expired_at', '>=', now())
                ->first();

            if ($voucher) {
                $voucher_disc_rate = 0.10; // 10% jika voucher valid
            }
        }

        // Total persentase diskon (Ketentuan 3: Jika member + voucher = 15%)
        $total_disc_rate = $member_disc_rate + $voucher_disc_rate;

        // Perhitungan nominal diskon dilakukan SETELAH pajak
        $total_discount_amount = $total_before_discount * $total_disc_rate;
        $final_total = $total_before_discount - $total_discount_amount;

        // 4. Simpan Transaksi Utama (TransOrder)
        $order = TransOrder::create([
            'id_customer' => $request->id_customer,
            'order_code' => 'ORD-' . time(),
            'order_date' => date('Y-m-d'),
            'total' => $final_total,
            'discount_amount' => $total_discount_amount,
            'tax_amount' => $tax_amount,
            'order_pay' => $request->order_pay,
            'order_change' => $request->order_pay - $final_total,
            'order_status' => 0
        ]);

        // 5. Simpan Detail Transaksi
        foreach ($order_details as $detail) {
            TransOrderDetail::create([
                'id_order' => $order->id,
                'id_service' => $detail['id_service'],
                'qty' => $detail['qty'],
                'subtotal' => $detail['subtotal']
            ]);
        }

        // 6. Update Status Voucher menjadi Terpakai (Ketentuan 4)
        if ($voucher) {
            $voucher->update(['is_used' => true]);
        }

        return redirect('/transactions')->with('success', 'Transaksi berhasil disimpan!');
    }
}
