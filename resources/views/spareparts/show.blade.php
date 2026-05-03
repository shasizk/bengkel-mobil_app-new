@extends('be.master')
@section('Sparepart')
<div class="container">
    <div class="page-inner">
        <div class="card border-0 shadow-sm col-md-6 mx-auto">
            <div class="card-header bg-primary text-white"><h4 class="mb-0">Sparepart Detail</h4></div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr><td>Name</td><td class="fw-bold">: {{ $sparepart->name }}</td></tr>
                    <tr><td>Brand</td><td class="fw-bold">: {{ $sparepart->brand }}</td></tr>
                    <tr><td>Stock</td><td class="fw-bold">: {{ $sparepart->stock }} pcs</td></tr>
                    <tr><td>Price</td><td class="fw-bold">: Rp {{ number_format($sparepart->price, 0, ',', '.') }}</td></tr>
                </table>
            </div>
            <div class="card-footer"><a href="{{ backend_route('admin.spareparts.index') }}" class="btn btn-secondary w-100 btn-round">Back</a></div>
        </div>
    </div>
</div>
@endsection
