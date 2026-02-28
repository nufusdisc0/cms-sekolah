<div>
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h3 class="h4 mb-0 text-gray-800"><i class="fa fa-bullhorn me-2"></i> Manajemen Banner / Iklan</h3>
        <button class="btn btn-sm btn-primary shadow-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#bannerModal" wire:click="resetInputFields">
            <i class="fa fa-plus fa-sm me-1"></i> Tambah Banner
        </button>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle me-1"></i> {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Preview</th>
                            <th class="px-4 py-3 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Detail Banner</th>
                            <th class="px-4 py-3 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Posisi / Urutan</th>
                            <th class="px-4 py-3 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Status</th>
                            <th class="px-4 py-3 text-uppercase text-muted text-end" style="font-size: 0.75rem; letter-spacing: 1px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($banners as $banner)
                            <tr>
                                <td class="px-4 py-3">
                                    @if($banner->banner_image)
                                        <img src="{{ asset('storage/banners/' . $banner->banner_image) }}" alt="Banner" class="rounded shadow-sm" style="height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-light text-muted rounded d-flex align-items-center justify-content-center" style="width: 100px; height: 60px;">
                                            <i class="fa fa-image fa-2x"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="fw-bold text-dark mb-1">{{ $banner->banner_title ?: 'Tanpa Judul' }}</div>
                                    @if($banner->banner_url)
                                        <a href="{{ $banner->banner_url }}" target="_blank" class="text-primary small text-decoration-none">
                                            <i class="fa fa-external-link me-1"></i>{{ Str::limit($banner->banner_url, 30) }}
                                        </a>
                                    @else
                                        <span class="text-muted small">Tanpa Tautan</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="badge bg-secondary mb-1"><i class="fa fa-map-marker me-1"></i>{{ ucfirst(str_replace('_', ' ', $banner->banner_position)) }}</span>
                                    <div class="text-muted small">Urutan: <strong>{{ $banner->banner_order }}</strong></div>
                                </td>
                                <td class="px-4 py-3">
                                    @if($banner->status == 'Aktif')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="fa fa-check-circle me-1"></i>Aktif</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1"><i class="fa fa-times-circle me-1"></i>Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <button wire:click="edit({{ $banner->id }})" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#bannerModal">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button wire:click="delete({{ $banner->id }})" wire:confirm="Yakin ingin menghapus banner ini?" class="btn btn-sm btn-outline-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="bg-light rounded-circle d-inline-flex p-4 mb-3">
                                        <i class="fa fa-bullhorn fa-3x text-muted"></i>
                                    </div>
                                    <h5 class="text-muted mb-0">Belum ada banner/iklan.</h5>
                                    <p class="text-muted small">Klik tombol 'Tambah Banner' untuk menambahkan iklan baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-top">
                {{ $banners->links() }}
            </div>
        </div>
    </div>

    <!-- Banner Modal -->
    <div wire:ignore.self class="modal fade" id="bannerModal" tabindex="-1" aria-labelledby="bannerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light border-bottom-0">
                    <h5 class="modal-title fw-bold text-dark" id="bannerModalLabel">
                        <i class="fa fa-{{ $isEditMode ? 'edit' : 'plus-circle' }} text-primary me-2"></i>
                        {{ $isEditMode ? 'Edit Banner / Iklan' : 'Tambah Banner / Iklan' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="resetInputFields"></button>
                </div>
                <div class="modal-body p-4">
                    <form>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small">Gambar Banner / Iklan</label>
                            
                            @if ($banner_image)
                                <div class="mb-2">
                                    <img src="{{ $banner_image->temporaryUrl() }}" class="img-fluid rounded border shadow-sm" style="max-height: 150px;">
                                </div>
                            @elseif ($existing_banner_image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/banners/' . $existing_banner_image) }}" class="img-fluid rounded border shadow-sm" style="max-height: 150px;">
                                </div>
                            @endif

                            <input type="file" class="form-control" wire:model="banner_image" accept="image/*">
                            @error('banner_image') <span class="text-danger small">{{ $message }}</span> @enderror
                            <div class="form-text small text-muted"><i class="fa fa-info-circle me-1"></i>Format JPG/PNG, Maksimal 2MB.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small">Judul Banner (Opsional)</label>
                            <input type="text" class="form-control" wire:model="banner_title" placeholder="Contoh: Iklan Pendaftaran 2024">
                            @error('banner_title') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small">Tautan / URL Tujuan (Opsional)</label>
                            <input type="url" class="form-control" wire:model="banner_url" placeholder="https://example.com/promo">
                            @error('banner_url') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small">Posisi Banner</label>
                                <select class="form-select" wire:model="banner_position">
                                    <option value="dashboard_top">Dashboard (Atas)</option>
                                    <option value="dashboard_bottom">Dashboard (Bawah)</option>
                                    <option value="sidebar">Sidebar Publik</option>
                                </select>
                                @error('banner_position') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small">Urutan</label>
                                <input type="number" class="form-control" wire:model="banner_order" min="0">
                                @error('banner_order') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mb-1">
                            <label class="form-label fw-bold text-dark small">Status Tampil</label>
                            <select class="form-select" wire:model="status">
                                <option value="Aktif">Aktif (Tampilkan)</option>
                                <option value="Nonaktif">Nonaktif (Sembunyikan)</option>
                            </select>
                            @error('status') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="resetInputFields">Batal</button>
                    @if($isEditMode)
                        <button type="button" class="btn btn-primary px-4" wire:click.prevent="update" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="update"><i class="fa fa-save me-1"></i> Simpan Perubahan</span>
                            <span wire:loading wire:target="update"><i class="fa fa-spinner fa-spin me-1"></i> Menyimpan...</span>
                        </button>
                    @else
                        <button type="button" class="btn btn-primary px-4" wire:click.prevent="store" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="store"><i class="fa fa-save me-1"></i> Tambah Banner</span>
                            <span wire:loading wire:target="store"><i class="fa fa-spinner fa-spin me-1"></i> Menyimpan...</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Close Modal Script -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('close-modal', (event) => {
                var modalInstance = bootstrap.Modal.getInstance(document.getElementById('bannerModal'));
                if (modalInstance) {
                    modalInstance.hide();
                }
            });
        });
    </script>
</div>
