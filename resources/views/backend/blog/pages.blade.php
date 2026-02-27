@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Halaman (Pages)</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPageModal">
                    <i class="fa fa-plus"></i> Tambah Halaman
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
                        <tr><th>No</th><th>Judul</th><th>Status</th><th>Visibilitas</th><th>Tanggal</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @foreach($pages as $i => $row)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $row->post_title }}</td>
                            <td><span class="badge bg-{{ $row->post_status == 'publish' ? 'success' : 'secondary' }}">{{ ucfirst($row->post_status) }}</span></td>
                            <td>{{ ucfirst($row->post_visibility) }}</td>
                            <td>{{ $row->created_at ? $row->created_at->format('d M Y') : '-' }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-{{ $row->id }}">Edit</button>
                                <form action="{{ route('backend.pages.destroy', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?');">@csrf @method('DELETE')<button type="submit" class="btn btn-danger btn-sm">Hapus</button></form>
                            </td>
                        </tr>
                        <div class="modal fade" id="editModal-{{ $row->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg"><div class="modal-content">
                                <form action="{{ route('backend.pages.update', $row->id) }}" method="POST">@csrf @method('PUT')
                                    <div class="modal-header"><h5 class="modal-title">Edit Halaman</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                    <div class="modal-body">
                                        <div class="mb-3"><label>Judul</label><input type="text" name="post_title" class="form-control" value="{{ $row->post_title }}" required></div>
                                        <div class="mb-3"><label>Konten</label><textarea name="post_content" class="form-control" rows="8" required>{{ $row->post_content }}</textarea></div>
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label>Status</label>
                                                <select name="post_status" class="form-control" required>
                                                    <option value="publish" {{ $row->post_status == 'publish' ? 'selected' : '' }}>Publish</option>
                                                    <option value="draft" {{ $row->post_status == 'draft' ? 'selected' : '' }}>Draft</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>Visibilitas</label>
                                                <select name="post_visibility" class="form-control" required>
                                                    <option value="public" {{ $row->post_visibility == 'public' ? 'selected' : '' }}>Public</option>
                                                    <option value="private" {{ $row->post_visibility == 'private' ? 'selected' : '' }}>Private</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label>Komentar</label>
                                                <select name="post_comment_status" class="form-control" required>
                                                    <option value="open" {{ $row->post_comment_status == 'open' ? 'selected' : '' }}>Open</option>
                                                    <option value="close" {{ $row->post_comment_status == 'close' ? 'selected' : '' }}>Close</option>
                                                </select>
                                            </div>
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
<div class="modal fade" id="addPageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <form action="{{ route('backend.pages.store') }}" method="POST">@csrf
            <div class="modal-header"><h5 class="modal-title">Tambah Halaman Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label>Judul</label><input type="text" name="post_title" class="form-control" required></div>
                <div class="mb-3"><label>Konten</label><textarea name="post_content" class="form-control" rows="8" required></textarea></div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Status</label>
                        <select name="post_status" class="form-control" required>
                            <option value="publish">Publish</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Visibilitas</label>
                        <select name="post_visibility" class="form-control" required>
                            <option value="public">Public</option>
                            <option value="private">Private</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Komentar</label>
                        <select name="post_comment_status" class="form-control" required>
                            <option value="open">Open</option>
                            <option value="close">Close</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
        </form>
    </div></div>
</div>
@endsection
