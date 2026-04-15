<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransOrder;

class ReportController extends Controller
{
    public function index()
    {
        $reports = TransOrder::where('order_status', 1)->with('customer')->orderBy('order_end_date', 'desc')->get();
        return view('reports.index', compact('reports'));
    }
}
