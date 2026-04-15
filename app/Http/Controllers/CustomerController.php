<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('id', 'desc')->get();
        return view('master.customers.index', compact('customers'));
    }

    public function store(Request $request)
    {
        Customer::create($request->only('customer_name', 'phone', 'address'));
        return back();
    }
}
