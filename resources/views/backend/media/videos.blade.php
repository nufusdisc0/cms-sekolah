@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Video (YouTube)</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fa fa-plus"></i> Tambah Video
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
                        <tr><th>No</th><th>Judul Video</th><th>URL / ID YouTube</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @foreach($videos as $i => $row)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $row->post_title }}</td>
                            <td>{{ $row->post_content }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-{{ $row->id }}">Edit</button>
                                <form action="{{ route('backend.videos.destroy', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?');">@csrf @method('DELETE')<button type="submit" class="btn btn-danger btn-sm">Hapus</button></form>
                            </td>
                        </tr>
                        <div class="modal fade" id="editModal-{{ $row->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog"><div class="modal-content">
                                <form action="{{ route('backend.videos.update', $row->id) }}" method="POST">@csrf @method('PUT')
                                    <div class="modal-header"><h5 class="modal-title">Edit Video</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                    <div class="modal-body">
                                        <div class="mb-3"><label>Judul Video</label><input type="text" name="post_title" class="form-control" value="{{ $row->post_title }}" required></div>
                                        <div class="mb-3"><label>URL / ID YouTube</label><input type="text" name="post_content" class="form-control" value="{{ $row->post_content }}" required placeholder="Contoh: https://youtube.com/watch?v=xxxx"></div>
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
        <form action="{{ route('backend.videos.store') }}" method="POST">@csrf
            <div class="modal-header"><h5 class="modal-title">Tambah Video Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label>Judul Video</label><input type="text" name="post_title" class="form-control" required></div>
                <div class="mb-3"><label>URL / ID YouTube</label><input type="text" name="post_content" class="form-control" required placeholder="Contoh: https://youtube.com/watch?v=xxxx"></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
        </form>
    </div></div>
</div>
@endsection
