<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fa fa-bars me-2"></i>Menu</h5>
            <button wire:click="openModal" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Menu</button>
        </div>
        <div class="card-body">
            @if(session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show"><i class="fa fa-check-circle me-1"></i> {{ session('message') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark"><tr><th style="width:50px">No</th><th>Judul Menu</th><th>URL</th><th>Target</th><th>Tipe</th><th style="width:160px">Aksi</th></tr></thead>
                <tbody>
                    @forelse($menus as $i => $row)
                    <tr wire:key="menu-{{ $row->id }}">
                        <td>{{ $menus->firstItem() + $i }}</td>
                        <td>{{ $row->menu_title }}</td>
                        <td><code>{{ $row->menu_url }}</code></td>
                        <td>{{ $row->menu_target }}</td>
                        <td><span class="badge bg-info">{{ $row->menu_type }}</span></td>
                        <td>
                            <button wire:click="edit({{ $row->id }})" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i></button>
                            @if($confirmingDeleteId === $row->id)
                                <button wire:click="delete({{ $row->id }})" class="btn btn-danger btn-sm">Ya</button>
                                <button wire:click="cancelDelete" class="btn btn-secondary btn-sm">Batal</button>
                            @else
                                <button wire:click="confirmDelete({{ $row->id }})" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-3">Belum ada menu.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $menus->links() }}
        </div>
    </div>
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog"><div class="modal-content">
            <form wire:submit="save">
                <div class="modal-header"><h5 class="modal-title">{{ $editingId ? 'Edit' : 'Tambah' }} Menu</h5><button type="button" class="btn-close" wire:click="closeModal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label fw-semibold">Judul Menu <span class="text-danger">*</span></label><input type="text" wire:model="menu_title" class="form-control @error('menu_title') is-invalid @enderror">@error('menu_title') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                    <div class="mb-3"><label class="form-label fw-semibold">URL <span class="text-danger">*</span></label><input type="text" wire:model="menu_url" class="form-control @error('menu_url') is-invalid @enderror" placeholder="https://...">@error('menu_url') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label fw-semibold">Target</label><select wire:model="menu_target" class="form-select"><option value="_self">Self</option><option value="_blank">Blank (Tab Baru)</option></select></div>
                        <div class="col-md-6 mb-3"><label class="form-label fw-semibold">Tipe</label><select wire:model="menu_type" class="form-select"><option value="links">Links</option><option value="pages">Pages</option><option value="post_categories">Post Categories</option><option value="modules">Modules</option></select></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" wire:click="closeModal">Batal</button><button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Simpan</button></div>
            </form>
        </div></div>
    </div>
    @endif
</div>
