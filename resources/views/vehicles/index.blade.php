@extends('be.master')
@section('Vehicle')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title" style="color: #1a2035; font-weight: bold;">Vehicle Management</h4>
        </div>
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title">All Vehicles</div>
                @if(($backendAuthUser->role ?? null) === 'admin')
                    <a href="{{ backend_route('admin.vehicles.create') }}" class="btn btn-primary btn-round px-4">
                        <i class="fa fa-plus me-1"></i> Add Vehicle
                    </a>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Brand & Model</th>
                                <th>License Plate</th> 
                                <th>Owner</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vehicles as $v)
                            <tr data-search-row="1" data-search-text="{{ $v->brand }} {{ $v->model }} {{ $v->license_plate }} {{ $v->user->name ?? 'Guest' }} {{ $v->year }} {{ $v->color }}">
                                <td>{{ $v->id }}</td>
                                <td>
                                    <strong>{{ $v->brand }}</strong> <br>
                                    <small>{{ $v->model }} ({{ $v->year }})</small>
                                </td>
                                <td>{{ $v->license_plate }}</td>
                                <td>{{ $v->user->name ?? 'Guest' }}</td>
                                <td class="text-center">
                                    <a href="{{ backend_route('admin.vehicles.show', [$v->id]) }}" class="btn btn-link btn-info p-2"><i class="fa fa-eye"></i></a>
                                    @if(($backendAuthUser->role ?? null) === 'admin')
                                        <a href="{{ backend_route('admin.vehicles.edit', [$v->id]) }}" class="btn btn-link btn-primary p-2"><i class="fa fa-edit"></i></a>

                                        <button type="button" class="btn btn-link btn-danger p-2" onclick="hapusData('{{ $v->id }}')">
                                            <i class="fa fa-times"></i>
                                        </button>

                                        <form id="form-hapus-{{ $v->id }}" action="{{ backend_route('admin.vehicles.destroy', [$v->id]) }}" method="POST" style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
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
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function hapusData(id) {
        Swal.fire({
            title: 'Yakin mau hapus?',
            text: "Data plate " + id + " bakal ilang permanen lho!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Baris ini yang nge-trigger form buat jalan
                document.getElementById('form-hapus-' + id).submit();
            }
        })
    }
</script>
@endsection
