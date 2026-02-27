<div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fa fa-edit me-2"></i>Tulisan (Posts) — <small class="text-muted">Livewire</small></h5>
                    <div class="d-flex gap-2 align-items-center">
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm" placeholder="Cari tulisan..." style="width: 200px;">
                        <button wire:click="openModal" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> Tambah Tulisan
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if(session()->has('message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fa fa-check-circle me-1"></i> {{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>Judul</th>
                                    <th style="width: 100px;">Status</th>
                                    <th style="width: 100px;">Visibilitas</th>
                                    <th style="width: 120px;">Tanggal</th>
                                    <th style="width: 160px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($posts as $i => $row)
                                <tr wire:key="post-{{ $row->id }}">
                                    <td>{{ $posts->firstItem() + $i }}</td>
                                    <td>{{ $row->post_title }}</td>
                                    <td>
                                        <span class="badge bg-{{ $row->post_status == 'publish' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($row->post_status) }}
                                        </span>
                                    </td>
                                    <td>{{ ucfirst($row->post_visibility) }}</td>
                                    <td>{{ $row->created_at ? $row->created_at->format('d M Y') : '-' }}</td>
                                    <td>
                                        <button wire:click="edit({{ $row->id }})" class="btn btn-warning btn-sm">
                                            <i class="fa fa-pencil"></i> Edit
                                        </button>

                                        @if($confirmingDeleteId === $row->id)
                                            <button wire:click="delete({{ $row->id }})" class="btn btn-danger btn-sm">
                                                <i class="fa fa-check"></i> Ya
                                            </button>
                                            <button wire:click="cancelDelete" class="btn btn-secondary btn-sm">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        @else
                                            <button wire:click="confirmDelete({{ $row->id }})" class="btn btn-outline-danger btn-sm">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fa fa-inbox fa-2x mb-2 d-block"></i>
                                        Belum ada tulisan.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">Menampilkan {{ $posts->firstItem() ?? 0 }} - {{ $posts->lastItem() ?? 0 }} dari {{ $posts->total() }} data</small>
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL: Add/Edit Post --}}
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form wire:submit="save">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fa fa-{{ $editingPostId ? 'pencil' : 'plus' }} me-1"></i>
                            {{ $editingPostId ? 'Edit Tulisan' : 'Tambah Tulisan Baru' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
                            <input type="text" wire:model="post_title" class="form-control @error('post_title') is-invalid @enderror" placeholder="Masukkan judul tulisan">
                            @error('post_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Konten <span class="text-danger">*</span></label>
                            <textarea wire:model="post_content" class="form-control @error('post_content') is-invalid @enderror" rows="8" placeholder="Tulis konten di sini..."></textarea>
                            @error('post_content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Kategori</label>
                                <select wire:model="post_categories" class="form-select">
                                    <option value="">-- Pilih --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Status</label>
                                <select wire:model="post_status" class="form-select">
                                    <option value="publish">Publish</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Visibilitas</label>
                                <select wire:model="post_visibility" class="form-select">
                                    <option value="public">Public</option>
                                    <option value="private">Private</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Komentar</label>
                                <select wire:model="post_comment_status" class="form-select">
                                    <option value="open">Open</option>
                                    <option value="close">Close</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Tags</label>
                                <input type="text" wire:model="post_tags" class="form-control" placeholder="tag1, tag2">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Gambar</label>
                                <input type="file" wire:model="post_image" class="form-control @error('post_image') is-invalid @enderror">
                                @error('post_image') <div class="invalid-feedback">{{ $message }}</div> @enderror

                                <div wire:loading wire:target="post_image" class="mt-1">
                                    <small class="text-info"><i class="fa fa-spinner fa-spin"></i> Uploading...</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">
                            <i class="fa fa-times me-1"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="save"><i class="fa fa-save me-1"></i> Simpan</span>
                            <span wire:loading wire:target="save"><i class="fa fa-spinner fa-spin me-1"></i> Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
