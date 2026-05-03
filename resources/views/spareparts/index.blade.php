@extends('be.master')
@section('Sparepart')
<div class="container">
    <div class="page-inner">
        <div class="card border-0 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="fw-bold mb-0" style="color: #1a2035;">Sparepart Inventory</h4>
                @if(in_array($backendAuthUser->role ?? null, ['admin', 'owner'], true))
                    <a href="{{ backend_route('admin.spareparts.create') }}" class="btn btn-primary btn-round px-4">
                        <i class="fa fa-plus me-1"></i> Add Sparepart
                    </a>
                @endif
            </div>
            <div class="card-body">
                <table class="table table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Brand</th>
                            <th>Stock</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($spareparts as $s)
                        <tr data-search-row="1" data-search-text="{{ $s->name }} {{ $s->brand }} {{ $s->stock }} {{ $s->price }}">
                            <td>{{ $s->id }}</td>
                            <td><strong>{{ $s->name }}</strong></td>
                            <td>{{ $s->brand }}</td>
                            <td><span class="badge {{ $s->stock < 10 ? 'badge-danger' : 'badge-success' }}">{{ $s->stock }}</span></td>
                            <td>Rp {{ number_format($s->price, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ backend_route('admin.spareparts.show', [$s->id]) }}" class="btn btn-link btn-info p-2"><i class="fa fa-eye"></i></a>
                                @if(in_array($backendAuthUser->role ?? null, ['admin', 'owner'], true))
                                    <a href="{{ backend_route('admin.spareparts.edit', [$s->id]) }}" class="btn btn-link btn-primary p-2"><i class="fa fa-edit"></i></a>
                                @endif
                                @if(($backendAuthUser->role ?? null) === 'admin')
                                    <button type="button" class="btn btn-link btn-danger p-2" onclick="hapusPart('{{ $s->id }}')">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <form id="form-hapus-{{ $s->id }}" action="{{ backend_route('admin.spareparts.destroy', [$s->id]) }}" method="POST" style="display:none;">
                                        @csrf @method('DELETE')
                                    </form>
                                @else
                                    <span class="text-muted small">Read only</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function hapusPart(id) {
        Swal.fire({
            title: 'Hapus data ini?',
            text: "Stok sparepart ini akan hilang dari sistem!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-hapus-' + id).submit();
            }
        })
    }
</script>
@endsection
