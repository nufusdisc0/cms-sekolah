@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Tahun Pelajaran (Academic Years)</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAcademicYearModal">
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
                            <th>Tahun Pelajaran</th>
                            <th>Semester</th>
                            <th>Semester Aktif</th>
                            <th>Semester PPDB/PMB</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($academic_years as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $row->academic_year }}</td>
                                <td>
                                    {{ $row->semester == 1 ? '1 (Ganjil)' : ($row->semester == 2 ? '2 (Genap)' : '3') }}
                                </td>
                                <td>
                                    @if($row->current_semester) <span class="badge bg-success">Ya</span> @else <span class="badge bg-danger">Tidak</span> @endif
                                </td>
                                <td>
                                    @if($row->admission_semester) <span class="badge bg-success">Ya</span> @else <span class="badge bg-danger">Tidak</span> @endif
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-{{ $row->id }}">Edit</button>
                                    @if(!$row->current_semester && !$row->admission_semester)
                                        <form action="{{ route('backend.academic_years.destroy', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal-{{ $row->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('backend.academic_years.update', $row->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Tahun Pelajaran</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Tahun Pelajaran (Format: YYYY-YYYY, ex: 2023-2024)</label>
                                                    <input type="text" name="academic_year" class="form-control" value="{{ $row->academic_year }}" required pattern="\d{4}-\d{4}">
                                                </div>
                                                <div class="mb-3">
                                                    <label>Semester</label>
                                                    <select name="semester" class="form-control" required>
                                                        <option value="1" {{ $row->semester == 1 ? 'selected' : '' }}>1 (Ganjil)</option>
                                                        <option value="2" {{ $row->semester == 2 ? 'selected' : '' }}>2 (Genap)</option>
                                                        <option value="3" {{ $row->semester == 3 ? 'selected' : '' }}>3</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="current_semester" value="1" id="current_semester_{{ $row->id }}" {{ $row->current_semester ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="current_semester_{{ $row->id }}">Jadikan Semester Aktif (Belajar Mengajar)</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="admission_semester" value="1" id="admission_semester_{{ $row->id }}" {{ $row->admission_semester ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="admission_semester_{{ $row->id }}">Jadikan Semester PPDB/PMB (Penerimaan Siswa/Mahasiswa Baru)</label>
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
<div class="modal fade" id="addAcademicYearModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('backend.academic_years.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Tahun Pelajaran Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Tahun Pelajaran (Format: YYYY-YYYY, ex: 2023-2024)</label>
                        <input type="text" name="academic_year" class="form-control" required pattern="\d{4}-\d{4}">
                    </div>
                    <div class="mb-3">
                        <label>Semester</label>
                        <select name="semester" class="form-control" required>
                            <option value="1">1 (Ganjil)</option>
                            <option value="2">2 (Genap)</option>
                            <option value="3">3</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="current_semester" value="1" id="current_semester_new">
                            <label class="form-check-label" for="current_semester_new">Jadikan Semester Aktif (Belajar Mengajar)</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="admission_semester" value="1" id="admission_semester_new">
                            <label class="form-check-label" for="admission_semester_new">Jadikan Semester PPDB/PMB (Penerimaan Siswa/Mahasiswa Baru)</label>
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
