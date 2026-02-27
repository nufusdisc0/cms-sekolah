@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Peserta Didik Aktif (Students)</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                    <i class="fa fa-plus"></i> Tambah Peserta Didik
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
                            <th>NIS/NIM</th>
                            <th>NISN</th>
                            <th>Nama Lengkap</th>
                            <th>L/P</th>
                            <th>Jurusan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $row->identity_number }}</td>
                                <td>{{ $row->nisn }}</td>
                                <td>{{ $row->full_name }}</td>
                                <td>{{ $row->gender == 'M' ? 'L' : 'P' }}</td>
                                <td>{{ $row->major->major_name ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-{{ $row->id }}">Edit</button>
                                    <form action="{{ route('backend.academic_students.destroy', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal-{{ $row->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form action="{{ route('backend.academic_students.update', $row->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Peserta Didik</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label>NIS / NIM</label>
                                                        <input type="text" name="identity_number" class="form-control" value="{{ $row->identity_number }}" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label>NISN</label>
                                                        <input type="text" name="nisn" class="form-control" value="{{ $row->nisn }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label>Nama Lengkap</label>
                                                        <input type="text" name="full_name" class="form-control" value="{{ $row->full_name }}" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label>Jenis Kelamin</label>
                                                        <select name="gender" class="form-control" required>
                                                            <option value="M" {{ $row->gender == 'M' ? 'selected' : '' }}>Laki-laki (L)</option>
                                                            <option value="F" {{ $row->gender == 'F' ? 'selected' : '' }}>Perempuan (P)</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label>Tempat Lahir</label>
                                                        <input type="text" name="birth_place" class="form-control" value="{{ $row->birth_place }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label>Tanggal Lahir</label>
                                                        <input type="date" name="birth_date" class="form-control" value="{{ $row->birth_date }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label>Jurusan</label>
                                                        <select name="major_id" class="form-control">
                                                            <option value="">-- Tidak Memiliki Jurusan --</option>
                                                            @foreach($majors as $major)
                                                                <option value="{{ $major->id }}" {{ $row->major_id == $major->id ? 'selected' : '' }}>{{ $major->major_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label>Email</label>
                                                        <input type="email" name="email" class="form-control" value="{{ $row->email }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label>No. HP / WhatsApp</label>
                                                        <input type="text" name="phone" class="form-control" value="{{ $row->phone }}">
                                                    </div>
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
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('backend.academic_students.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Peserta Didik Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>NIS / NIM</label>
                            <input type="text" name="identity_number" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>NISN</label>
                            <input type="text" name="nisn" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Nama Lengkap</label>
                            <input type="text" name="full_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Jenis Kelamin</label>
                            <select name="gender" class="form-control" required>
                                <option value="M">Laki-laki (L)</option>
                                <option value="F">Perempuan (P)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Tempat Lahir</label>
                            <input type="text" name="birth_place" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Tanggal Lahir</label>
                            <input type="date" name="birth_date" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Jurusan</label>
                            <select name="major_id" class="form-control">
                                <option value="">-- Tidak Memiliki Jurusan --</option>
                                @foreach($majors as $major)
                                    <option value="{{ $major->id }}">{{ $major->major_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>No. HP / WhatsApp</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
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
