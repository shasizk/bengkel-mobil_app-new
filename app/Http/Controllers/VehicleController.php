<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function __construct() {
        view()->share('title', 'Vehicle');
    }

    public function index() {
        $vehicles = Vehicle::with('user')->get(); // eager loading agar cepat
        return view('vehicles.index', compact('vehicles'));
    }

    public function create() {
        $users = User::all();
        return view('vehicles.create', compact('users'));
    }

    public function store(Request $request) {
        $request->validate([
            'user_id' => 'required',
            'brand' => 'required',
            'model' => 'required',
            'year' => 'required|numeric',
            'license_plate' => 'required|unique:vehicles',
            'color' => 'required'
        ]);

        try {
            Vehicle::create($request->all());
            return redirect()->to(backend_route('admin.vehicles.index'))->with('success', 'Vehicle registered successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show($id) {
        $vehicle = Vehicle::with('user')->findOrFail($id);
        return view('vehicles.show', compact('vehicle'));
    }

    public function edit($id) {
        $vehicle = Vehicle::findOrFail($id);
        $users = User::all();
        return view('vehicles.edit', compact('vehicle', 'users'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'user_id' => 'required',
            'brand' => 'required',
            'model' => 'required',
            'license_plate' => 'required|unique:vehicles,license_plate,'.$id
        ]);

        try {
            $vehicle = Vehicle::findOrFail($id);
            $vehicle->update($request->all());
            return redirect()->to(backend_route('admin.vehicles.index'))->with('success', 'Vehicle updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Update Failed!');
        }
    }

    public function destroy($id) {
        try {
            Vehicle::findOrFail($id)->delete();
            return redirect()->to(backend_route('admin.vehicles.index'))->with('success', 'Vehicle deleted!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Delete Failed!');
        }
    }
}
