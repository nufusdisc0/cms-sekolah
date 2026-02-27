<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fa fa-list me-2"></i>{{ $title }}</h5>
            <div class="d-flex gap-2 align-items-center">
                <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm" placeholder="Cari..." style="width: 200px;">
                <button wire:click="openModal" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah</button>
            </div>
        </div>
        <div class="card-body">
            @if(session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark"><tr><th style="width:50px">No</th><th>{{ $fieldLabel }}</th><th style="width:160px">Aksi</th></tr></thead>
                <tbody>
                    @forelse($items as $i => $row)
                    <tr wire:key="opt-{{ $row->id }}">
                        <td>{{ $items->firstItem() + $i }}</td>
                        <td>{{ $row->option_name }}</td>
                        <td>
                            <button wire:click="edit({{ $row->id }})" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i> Edit</button>
                            @if($confirmingDeleteId === $row->id)
                                <button wire:click="delete({{ $row->id }})" class="btn btn-danger btn-sm">Ya</button>
                                <button wire:click="cancelDelete" class="btn btn-secondary btn-sm">Batal</button>
                            @else
                                <button wire:click="confirmDelete({{ $row->id }})" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center text-muted py-3">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">{{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} dari {{ $items->total() }}</small>
                {{ $items->links() }}
            </div>
        </div>
    </div>
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog"><div class="modal-content">
            <form wire:submit="save">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editingId ? 'Edit' : 'Tambah' }} {{ $title }}</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ $fieldLabel }} <span class="text-danger">*</span></label>
                        <input type="text" wire:model="option_name" class="form-control @error('option_name') is-invalid @enderror">
                        @error('option_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Simpan</button>
                </div>
            </form>
        </div></div>
    </div>
    @endif
</div>
