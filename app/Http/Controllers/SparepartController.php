<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use Illuminate\Http\Request;

class SparepartController extends Controller
{
    public function __construct() {
        view()->share('title', 'Sparepart');
    }

    public function index() {
        $spareparts = Sparepart::all();
        return view('spareparts.index', compact('spareparts'));
    }

    public function create() {
        return view('spareparts.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name'  => 'required',
            'brand' => 'required',
            'stock' => 'required|numeric',
            'price' => 'required|numeric'
        ]);

        try {
            Sparepart::create($request->all());
            return redirect()->to(backend_route('admin.spareparts.index'))->with('success', 'Sparepart added!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal simpan!');
        }
    }

    public function show($id) {
        $sparepart = Sparepart::findOrFail($id);
        return view('spareparts.show', compact('sparepart'));
    }

    public function edit($id) {
        $sparepart = Sparepart::findOrFail($id);
        return view('spareparts.edit', compact('sparepart'));
    }

    public function update(Request $request, $id) {
        $sparepart = Sparepart::findOrFail($id);
        $sparepart->update($request->all());
        return redirect()->to(backend_route('admin.spareparts.index'))->with('success', 'Sparepart updated!');
    }

    public function destroy($id) {
        Sparepart::findOrFail($id)->delete();
        return redirect()->to(backend_route('admin.spareparts.index'))->with('success', 'Sparepart deleted!');
    }
}
