@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    @if($album)
                        Foto dalam Album: {{ $album->album_title }}
                    @else
                        Semua Foto
                    @endif
                </h5>
                <div>
                    @if($album)
                        <a href="{{ route('backend.albums.index') }}" class="btn btn-secondary btn-sm me-2"><i class="fa fa-arrow-left"></i> Kembali ke Album</a>
                    @endif
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="fa fa-plus"></i> Upload Foto
                    </button>
                </div>
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
                
                <form method="GET" action="{{ route('backend.photos.index') }}" class="mb-4 d-flex align-items-center" style="max-width: 400px;">
                    <select name="album_id" class="form-select me-2" onchange="this.form.submit()">
                        <option value="">-- Semua Album --</option>
                        @foreach($albums as $row)
                            <option value="{{ $row->id }}" {{ $album_id == $row->id ? 'selected' : '' }}>{{ $row->album_title }}</option>
                        @endforeach
                    </select>
                </form>

                <div class="row">
                    @forelse($photos as $photo)
                        <div class="col-md-3 mb-4">
                            <div class="card h-100">
                                <img src="{{ asset('storage/' . $photo->photo_name) }}" class="card-img-top" alt="Photo" style="object-fit:cover; height:200px;">
                                <div class="card-footer bg-white border-0 text-center">
                                    <form action="{{ route('backend.photos.destroy', $photo->id) }}" method="POST" onsubmit="return confirm('Yakin hapus foto ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm w-100"><i class="fa fa-trash"></i> Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted py-4">Belum ada foto yang diupload.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('backend.photos.store') }}" method="POST" enctype="multipart/form-data">@csrf
                <div class="modal-header"><h5 class="modal-title">Upload Foto Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Pilih Album</label>
                        <select name="photo_album_id" class="form-select" required>
                            <option value="">-- Pilih Album --</option>
                            @foreach($albums as $row)
                                <option value="{{ $row->id }}" {{ $album_id == $row->id ? 'selected' : '' }}>{{ $row->album_title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>File Foto</label>
                        <input type="file" name="photo_name" class="form-control" required accept="image/*">
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Upload</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
