<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::orderBy('created_at', 'desc')->get();
        return view('master.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        if (Auth::user()->id_level != 1) abort(403);
        return view('master.vouchers.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->id_level != 1) abort(403);
        $request->validate([
            'code' => 'required|unique:vouchers,code',
            'expired_at' => 'required|date'
        ]);

        Voucher::create([
            'code' => strtoupper($request->code),
            'expired_at' => $request->expired_at,
            'discount_percent' => 10,
            'is_used' => false
        ]);

        return redirect('/master/vouchers')->with('success', 'Voucher manual berhasil ditambahkan.');
    }

    public function generate(Request $request)
    {
        if (Auth::user()->id_level != 1) abort(403);
        $request->validate([
            'expired_at' => 'required|date'
        ]);

        $code = strtoupper(Str::random(8));
        Voucher::create([
            'code' => $code,
            'expired_at' => $request->expired_at,
            'discount_percent' => 10,
            'is_used' => false
        ]);

        return redirect('/master/vouchers')->with('success', "Voucher acak berhasil di-generate: $code");
    }

    public function check(Request $request)
    {
        $voucher = Voucher::where('code', $request->voucher_code)
                    ->where('is_used', false)
                    ->where('expired_at', '>=', now())
                    ->first();

        if ($voucher) {
            return response()->json(['valid' => true, 'discount_percent' => $voucher->discount_percent]);
        }
        return response()->json(['valid' => false]);
    }
}
