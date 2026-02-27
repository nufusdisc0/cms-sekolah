@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Biodata Karyawan</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success mt-3">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger mt-3">
                        <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <form action="{{ route('backend.employee_profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <h6 class="text-primary mt-4 border-bottom pb-2">Informasi Kepegawaian</h6>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3"><label>NIK (No. KTP/KIA)</label><input type="text" name="nik" class="form-control" value="{{ old('nik', $employee->nik) }}" required></div>
                        <div class="col-md-6 mb-3"><label>NIP</label><input type="text" name="nip" class="form-control" value="{{ old('nip', $employee->nip) }}"></div>
                        <div class="col-md-6 mb-3"><label>NUPTK</label><input type="text" name="nuptk" class="form-control" value="{{ old('nuptk', $employee->nuptk) }}"></div>
                        <div class="col-md-6 mb-3"><label>NPWP</label><input type="text" name="npwp" class="form-control" value="{{ old('npwp', $employee->npwp) }}"></div>
                        <div class="col-md-4 mb-3">
                            <label>Status Kepegawaian</label>
                            <select name="employment_status_id" class="form-select">
                                <option value="0">-- Pilih --</option>
                                @foreach($employment_status as $es)
                                    <option value="{{ $es->id }}" {{ $employee->employment_status_id == $es->id ? 'selected' : '' }}>{{ $es->option_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Jenis GTK</label>
                            <select name="employment_type_id" class="form-select">
                                <option value="0">-- Pilih --</option>
                                @foreach($employment_types as $et)
                                    <option value="{{ $et->id }}" {{ $employee->employment_type_id == $et->id ? 'selected' : '' }}>{{ $et->option_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <h6 class="text-primary mt-4 border-bottom pb-2">Data Pribadi</h6>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3"><label>Nama Lengkap</label><input type="text" name="full_name" class="form-control" value="{{ old('full_name', $employee->full_name) }}" required></div>
                        <div class="col-md-6 mb-3"><label>Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $employee->email) }}" required></div>
                        <div class="col-md-4 mb-3">
                            <label>Jenis Kelamin</label>
                            <select name="gender" class="form-select">
                                <option value="M" {{ $employee->gender == 'M' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="F" {{ $employee->gender == 'F' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3"><label>Tempat Lahir</label><input type="text" name="birth_place" class="form-control" value="{{ old('birth_place', $employee->birth_place) }}"></div>
                        <div class="col-md-4 mb-3"><label>Tanggal Lahir</label><input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', $employee->birth_date) }}"></div>
                        <div class="col-md-6 mb-3"><label>Nama Ibu Kandung</label><input type="text" name="mother_name" class="form-control" value="{{ old('mother_name', $employee->mother_name) }}"></div>
                        <div class="col-md-6 mb-3">
                            <label>Agama</label>
                            <select name="religion_id" class="form-select">
                                <option value="0">-- Pilih Agama --</option>
                                @foreach($religions as $rel)
                                    <option value="{{ $rel->id }}" {{ $employee->religion_id == $rel->id ? 'selected' : '' }}>{{ $rel->option_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <h6 class="text-primary mt-4 border-bottom pb-2">Data Tempat Tinggal & Kontak</h6>
                    <div class="row mb-3">
                        <div class="col-md-12 mb-3"><label>Alamat Jalan</label><input type="text" name="street_address" class="form-control" value="{{ old('street_address', $employee->street_address) }}"></div>
                        <div class="col-md-3 mb-3"><label>RT</label><input type="text" name="rt" class="form-control" value="{{ old('rt', $employee->rt) }}"></div>
                        <div class="col-md-3 mb-3"><label>RW</label><input type="text" name="rw" class="form-control" value="{{ old('rw', $employee->rw) }}"></div>
                        <div class="col-md-6 mb-3"><label>Desa/Kelurahan</label><input type="text" name="village" class="form-control" value="{{ old('village', $employee->village) }}"></div>
                        <div class="col-md-4 mb-3"><label>Kecamatan</label><input type="text" name="sub_district" class="form-control" value="{{ old('sub_district', $employee->sub_district) }}"></div>
                        <div class="col-md-4 mb-3"><label>Kabupaten/Kota</label><input type="text" name="district" class="form-control" value="{{ old('district', $employee->district) }}"></div>
                        <div class="col-md-4 mb-3"><label>Kode Pos</label><input type="text" name="postal_code" class="form-control" value="{{ old('postal_code', $employee->postal_code) }}"></div>
                        <div class="col-md-6 mb-3"><label>Telepon</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $employee->phone) }}"></div>
                        <div class="col-md-6 mb-3"><label>No. HP</label><input type="text" name="mobile_phone" class="form-control" value="{{ old('mobile_phone', $employee->mobile_phone) }}"></div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3"><i class="fa fa-save"></i> Simpan Biodata</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
