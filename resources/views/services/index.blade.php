@extends('be.master')
@section('Service') 
<div class="page-wrapper" style="padding-top: 20px;">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm text-white" style="border-radius: 24px; background: linear-gradient(135deg, #0f172a, #0f766e);">
                    <div class="card-body p-4 p-lg-5">
                        <p class="text-uppercase mb-2" style="letter-spacing: .25em; color: rgba(255,255,255,.7); font-size: 12px;">Admin Services</p>
                        <h3 class="fw-bold mb-2">Kelola layanan bengkel dari satu dashboard yang lebih rapi.</h3>
                        <p class="mb-0" style="color: rgba(255,255,255,.78);">Tambah layanan baru, ubah harga, perbarui estimasi waktu, dan rapikan katalog servis untuk booking customer.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <h4 class="fw-bold mb-0" style="color: #1a2035;">Daftar Layanan</h4>
                
                @if(($backendAuthUser->role ?? null) === 'admin')
                    <a href="{{ backend_route('admin.services.create') }}" class="btn btn-primary btn-round px-4">
                        <i class="fa fa-plus me-1"></i> Tambah Layanan
                    </a>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="40%">Service details</th>
                                <th width="20%">Price</th>
                                <th width="15%">Est. Time</th>
                                <th width="20%" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($services as $s)
                            <tr data-search-row="1" data-search-text="{{ $s->service_name }} {{ $s->description }} {{ $s->price }} {{ $s->estimated_time }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $s->service_name }}</div>
                                    <div class="small text-muted">{{ Str::limit($s->description, 50) }}</div>
                                </td>
                                <td><span class="badge badge-success">Rp {{ number_format($s->price, 0, ',', '.') }}</span></td>
                                <td><i class="far fa-clock me-1 text-muted"></i>{{ $s->estimated_time }} Min</td>
                                <td class="text-center">
                                    <div class="form-button-action">
                                        @if(($backendAuthUser->role ?? null) === 'admin')
                                            <a href="{{ backend_route('admin.services.edit', [$s->id]) }}" class="btn btn-link btn-primary btn-lg p-2" data-bs-toggle="tooltip" title="Edit layanan">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-link btn-danger" onclick="confirmDelete('{{ $s->id }}')">
                                                <i class="fa fa-times"></i>
                                            </button>

                                            <form id="delete-form-{{ $s->id }}" action="{{ backend_route('admin.services.destroy', [$s->id]) }}" method="POST" style="display:none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @else
                                            <span class="text-muted small">Read only</span>
                                        @endif
                                    </div>
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

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Data yang dihapus tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    })
}
</script>
@endsection
