@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Image Slider</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="fa fa-plus"></i> Tambah Slider
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
                        <tr><th>No</th><th>Caption</th><th>Image</th><th>Aktif</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @foreach($sliders as $i => $row)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $row->caption }}</td>
                            <td>@if($row->image)<img src="{{ asset('storage/' . $row->image) }}" width="100">@else - @endif</td>
                            <td><span class="badge bg-{{ $row->is_active == 'true' ? 'success' : 'secondary' }}">{{ $row->is_active == 'true' ? 'Aktif' : 'Nonaktif' }}</span></td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-{{ $row->id }}">Edit</button>
                                <form action="{{ route('backend.image_sliders.destroy', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?');">@csrf @method('DELETE')<button type="submit" class="btn btn-danger btn-sm">Hapus</button></form>
                            </td>
                        </tr>
                        <div class="modal fade" id="editModal-{{ $row->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog"><div class="modal-content">
                                <form action="{{ route('backend.image_sliders.update', $row->id) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
                                    <div class="modal-header"><h5 class="modal-title">Edit Slider</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                    <div class="modal-body">
                                        <div class="mb-3"><label>Caption</label><input type="text" name="caption" class="form-control" value="{{ $row->caption }}" required></div>
                                        <div class="mb-3"><label>Gambar</label><input type="file" name="image" class="form-control"></div>
                                        <div class="mb-3"><label>Status</label><select name="is_active" class="form-control"><option value="true" {{ $row->is_active == 'true' ? 'selected' : '' }}>Aktif</option><option value="false" {{ $row->is_active == 'false' ? 'selected' : '' }}>Nonaktif</option></select></div>
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
        <form action="{{ route('backend.image_sliders.store') }}" method="POST" enctype="multipart/form-data">@csrf
            <div class="modal-header"><h5 class="modal-title">Tambah Slider Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label>Caption</label><input type="text" name="caption" class="form-control" required></div>
                <div class="mb-3"><label>Gambar</label><input type="file" name="image" class="form-control" required></div>
                <div class="mb-3"><label>Status</label><select name="is_active" class="form-control"><option value="true">Aktif</option><option value="false">Nonaktif</option></select></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
        </form>
    </div></div>
</div>
@endsection
