@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Album Foto</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fa fa-plus"></i> Tambah Album
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
                        <tr><th>No</th><th>Cover</th><th>Judul Album</th><th>Slug</th><th>Keterangan</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @foreach($albums as $i => $row)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                @if($row->album_cover)
                                    <img src="{{ asset('storage/' . $row->album_cover) }}" width="60" alt="Cover">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>{{ $row->album_title }}</td>
                            <td>{{ $row->album_slug }}</td>
                            <td>{{ $row->album_description }}</td>
                            <td>
                                <a href="{{ route('backend.photos.index', ['album_id' => $row->id]) }}" class="btn btn-info btn-sm"><i class="fa fa-camera"></i> Foto</a>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-{{ $row->id }}">Edit</button>
                                <form action="{{ route('backend.albums.destroy', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?');">@csrf @method('DELETE')<button type="submit" class="btn btn-danger btn-sm">Hapus</button></form>
                            </td>
                        </tr>
                        <div class="modal fade" id="editModal-{{ $row->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog"><div class="modal-content">
                                <form action="{{ route('backend.albums.update', $row->id) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
                                    <div class="modal-header"><h5 class="modal-title">Edit Album</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                    <div class="modal-body">
                                        <div class="mb-3"><label>Judul Album</label><input type="text" name="album_title" class="form-control" value="{{ $row->album_title }}" required></div>
                                        <div class="mb-3"><label>Keterangan</label><textarea name="album_description" class="form-control">{{ $row->album_description }}</textarea></div>
                                        <div class="mb-3"><label>Cover (Opsional)</label><input type="file" name="album_cover" class="form-control"></div>
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
        <form action="{{ route('backend.albums.store') }}" method="POST" enctype="multipart/form-data">@csrf
            <div class="modal-header"><h5 class="modal-title">Tambah Album Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label>Judul Album</label><input type="text" name="album_title" class="form-control" required></div>
                <div class="mb-3"><label>Keterangan</label><textarea name="album_description" class="form-control"></textarea></div>
                <div class="mb-3"><label>Cover (Opsional)</label><input type="file" name="album_cover" class="form-control"></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
        </form>
    </div></div>
</div>
@endsection
