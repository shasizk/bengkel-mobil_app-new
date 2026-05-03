@extends('be.master')
@section('Vehicle')
<div class="container">
    <div class="page-inner">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-warning">
                        <h4 class="fw-bold mb-0 text-white">Edit Vehicle Data</h4>
                    </div>
                    <form action="{{ backend_route('admin.vehicles.update', [$vehicle->id]) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label>Owner</label>
                                    <select name="user_id" class="form-control" required>
                                        @foreach($users as $u)
                                            <option value="{{ $u->id }}" {{ $vehicle->user_id == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3"><label>Brand</label><input type="text" name="brand" class="form-control" value="{{ $vehicle->brand }}" required></div>
                                <div class="col-md-6 mb-3"><label>Model</label><input type="text" name="model" class="form-control" value="{{ $vehicle->model }}" required></div>
                                <div class="col-md-6 mb-3"><label>Year</label><input type="number" name="year" class="form-control" value="{{ $vehicle->year }}" required></div>
                                <div class="col-md-6 mb-3"><label>License Plate</label><input type="text" name="license_plate" class="form-control" value="{{ $vehicle->license_plate }}" required></div>
                                <div class="col-md-12 mb-3"><label>Color</label><input type="text" name="color" class="form-control" value="{{ $vehicle->color }}" required></div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end bg-white">
                            <a href="{{ backend_route('admin.vehicles.index') }}" class="btn btn-light me-2">Cancel</a>
                            <button type="submit" class="btn btn-warning btn-round text-white px-4">Update Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
