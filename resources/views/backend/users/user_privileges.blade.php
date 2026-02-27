@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Hak Akses Modul</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPrivilegeModal">
                    <i class="fa fa-plus"></i> Tambah Hak Akses
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
                            <th>No</th>
                            <th>Grup Pengguna</th>
                            <th>Akses Modul</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user_privileges as $index => $privilege)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $privilege->userGroup->user_group ?? 'Unknown' }}</td>
                                <td>{{ $privilege->module->module_name ?? 'Unknown' }} ({{ $privilege->module->module_description ?? '' }})</td>
                                <td>
                                    <form action="{{ route('backend.user_privileges.destroy', $privilege->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin mencabut hak akses ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Cabut Akses</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addPrivilegeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('backend.user_privileges.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Berikan Hak Akses Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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
                        <label>Modul</label>
                        <select name="module_id" class="form-control" required>
                            <option value="">-- Pilih Modul --</option>
                            @foreach($modules as $module)
                                <option value="{{ $module->id }}">{{ $module->module_name }} - {{ $module->module_description }}</option>
                            @endforeach
                        </select>
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
