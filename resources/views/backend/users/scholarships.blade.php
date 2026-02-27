@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                <h5 class="mb-0">Beasiswa</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fa fa-plus"></i> Tambah Beasiswa
                </button>
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

                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Jenis Beasiswa</th>
                                <th>Nama Beasiswa / Keterangan</th>
                                <th>Tahun Mulai</th>
                                <th>Tahun Selesai</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($scholarships as $key => $schol)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $schol->scholarship_type == 1 ? 'Anak Berprestasi' : ($schol->scholarship_type == 2 ? 'Anak Miskin' : ($schol->scholarship_type == 3 ? 'Pendidikan' : ($schol->scholarship_type == 4 ? 'Unggulan' : 'Lain-lain'))) }}</td>
                                <td>{{ $schol->scholarship_description }}</td>
                                <td>{{ $schol->scholarship_start_year }}</td>
                                <td>{{ $schol->scholarship_end_year }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $schol->id }}"><i class="fa fa-edit"></i></button>
                                    <form action="{{ route('backend.scholarships.destroy', $schol->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus beasiswa ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal{{ $schol->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ route('backend.scholarships.update', $schol->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Beasiswa</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Jenis Beasiswa</label>
                                                    <select name="scholarship_type" class="form-select" required>
                                                        <option value="1" {{ $schol->scholarship_type == 1 ? 'selected' : '' }}>Anak Berprestasi</option>
                                                        <option value="2" {{ $schol->scholarship_type == 2 ? 'selected' : '' }}>Anak Miskin</option>
                                                        <option value="3" {{ $schol->scholarship_type == 3 ? 'selected' : '' }}>Pendidikan</option>
                                                        <option value="4" {{ $schol->scholarship_type == 4 ? 'selected' : '' }}>Unggulan</option>
                                                        <option value="5" {{ $schol->scholarship_type == 5 ? 'selected' : '' }}>Lain-lain</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Nama Beasiswa / Keterangan</label>
                                                    <input type="text" name="scholarship_description" class="form-control" value="{{ $schol->scholarship_description }}" required>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6 mb-3">
                                                        <label>Tahun Mulai</label>
                                                        <input type="number" name="scholarship_start_year" class="form-control" value="{{ $schol->scholarship_start_year }}" min="1990" max="{{ date('Y') }}" required>
                                                    </div>
                                                    <div class="col-6 mb-3">
                                                        <label>Tahun Selesai</label>
                                                        <input type="number" name="scholarship_end_year" class="form-control" value="{{ $schol->scholarship_end_year }}" min="1990" max="{{ date('Y') + 5 }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data beasiswa.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('backend.scholarships.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Beasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Jenis Beasiswa</label>
                        <select name="scholarship_type" class="form-select" required>
                            <option value="1">Anak Berprestasi</option>
                            <option value="2">Anak Miskin</option>
                            <option value="3">Pendidikan</option>
                            <option value="4">Unggulan</option>
                            <option value="5">Lain-lain</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Nama Beasiswa / Keterangan</label>
                        <input type="text" name="scholarship_description" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label>Tahun Mulai</label>
                            <input type="number" name="scholarship_start_year" class="form-control" value="{{ date('Y') }}" min="1990" max="{{ date('Y') }}" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label>Tahun Selesai</label>
                            <input type="number" name="scholarship_end_year" class="form-control" value="{{ date('Y') }}" min="1990" max="{{ date('Y') + 5 }}" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
