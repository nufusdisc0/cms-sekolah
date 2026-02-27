@extends('layouts.backend')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Iklan (Banner)</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addBannerModal">
                    <i class="fa fa-plus"></i> Tambah Iklan
                </button>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
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

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="150">Gambar</th>
                                <th>Keterangan</th>
                                <th>URL</th>
                                <th>Target</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($banners as $banner)
                                <tr>
                                    <td>
                                        @if($banner->link_image && Storage::disk('public')->exists($banner->link_image))
                                            <img src="{{ Storage::url($banner->link_image) }}" alt="{{ $banner->link_title }}" style="max-height: 50px; max-width: 120px; object-fit: contain;">
                                        @else
                                            <span class="text-muted">Tidak ada gambar</span>
                                        @endif
                                    </td>
                                    <td>{{ $banner->link_title }}</td>
                                    <td><a href="{{ $banner->link_url }}" target="_blank">{{ $banner->link_url }}</a></td>
                                    <td>{{ $banner->link_target }}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editBannerModal-{{ $banner->id }}"><i class="fa fa-edit"></i> Edit</button>
                                        <form action="{{ route('backend.banners.destroy', $banner->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus iklan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Hapus</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editBannerModal-{{ $banner->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('backend.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Iklan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label>Keterangan (Title)</label>
                                                        <input type="text" name="link_title" class="form-control" value="{{ $banner->link_title }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>URL Tujuan</label>
                                                        <input type="url" name="link_url" class="form-control" value="{{ $banner->link_url }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Link Target</label>
                                                        <select name="link_target" class="form-control" required>
                                                            <option value="_blank" {{ $banner->link_target == '_blank' ? 'selected' : '' }}>_blank (Tab Baru)</option>
                                                            <option value="_self" {{ $banner->link_target == '_self' ? 'selected' : '' }}>_self (Tab Sama)</option>
                                                            <option value="_parent" {{ $banner->link_target == '_parent' ? 'selected' : '' }}>_parent</option>
                                                            <option value="_top" {{ $banner->link_target == '_top' ? 'selected' : '' }}>_top</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Gambar Baru (Kosongkan jika tidak diubah)</label>
                                                        <input type="file" name="link_image" class="form-control" accept="image/*">
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
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada data iklan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addBannerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('backend.banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Iklan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Keterangan (Title)</label>
                        <input type="text" name="link_title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>URL Tujuan</label>
                        <input type="url" name="link_url" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Link Target</label>
                        <select name="link_target" class="form-control" required>
                            <option value="_blank">_blank (Tab Baru)</option>
                            <option value="_self">_self (Tab Sama)</option>
                            <option value="_parent">_parent</option>
                            <option value="_top">_top</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Gambar Iklan</label>
                        <input type="file" name="link_image" class="form-control" accept="image/*">
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
