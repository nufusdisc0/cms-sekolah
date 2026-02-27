@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pengguna Administrator</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="fa fa-plus"></i> Tambah Baru
                </button>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Grup</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->user_full_name }}</td>
                                <td>{{ $user->user_name }}</td>
                                <td>{{ $user->user_email }}</td>
                                <td>
                                    {{ $user_groups->firstWhere('id', $user->user_group_id)->user_group ?? 'None' }}
                                </td>
                                <td>
                                    @if($user->user_type !== 'super_user' || auth()->user()->user_type === 'super_user')
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal-{{ $user->id }}">Edit</button>
                                    @endif
                                    @if($user->user_type !== 'super_user')
                                        <form action="{{ route('backend.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editUserModal-{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('backend.users.update', $user->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Administrator</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Nama Lengkap</label>
                                                    <input type="text" name="user_full_name" class="form-control" value="{{ $user->user_full_name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Username</label>
                                                    <input type="text" name="user_name" class="form-control" value="{{ $user->user_name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Email</label>
                                                    <input type="email" name="user_email" class="form-control" value="{{ $user->user_email }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Grup Pengguna</label>
                                                    <select name="user_group_id" class="form-control" required>
                                                        <option value="">-- Pilih Grup --</option>
                                                        @foreach($user_groups as $group)
                                                            <option value="{{ $group->id }}" {{ $user->user_group_id == $group->id ? 'selected' : '' }}>{{ $group->user_group }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Password (Kosongkan jika tidak ingin diubah)</label>
                                                    <input type="password" name="user_password" class="form-control">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('backend.users.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Administrator Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Lengkap</label>
                        <input type="text" name="user_full_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="user_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="user_email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Grup Pengguna</label>
                        <select name="user_group_id" class="form-control" required>
                            <option value="">-- Pilih Grup --</option>
                            @foreach($user_groups as $group)
                                <option value="{{ $group->id }}">{{ $group->user_group }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="user_password" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
