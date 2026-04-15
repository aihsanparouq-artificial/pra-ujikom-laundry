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
        $order = TransOrder::create([
            'id_customer' => $request->id_customer,
            'order_code' => 'ORD-' . time(),
            'order_date' => date('Y-m-d'),
            'total' => $request->total,
            'order_pay' => $request->order_pay,
            'order_change' => $request->order_pay - $request->total,
            'order_status' => 0
        ]);
        
        if ($request->has('services')) {
            foreach($request->services as $service_id => $qty) {
                if($qty > 0) {
                    $service = TypeOfService::find($service_id);
                    TransOrderDetail::create([
                        'id_order' => $order->id,
                        'id_service' => $service_id,
                        'qty' => $qty,
                        'subtotal' => $service->price * $qty
                    ]);
                }
            }
        }
        
        return redirect('/transactions');
    }
}
