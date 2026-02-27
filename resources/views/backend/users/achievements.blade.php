@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                <h5 class="mb-0">Prestasi</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fa fa-plus"></i> Tambah Prestasi
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

                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Prestasi</th>
                                <th>Jenis</th>
                                <th>Tingkat</th>
                                <th>Thn</th>
                                <th>Penyelenggara</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($achievements as $key => $ach)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $ach->achievement_description }}</td>
                                <td>{{ $ach->achievement_type == 1 ? 'Sains' : ($ach->achievement_type == 2 ? 'Seni' : 'Olahraga / Lainnya') }}</td>
                                <td>{{ $ach->achievement_level == 1 ? 'Sekolah' : ($ach->achievement_level == 2 ? 'Kecamatan' : ($ach->achievement_level == 3 ? 'Kabupaten/Kota' : ($ach->achievement_level == 4 ? 'Provinsi' : 'Nasional/Internasional'))) }}</td>
                                <td>{{ $ach->achievement_year }}</td>
                                <td>{{ $ach->achievement_organizer }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $ach->id }}"><i class="fa fa-edit"></i></button>
                                    <form action="{{ route('backend.achievements.destroy', $ach->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus prestasi ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal{{ $ach->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ route('backend.achievements.update', $ach->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Prestasi</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Nama Prestasi</label>
                                                    <input type="text" name="achievement_description" class="form-control" value="{{ $ach->achievement_description }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Jenis Prestasi</label>
                                                    <select name="achievement_type" class="form-select" required>
                                                        <option value="1" {{ $ach->achievement_type == 1 ? 'selected' : '' }}>Sains</option>
                                                        <option value="2" {{ $ach->achievement_type == 2 ? 'selected' : '' }}>Seni</option>
                                                        <option value="3" {{ $ach->achievement_type == 3 ? 'selected' : '' }}>Olahraga / Lainnya</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Tingkat</label>
                                                    <select name="achievement_level" class="form-select" required>
                                                        <option value="1" {{ $ach->achievement_level == 1 ? 'selected' : '' }}>Sekolah</option>
                                                        <option value="2" {{ $ach->achievement_level == 2 ? 'selected' : '' }}>Kecamatan</option>
                                                        <option value="3" {{ $ach->achievement_level == 3 ? 'selected' : '' }}>Kabupaten / Kota</option>
                                                        <option value="4" {{ $ach->achievement_level == 4 ? 'selected' : '' }}>Provinsi</option>
                                                        <option value="5" {{ $ach->achievement_level == 5 ? 'selected' : '' }}>Nasional / Internasional</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Tahun</label>
                                                    <input type="number" name="achievement_year" class="form-control" value="{{ $ach->achievement_year }}" min="1990" max="{{ date('Y') }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Penyelenggara</label>
                                                    <input type="text" name="achievement_organizer" class="form-control" value="{{ $ach->achievement_organizer }}">
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
                                <td colspan="7" class="text-center">Belum ada data prestasi.</td>
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
        <form action="{{ route('backend.achievements.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Prestasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Prestasi</label>
                        <input type="text" name="achievement_description" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Jenis Prestasi</label>
                        <select name="achievement_type" class="form-select" required>
                            <option value="1">Sains</option>
                            <option value="2">Seni</option>
                            <option value="3">Olahraga / Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Tingkat</label>
                        <select name="achievement_level" class="form-select" required>
                            <option value="1">Sekolah</option>
                            <option value="2">Kecamatan</option>
                            <option value="3">Kabupaten / Kota</option>
                            <option value="4">Provinsi</option>
                            <option value="5">Nasional / Internasional</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Tahun</label>
                        <input type="number" name="achievement_year" class="form-control" value="{{ date('Y') }}" min="1990" max="{{ date('Y') }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Penyelenggara</label>
                        <input type="text" name="achievement_organizer" class="form-control">
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
