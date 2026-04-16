<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransOrder;
use App\Models\TransOrderDetail;
use App\Models\Customer;
use App\Models\TypeOfService;

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
        $total_items_price = 0;
        $order_details = [];

        if ($request->has('services')) {
            foreach ($request->services as $service_id => $qty) {
                if ($qty > 0) {
                    $service = TypeOfService::find($service_id);
                    $subtotal = $service->price * $qty;

                    $total_items_price += $subtotal;
                    $order_details[] = [
                        'id_service' => $service_id,
                        'qty' => $qty,
                        'subtotal' => $subtotal
                    ];
                    // TransOrderDetail::create([
                    //     'id_order' => $order->id,
                    //     'id_service' => $service_id,
                    //     'qty' => $qty,
                    //     'subtotal' => $service->price * $qty
                    // ]);
                }
            }
        }

        $discount = $request->discount ?? 0;
        $tax_rate = 0.10;

        $tax_amount = ($total_items_price - $discount) * $tax_rate;
        $final_total = ($total_items_price - $discount) + $tax_amount;

        $order = TransOrder::create([
            'id_customer' => $request->id_customer,
            'order_code' => 'ORD-' . time(),
            'order_date' => date('Y-m-d'),
            'total' => $final_total,
            'discount_amount' => $discount,
            'tax_amount' => $tax_amount,
            'order_pay' => $request->order_pay,
            'order_change' => $request->order_pay - $final_total,
            'order_status' => 0
        ]);

        foreach ($order_details as $detail) {
            TransOrderDetail::create([
                'id_order' => $order->id,
                'id_service' => $detail['id_service'],
                'qty' => $detail['qty'],
                'subtotal' => $detail['subtotal']
            ]);
        }

        return redirect('/transactions')->with('success', 'Transaksi berhasil disimpan!');
    }
}
