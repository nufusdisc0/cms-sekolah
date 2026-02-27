@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Direktori Guru dan Tenaga Kependidikan (PTK)</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                    <i class="fa fa-plus"></i> Tambah PTK
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
                            <th>NIK</th>
                            <th>Nama Lengkap</th>
                            <th>L/P</th>
                            <th>Status Kepegawaian</th>
                            <th>Jenis PTK</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $row->nik }}</td>
                                <td>{{ $row->full_name }}</td>
                                <td>{{ $row->gender == 'M' ? 'L' : 'P' }}</td>
                                <td>{{ optional($employment_statuses->firstWhere('id', $row->employment_status_id))->option_name ?? '-' }}</td>
                                <td>{{ optional($employment_types->firstWhere('id', $row->employment_type_id))->option_name ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-{{ $row->id }}">Edit</button>
                                    <form action="{{ route('backend.academic_employees.destroy', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
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
                                        <form action="{{ route('backend.academic_employees.update', $row->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit PTK</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label>NIK</label>
                                                        <input type="text" name="nik" class="form-control" value="{{ $row->nik }}" required>
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
                                                        <label>Status Kepegawaian</label>
                                                        <select name="employment_status_id" class="form-control">
                                                            <option value="">-- Pilih --</option>
                                                            @foreach($employment_statuses as $opt)
                                                                <option value="{{ $opt->id }}" {{ $row->employment_status_id == $opt->id ? 'selected' : '' }}>{{ $opt->option_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label>Jenis PTK</label>
                                                        <select name="employment_type_id" class="form-control">
                                                            <option value="">-- Pilih --</option>
                                                            @foreach($employment_types as $opt)
                                                                <option value="{{ $opt->id }}" {{ $row->employment_type_id == $opt->id ? 'selected' : '' }}>{{ $opt->option_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label>Email</label>
                                                        <input type="email" name="email" class="form-control" value="{{ $row->email }}" required>
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
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('backend.academic_employees.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah PTK Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>NIK</label>
                            <input type="text" name="nik" class="form-control" required>
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
                            <label>Status Kepegawaian</label>
                            <select name="employment_status_id" class="form-control">
                                <option value="">-- Pilih --</option>
                                @foreach($employment_statuses as $opt)
                                    <option value="{{ $opt->id }}">{{ $opt->option_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Jenis PTK</label>
                            <select name="employment_type_id" class="form-control">
                                <option value="">-- Pilih --</option>
                                @foreach($employment_types as $opt)
                                    <option value="{{ $opt->id }}">{{ $opt->option_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
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
