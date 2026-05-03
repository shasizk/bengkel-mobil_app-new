@extends('be.master')
@section('Service')
<div class="page-wrapper" style="padding-top: 80px;">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header text-white py-4 border-0" style="background: linear-gradient(135deg, #1e293b, #f59e0b);">
                        <p class="mb-2 text-uppercase" style="letter-spacing: .25em; color: rgba(255,255,255,.7); font-size: 12px;">Admin Menu</p>
                        <h4 class="fw-bold mb-0"><i class="fas fa-edit me-2"></i>Edit Layanan</h4>
                    </div>
                    <form action="{{ backend_route('admin.services.update', [$service->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body p-4 p-lg-5">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">Nama Layanan</label>
                                <input type="text" name="service_name" class="form-control" value="{{ $service->service_name }}" required style="min-height: 50px; border-radius: 16px;">
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">Deskripsi</label>
                                <textarea name="description" class="form-control" rows="4" style="border-radius: 18px;">{{ $service->description }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Harga (Rp)</label>
                                    <input type="number" name="price" class="form-control" value="{{ intval($service->price) }}" required style="min-height: 50px; border-radius: 16px;">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Estimasi Waktu (Menit)</label>
                                    <input type="number" name="estimated_time" class="form-control" value="{{ $service->estimated_time }}" required style="min-height: 50px; border-radius: 16px;">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white d-flex justify-content-end py-3">
                            <a href="{{ backend_route('admin.services.index') }}" class="btn btn-light me-2">Cancel</a>
                            <button type="submit" class="btn btn-warning">Update Service</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
