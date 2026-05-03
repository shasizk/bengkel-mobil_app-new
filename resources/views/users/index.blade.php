@extends('be.master')
@section('User')
<div class="container">
    <div class="page-inner">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold mb-0">{{ ($backendAuthUser->role ?? null) === 'owner' ? 'Daftar Mekanik' : 'Manajemen User' }}</h4>
                    <small class="text-muted">Kelola akun dan role pengguna sistem.</small>
                </div>
                @if(($backendAuthUser->role ?? null) === 'admin')
                    <a href="{{ backend_route('admin.users.create') }}" class="btn btn-primary">+ Tambah User</a>
                @endif
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <a href="{{ backend_route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">Semua</a>
                    <a href="{{ backend_route('admin.users.index', ['role' => 'admin']) }}" class="btn btn-sm btn-outline-secondary">Admin</a>
                    <a href="{{ backend_route('admin.users.index', ['role' => 'mekanik']) }}" class="btn btn-sm btn-outline-secondary">Mekanik</a>
                    <a href="{{ backend_route('admin.users.index', ['role' => 'kasir']) }}" class="btn btn-sm btn-outline-secondary">Kasir</a>
                    <a href="{{ backend_route('admin.users.index', ['role' => 'customer']) }}" class="btn btn-sm btn-outline-secondary">Customer</a>
                    <a href="{{ backend_route('admin.users.index', ['role' => 'owner']) }}" class="btn btn-sm btn-outline-secondary">Owner</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Phone</th>
                                <th>Address</th>
                                @if(($backendAuthUser->role ?? null) === 'owner')
                                    <th>Presensi Terakhir</th>
                                @endif
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr data-search-row="1" data-search-text="{{ $user->name }} {{ $user->email }} {{ $user->role }} {{ $user->phone }} {{ $user->address }}">
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td><span class="badge bg-light text-dark text-uppercase">{{ $user->role }}</span></td>
                                    <td>{{ $user->phone ?: '-' }}</td>
                                    <td>{{ $user->address ?: '-' }}</td>
                                    @if(($backendAuthUser->role ?? null) === 'owner')
                                        @php($latestAttendance = $attendanceSummary[$user->id] ?? null)
                                        <td>
                                            @if($latestAttendance?->last_attendance_date)
                                                {{ \Carbon\Carbon::parse($latestAttendance->last_attendance_date)->format('d M Y') }}
                                            @else
                                                <span class="text-muted small">Belum ada</span>
                                            @endif
                                        </td>
                                    @endif
                                    <td>
                                        @if(($backendAuthUser->role ?? null) === 'admin')
                                            <a href="{{ backend_route('admin.users.edit', [$user->id]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        @else
                                            <span class="text-muted small">Read only</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="{{ ($backendAuthUser->role ?? null) === 'owner' ? 8 : 7 }}" class="text-center text-muted">Belum ada data user.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
