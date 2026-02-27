@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Kelas (Class Groups)</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addClassGroupModal">
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
                            <th>No</th>
                            <th>Kelas</th>
                            <th>Sub Kelas</th>
                            <th>Jurusan (opsional)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($class_groups as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $row->class_group }}</td>
                                <td>{{ $row->sub_class_group }}</td>
                                <td>{{ $row->major->major_name ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-{{ $row->id }}">Edit</button>
                                    <form action="{{ route('backend.class_groups.destroy', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal-{{ $row->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('backend.class_groups.update', $row->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Kelas</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Kelas (contoh: X, XI, XII, 1, 2, 3)</label>
                                                    <input type="text" name="class_group" class="form-control" value="{{ $row->class_group }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Sub Kelas (contoh: A, B, RPL 1, TKJ 2)</label>
                                                    <input type="text" name="sub_class_group" class="form-control" value="{{ $row->sub_class_group }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label>Jurusan</label>
                                                    <select name="major_id" class="form-control">
                                                        <option value="">-- Tidak Memiliki Jurusan --</option>
                                                        @foreach($majors as $major)
                                                            <option value="{{ $major->id }}" {{ $row->major_id == $major->id ? 'selected' : '' }}>{{ $major->major_name }}</option>
                                                        @endforeach
                                                    </select>
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
<div class="modal fade" id="addClassGroupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('backend.class_groups.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kelas Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Kelas (contoh: X, XI, XII, 1, 2, 3)</label>
                        <input type="text" name="class_group" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Sub Kelas (contoh: A, B, RPL 1, TKJ 2)</label>
                        <input type="text" name="sub_class_group" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Jurusan</label>
                        <select name="major_id" class="form-control">
                            <option value="">-- Tidak Memiliki Jurusan --</option>
                            @foreach($majors as $major)
                                <option value="{{ $major->id }}">{{ $major->major_name }}</option>
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
