<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TypeOfService;

class ServiceController extends Controller
{
    public function index()
    {
        $services = TypeOfService::all();
        return view('master.services.index', compact('services'));
    }

    public function create()
    {
        return view('master.services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        TypeOfService::create([
            'service_name' => $request->service_name,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        return redirect('/master/services')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $service = TypeOfService::findOrFail($id);
        return view('master.services.edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $service = TypeOfService::findOrFail($id);

        $request->validate([
            'service_name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $service->update([
            'service_name' => $request->service_name,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        return redirect('/master/services')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $service = TypeOfService::findOrFail($id);
        $service->delete();
        return redirect('/master/services')->with('success', 'Layanan berhasil dihapus.');
    }
}
