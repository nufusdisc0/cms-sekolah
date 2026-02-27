@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Gelombang Pendaftaran (Admission Phases)</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fa fa-plus"></i> Tambah Gelombang
                </button>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr><th>No</th><th>Tahun Pelajaran</th><th>Gelombang</th><th>Tanggal Mulai</th><th>Tanggal Selesai</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @foreach($phases as $i => $row)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ optional($academic_years->firstWhere('id', $row->academic_year_id))->academic_year ?? '-' }}</td>
                            <td>{{ $row->phase_name }}</td>
                            <td>{{ $row->phase_start_date }}</td>
                            <td>{{ $row->phase_end_date }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-{{ $row->id }}">Edit</button>
                                <form action="{{ route('backend.admission_phases.destroy', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?');">@csrf @method('DELETE')<button type="submit" class="btn btn-danger btn-sm">Hapus</button></form>
                            </td>
                        </tr>
                        <div class="modal fade" id="editModal-{{ $row->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog"><div class="modal-content">
                                <form action="{{ route('backend.admission_phases.update', $row->id) }}" method="POST">@csrf @method('PUT')
                                    <div class="modal-header"><h5 class="modal-title">Edit Gelombang</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                    <div class="modal-body">
                                        <div class="mb-3"><label>Tahun Pelajaran</label>
                                            <select name="academic_year_id" class="form-control" required>
                                                @foreach($academic_years as $ay)<option value="{{ $ay->id }}" {{ $row->academic_year_id == $ay->id ? 'selected' : '' }}>{{ $ay->academic_year }}</option>@endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3"><label>Nama Gelombang</label><input type="text" name="phase_name" class="form-control" value="{{ $row->phase_name }}" required></div>
                                        <div class="mb-3"><label>Tanggal Mulai</label><input type="date" name="phase_start_date" class="form-control" value="{{ $row->phase_start_date }}" required></div>
                                        <div class="mb-3"><label>Tanggal Selesai</label><input type="date" name="phase_end_date" class="form-control" value="{{ $row->phase_end_date }}" required></div>
                                    </div>
                                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
                                </form>
                            </div></div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog"><div class="modal-content">
        <form action="{{ route('backend.admission_phases.store') }}" method="POST">@csrf
            <div class="modal-header"><h5 class="modal-title">Tambah Gelombang Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label>Tahun Pelajaran</label>
                    <select name="academic_year_id" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        @foreach($academic_years as $ay)<option value="{{ $ay->id }}">{{ $ay->academic_year }}</option>@endforeach
                    </select>
                </div>
                <div class="mb-3"><label>Nama Gelombang</label><input type="text" name="phase_name" class="form-control" required></div>
                <div class="mb-3"><label>Tanggal Mulai</label><input type="date" name="phase_start_date" class="form-control" required></div>
                <div class="mb-3"><label>Tanggal Selesai</label><input type="date" name="phase_end_date" class="form-control" required></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
        </form>
    </div></div>
</div>
@endsection
