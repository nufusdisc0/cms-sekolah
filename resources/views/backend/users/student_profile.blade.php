@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Biodata Siswa</h5>
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

                <form action="{{ route('backend.student_profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <h6 class="text-primary mt-4 border-bottom pb-2">Data Pribadi</h6>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3"><label>Nama Lengkap</label><input type="text" name="full_name" class="form-control" value="{{ old('full_name', $student->full_name) }}" required></div>
                        <div class="col-md-6 mb-3"><label>Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $student->email) }}" required></div>
                        <div class="col-md-4 mb-3">
                            <label>Jenis Kelamin</label>
                            <select name="gender" class="form-select">
                                <option value="M" {{ $student->gender == 'M' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="F" {{ $student->gender == 'F' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3"><label>Tempat Lahir</label><input type="text" name="birth_place" class="form-control" value="{{ old('birth_place', $student->birth_place) }}"></div>
                        <div class="col-md-4 mb-3"><label>Tanggal Lahir</label><input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', $student->birth_date) }}"></div>
                        
                        <div class="col-md-4 mb-3"><label>NISN</label><input type="text" name="nisn" class="form-control" value="{{ old('nisn', $student->nisn) }}"></div>
                        <div class="col-md-4 mb-3"><label>NIK (No. KTP/KIA)</label><input type="text" name="nik" class="form-control" value="{{ old('nik', $student->nik) }}"></div>
                        <div class="col-md-4 mb-3">
                            <label>Agama</label>
                            <select name="religion_id" class="form-select">
                                <option value="0">-- Pilih Agama --</option>
                                @foreach($religions as $rel)
                                    <option value="{{ $rel->id }}" {{ $student->religion_id == $rel->id ? 'selected' : '' }}>{{ $rel->option_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3"><label>Hobi</label><input type="text" name="hobby" class="form-control" value="{{ old('hobby', $student->hobby) }}"></div>
                        <div class="col-md-4 mb-3"><label>Cita-cita</label><input type="text" name="ambition" class="form-control" value="{{ old('ambition', $student->ambition) }}"></div>
                        <div class="col-md-4 mb-3"><label>Kewarganegaraan</label><input type="text" name="citizenship" class="form-control" value="{{ old('citizenship', $student->citizenship) }}"></div>
                    </div>

                    <h6 class="text-primary mt-4 border-bottom pb-2">Data Tempat Tinggal & Kontak</h6>
                    <div class="row mb-3">
                        <div class="col-md-12 mb-3"><label>Alamat Jalan</label><input type="text" name="street_address" class="form-control" value="{{ old('street_address', $student->street_address) }}"></div>
                        <div class="col-md-3 mb-3"><label>RT</label><input type="text" name="rt" class="form-control" value="{{ old('rt', $student->rt) }}"></div>
                        <div class="col-md-3 mb-3"><label>RW</label><input type="text" name="rw" class="form-control" value="{{ old('rw', $student->rw) }}"></div>
                        <div class="col-md-6 mb-3"><label>Desa/Kelurahan</label><input type="text" name="village" class="form-control" value="{{ old('village', $student->village) }}"></div>
                        <div class="col-md-4 mb-3"><label>Kecamatan</label><input type="text" name="sub_district" class="form-control" value="{{ old('sub_district', $student->sub_district) }}"></div>
                        <div class="col-md-4 mb-3"><label>Kabupaten/Kota</label><input type="text" name="district" class="form-control" value="{{ old('district', $student->district) }}"></div>
                        <div class="col-md-4 mb-3"><label>Kode Pos</label><input type="text" name="postal_code" class="form-control" value="{{ old('postal_code', $student->postal_code) }}"></div>
                        <div class="col-md-6 mb-3"><label>Telepon</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $student->phone) }}"></div>
                        <div class="col-md-6 mb-3"><label>No. HP</label><input type="text" name="mobile_phone" class="form-control" value="{{ old('mobile_phone', $student->mobile_phone) }}"></div>
                    </div>

                    <h6 class="text-primary mt-4 border-bottom pb-2">Data Orang Tua / Wali</h6>
                    <div class="row mb-3">
                        <div class="col-md-4 mb-3"><label>Nama Ayah</label><input type="text" name="father_name" class="form-control" value="{{ old('father_name', $student->father_name) }}"></div>
                        <div class="col-md-4 mb-3"><label>Tahun Lahir Ayah</label><input type="text" name="father_birth_year" class="form-control" value="{{ old('father_birth_year', $student->father_birth_year) }}"></div>
                        <div class="col-md-4 mb-3"></div>

                        <div class="col-md-4 mb-3"><label>Nama Ibu</label><input type="text" name="mother_name" class="form-control" value="{{ old('mother_name', $student->mother_name) }}"></div>
                        <div class="col-md-4 mb-3"><label>Tahun Lahir Ibu</label><input type="text" name="mother_birth_year" class="form-control" value="{{ old('mother_birth_year', $student->mother_birth_year) }}"></div>
                        <div class="col-md-4 mb-3"></div>

                        <div class="col-md-4 mb-3"><label>Nama Wali</label><input type="text" name="guardian_name" class="form-control" value="{{ old('guardian_name', $student->guardian_name) }}"></div>
                        <div class="col-md-4 mb-3"><label>Tahun Lahir Wali</label><input type="text" name="guardian_birth_year" class="form-control" value="{{ old('guardian_birth_year', $student->guardian_birth_year) }}"></div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3"><i class="fa fa-save"></i> Simpan Biodata</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
