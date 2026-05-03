@extends('be.master')
@section('Sparepart')
<div class="container">
    <div class="page-inner">
        <div class="card border-0 shadow-sm col-md-8 mx-auto">
            <div class="card-header"><h4 class="fw-bold mb-0">New Sparepart</h4></div>
            <form action="{{ backend_route('admin.spareparts.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3"><label>Name</label><input type="text" name="name" class="form-control" required></div>
                        <div class="col-md-6 mb-3"><label>Brand</label><input type="text" name="brand" class="form-control" required></div>
                        <div class="col-md-6 mb-3"><label>Stock</label><input type="number" name="stock" class="form-control" required></div>
                        <div class="col-md-6 mb-3"><label>Price (Rp)</label><input type="number" name="price" class="form-control" required></div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <a href="{{ backend_route('admin.spareparts.index') }}" class="btn btn-light me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary btn-round">Save Part</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
