@extends('be.master')
@section('Vehicle')
<div class="container">
    <div class="page-inner">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header"><h4 class="fw-bold mb-0" style="color: #1a2035;">Register Vehicle</h4></div>
                    <form action="{{ backend_route('admin.vehicles.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label>Owner (User ID)</label>
                                    <select name="user_id" class="form-control" required>
                                        <option value="">-- Select Owner --</option>
                                        @foreach($users as $u)
                                            <option value="{{ $u->id }}">{{ $u->name }} (ID: {{ $u->id }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3"><label>Brand</label><input type="text" name="brand" class="form-control" placeholder="Honda" required></div>
                                <div class="col-md-6 mb-3"><label>Model</label><input type="text" name="model" class="form-control" placeholder="PCX" required></div>
                                <div class="col-md-6 mb-3"><label>Year</label><input type="number" name="year" class="form-control" placeholder="2023" required></div>
                                <div class="col-md-6 mb-3"><label>License Plate</label><input type="text" name="license_plate" class="form-control" placeholder="76986" required></div>
                                <div class="col-md-12 mb-3"><label>Color</label><input type="text" name="color" class="form-control" placeholder="Red" required></div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <a href="{{ backend_route('admin.vehicles.index') }}" class="btn btn-light me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-round">Save Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
