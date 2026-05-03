@extends('be.master')
@section('Sparepart')
<div class="container">
    <div class="page-inner">
        <div class="card border-0 shadow-sm col-md-8 mx-auto">
            <div class="card-header"><h4 class="fw-bold mb-0">Edit Sparepart</h4></div>
            <form action="{{ backend_route('admin.spareparts.update', [$sparepart->id]) }}" method="POST">
                @csrf @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3"><label>Name</label><input type="text" name="name" class="form-control" value="{{ $sparepart->name }}" required></div>
                        <div class="col-md-6 mb-3"><label>Brand</label><input type="text" name="brand" class="form-control" value="{{ $sparepart->brand }}" required></div>
                        <div class="col-md-6 mb-3"><label>Stock</label><input type="number" name="stock" class="form-control" value="{{ $sparepart->stock }}" required></div>
                        <div class="col-md-6 mb-3"><label>Price (Rp)</label><input type="number" name="price" class="form-control" value="{{ $sparepart->price }}" required></div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-round">Update Sparepart</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
