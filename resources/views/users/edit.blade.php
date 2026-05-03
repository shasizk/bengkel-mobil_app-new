@extends('be.master')
@section('User')
<div class="container">
    <div class="page-inner">
        <div class="card shadow-sm border-0">
            <div class="card-header"><h4 class="fw-bold mb-0">Edit User</h4></div>
            <div class="card-body">
                <form method="POST" action="{{ backend_route('admin.users.update', [$user->id]) }}">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-md-6 mb-3"><label>Nama</label><input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required></div>
                        <div class="col-md-6 mb-3"><label>Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required></div>
                        <div class="col-md-6 mb-3"><label>Role</label><select name="role" class="form-select" required>@foreach(['admin','mekanik','kasir','customer','owner'] as $role)<option value="{{ $role }}" {{ old('role', $user->role) === $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>@endforeach</select></div>
                        <div class="col-md-6 mb-3"><label>No HP</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}"></div>
                        <div class="col-md-12 mb-3"><label>Alamat</label><textarea name="address" class="form-control" rows="3">{{ old('address', $user->address) }}</textarea></div>
                        <div class="col-md-6 mb-3"><label>Password Baru</label><input type="password" name="password" class="form-control"></div>
                        <div class="col-md-6 mb-3"><label>Konfirmasi Password Baru</label><input type="password" name="password_confirmation" class="form-control"></div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ backend_route('admin.users.index') }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
