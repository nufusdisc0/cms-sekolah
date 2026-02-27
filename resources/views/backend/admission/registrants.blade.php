@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Calon Peserta Didik Baru (Registrants)</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fa fa-plus"></i> Tambah Pendaftar
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
                        <tr><th>No</th><th>No. Pendaftaran</th><th>Nama</th><th>L/P</th><th>Pilihan 1</th><th>Daftar Ulang</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @foreach($registrants as $i => $row)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $row->registration_number }}</td>
                            <td>{{ $row->full_name }}</td>
                            <td>{{ $row->gender == 'M' ? 'L' : 'P' }}</td>
                            <td>{{ optional($majors->firstWhere('id', $row->first_choice_id))->major_name ?? '-' }}</td>
                            <td><span class="badge bg-{{ $row->re_registration == 'true' ? 'success' : 'secondary' }}">{{ $row->re_registration == 'true' ? 'Sudah' : 'Belum' }}</span></td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-{{ $row->id }}">Edit</button>
                                <form action="{{ route('backend.registrants.destroy', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?');">@csrf @method('DELETE')<button type="submit" class="btn btn-danger btn-sm">Hapus</button></form>
                            </td>
                        </tr>
                        <div class="modal fade" id="editModal-{{ $row->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg"><div class="modal-content">
                                <form action="{{ route('backend.registrants.update', $row->id) }}" method="POST">@csrf @method('PUT')
                                    <div class="modal-header"><h5 class="modal-title">Edit Pendaftar</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3"><label>Nama Lengkap</label><input type="text" name="full_name" class="form-control" value="{{ $row->full_name }}" required></div>
                                            <div class="col-md-3 mb-3"><label>Jenis Kelamin</label><select name="gender" class="form-control" required><option value="M" {{ $row->gender == 'M' ? 'selected' : '' }}>Laki-laki</option><option value="F" {{ $row->gender == 'F' ? 'selected' : '' }}>Perempuan</option></select></div>
                                            <div class="col-md-3 mb-3"><label>NIK</label><input type="text" name="nik" class="form-control" value="{{ $row->nik }}"></div>
                                            <div class="col-md-3 mb-3"><label>NISN</label><input type="text" name="nisn" class="form-control" value="{{ $row->nisn }}"></div>
                                            <div class="col-md-3 mb-3"><label>Tempat Lahir</label><input type="text" name="birth_place" class="form-control" value="{{ $row->birth_place }}"></div>
                                            <div class="col-md-3 mb-3"><label>Tanggal Lahir</label><input type="date" name="birth_date" class="form-control" value="{{ $row->birth_date }}"></div>
                                            <div class="col-md-3 mb-3"><label>Email</label><input type="email" name="email" class="form-control" value="{{ $row->email }}"></div>
                                            <div class="col-md-3 mb-3"><label>No. HP</label><input type="text" name="phone" class="form-control" value="{{ $row->phone }}"></div>
                                            <div class="col-md-3 mb-3"><label>Pilihan 1</label><select name="first_choice_id" class="form-control"><option value="">-- Pilih --</option>@foreach($majors as $m)<option value="{{ $m->id }}" {{ $row->first_choice_id == $m->id ? 'selected' : '' }}>{{ $m->major_name }}</option>@endforeach</select></div>
                                            <div class="col-md-3 mb-3"><label>Pilihan 2</label><select name="second_choice_id" class="form-control"><option value="">-- Pilih --</option>@foreach($majors as $m)<option value="{{ $m->id }}" {{ $row->second_choice_id == $m->id ? 'selected' : '' }}>{{ $m->major_name }}</option>@endforeach</select></div>
                                            <div class="col-md-6 mb-3"><label>Alamat</label><input type="text" name="street_address" class="form-control" value="{{ $row->street_address }}"></div>
                                            <div class="col-md-3 mb-3"><label>Nama Ayah</label><input type="text" name="father_name" class="form-control" value="{{ $row->father_name }}"></div>
                                            <div class="col-md-3 mb-3"><label>Nama Ibu</label><input type="text" name="mother_name" class="form-control" value="{{ $row->mother_name }}"></div>
                                            <div class="col-md-3 mb-3"><label>Daftar Ulang</label><select name="re_registration" class="form-control"><option value="false" {{ $row->re_registration != 'true' ? 'selected' : '' }}>Belum</option><option value="true" {{ $row->re_registration == 'true' ? 'selected' : '' }}>Sudah</option></select></div>
                                        </div>
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
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <form action="{{ route('backend.registrants.store') }}" method="POST">@csrf
            <div class="modal-header"><h5 class="modal-title">Tambah Pendaftar Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3"><label>Nama Lengkap</label><input type="text" name="full_name" class="form-control" required></div>
                    <div class="col-md-3 mb-3"><label>Jenis Kelamin</label><select name="gender" class="form-control" required><option value="M">Laki-laki</option><option value="F">Perempuan</option></select></div>
                    <div class="col-md-3 mb-3"><label>NIK</label><input type="text" name="nik" class="form-control"></div>
                    <div class="col-md-3 mb-3"><label>NISN</label><input type="text" name="nisn" class="form-control"></div>
                    <div class="col-md-3 mb-3"><label>Tempat Lahir</label><input type="text" name="birth_place" class="form-control"></div>
                    <div class="col-md-3 mb-3"><label>Tanggal Lahir</label><input type="date" name="birth_date" class="form-control"></div>
                    <div class="col-md-3 mb-3"><label>Email</label><input type="email" name="email" class="form-control"></div>
                    <div class="col-md-3 mb-3"><label>No. HP</label><input type="text" name="phone" class="form-control"></div>
                    <div class="col-md-3 mb-3"><label>Pilihan 1</label><select name="first_choice_id" class="form-control"><option value="">-- Pilih --</option>@foreach($majors as $m)<option value="{{ $m->id }}">{{ $m->major_name }}</option>@endforeach</select></div>
                    <div class="col-md-3 mb-3"><label>Pilihan 2</label><select name="second_choice_id" class="form-control"><option value="">-- Pilih --</option>@foreach($majors as $m)<option value="{{ $m->id }}">{{ $m->major_name }}</option>@endforeach</select></div>
                    <div class="col-md-6 mb-3"><label>Alamat</label><input type="text" name="street_address" class="form-control"></div>
                    <div class="col-md-3 mb-3"><label>Nama Ayah</label><input type="text" name="father_name" class="form-control"></div>
                    <div class="col-md-3 mb-3"><label>Nama Ibu</label><input type="text" name="mother_name" class="form-control"></div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
        </form>
    </div></div>
</div>
@endsection
