<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransOrder;
use App\Models\TransLaundryPickup;

class PickupController extends Controller
{
    public function index()
    {
        $pickups = TransLaundryPickup::with('order', 'customer')->orderBy('id', 'desc')->get();
        return view('pickups.index', compact('pickups'));
    }

    public function create()
    {
        $orders = TransOrder::where('order_status', 0)->with('customer')->get();
        return view('pickups.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $order = TransOrder::find($request->id_order);
        if ($order) {
            $order->update(['order_status' => 1, 'order_end_date' => date('Y-m-d')]);

            TransLaundryPickup::create([
                'id_order' => $order->id,
                'id_customer' => $order->id_customer,
                'pickup_date' => date('Y-m-d'),
                'notes' => $request->notes
            ]);
        }
        return redirect('/pickups');
    }
}
