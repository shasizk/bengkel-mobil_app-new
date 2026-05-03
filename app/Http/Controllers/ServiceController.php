<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index() {
        $services = Service::all();
        $title = 'Service'; 
        return view('services.index', compact('services', 'title'));
    }
    
    public function create() {
        $title = 'Service'; 
        return view('services.create', compact('title'));
    }

    public function store(Request $request) {
        $request->validate([
            'service_name' => 'required',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'estimated_time' => 'required|integer'
        ]);

        try {
            Service::create($request->only(['service_name', 'description', 'price', 'estimated_time']));
            return redirect()->to(backend_route('admin.services.index'))->with('success', 'Service berhasil ditambah!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambah data: ' . $e->getMessage());
        }
    }

    public function edit($id) {
        try {
            $service = Service::findOrFail($id);
            $title = 'Service';
            return view('services.edit', compact('service', 'title'));
        } catch (\Exception $e) {
            return redirect()->to(backend_route('admin.services.index'))->with('error', 'Data tidak ditemukan!');
        }
    }

    public function update(Request $request, $id) {
        $request->validate([
            'service_name' => 'required',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'estimated_time' => 'required|integer'
        ]);

        try {
            $service = Service::findOrFail($id);
            $service->update($request->only(['service_name', 'description', 'price', 'estimated_time']));
            return redirect()->to(backend_route('admin.services.index'))->with('success', 'Service berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update data: ' . $e->getMessage());
        }
    }

    public function destroy($id) {
        try {
            $service = Service::findOrFail($id);
            $service->delete();
            return redirect()->to(backend_route('admin.services.index'))->with('success', 'Service berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
