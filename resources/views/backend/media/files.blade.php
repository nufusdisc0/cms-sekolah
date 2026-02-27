@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar File</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fa fa-upload"></i> Upload File
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
                        <tr><th>No</th><th>Judul File</th><th>Kategori</th><th>Ekstensi</th><th>Ukuran</th><th>Visibilitas</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @foreach($files as $i => $row)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                <strong>{{ $row->file_title }}</strong><br>
                                <small class="text-muted"><a href="{{ asset('storage/' . $row->file_path) }}" target="_blank">{{ $row->file_name }}</a></small>
                            </td>
                            <td>{{ $row->category ? $row->category->category_name : '-' }}</td>
                            <td>{{ strtoupper($row->file_ext) }}</td>
                            <td>{{ $row->file_size }} KB</td>
                            <td>
                                @if($row->file_visibility == 'public')
                                    <span class="badge bg-success">Publik</span>
                                @else
                                    <span class="badge bg-secondary">Privat</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('backend.files.destroy', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus file ini?');">@csrf @method('DELETE')<button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Hapus</button></form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog"><div class="modal-content">
        <form action="{{ route('backend.files.store') }}" method="POST" enctype="multipart/form-data">@csrf
            <div class="modal-header"><h5 class="modal-title">Upload File Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label>Judul File</label><input type="text" name="file_title" class="form-control" required></div>
                <div class="mb-3"><label>Keterangan</label><textarea name="file_description" class="form-control"></textarea></div>
                <div class="mb-3">
                    <label>Kategori</label>
                    <select name="file_category_id" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label>Visibilitas</label>
                    <select name="file_visibility" class="form-select" required>
                        <option value="public">Publik</option>
                        <option value="private">Privat</option>
                    </select>
                </div>
                <div class="mb-3"><label>Pilih File</label><input type="file" name="file" class="form-control" required></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Upload</button></div>
        </form>
    </div></div>
</div>
@endsection
