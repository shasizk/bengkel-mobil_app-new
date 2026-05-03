@extends('be.master')
@section('Vehicle')
<div class="container">
    <div class="page-inner">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-info text-white">
                <h4 class="fw-bold mb-0">Vehicle Detail #{{ $vehicle->id }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 border-end">
                        <label class="text-muted mb-0">Brand</label>
                        <p class="fw-bold text-dark fs-5">{{ $vehicle->brand }}</p>
                        
                        <label class="text-muted mb-0">Model</label>
                        <p class="fw-bold text-dark fs-5">{{ $vehicle->model }}</p>

                        <label class="text-muted mb-0">Year</label>
                        <p class="fw-bold text-dark fs-5">{{ $vehicle->year }}</p>
                    </div>
                    <div class="col-md-6 ps-md-4">
                        <div style="margin-bottom: 20px;">
                            <label class="text-muted d-block">License Plate</label>
                            <h3 class="fw-bold text-dark">NOMOR: {{ $vehicle->license_plate }}</h3>
                        </div>

                        <div class="mt-3">
                            <label class="text-muted mb-0">Color</label>
                            <p><span class="badge p-2 text-white" style="background-color: {{ $vehicle->color }}">{{ $vehicle->color }}</span></p>
                        </div>

                        <div class="mt-3">
                            <label class="text-muted mb-0">Owner</label>
                            <p class="fw-bold text-dark fs-5">{{ $vehicle->user->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end bg-light">
                <a href="{{ backend_route('admin.vehicles.index') }}" class="btn btn-secondary btn-round">Back to List</a>
            </div>
        </div>
    </div>
</div>
@endsection
