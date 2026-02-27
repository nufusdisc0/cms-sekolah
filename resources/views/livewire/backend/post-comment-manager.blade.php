<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fa fa-comments me-2"></i>Komentar</h5>
            <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm" placeholder="Cari..." style="width: 200px;">
        </div>
        <div class="card-body">
            @if(session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show"><i class="fa fa-check-circle me-1"></i> {{ session('message') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark"><tr><th style="width:50px">No</th><th>Komentar</th><th>Status</th><th>Tanggal</th><th style="width:160px">Aksi</th></tr></thead>
                <tbody>
                    @forelse($comments as $i => $row)
                    <tr wire:key="cmt-{{ $row->id }}">
                        <td>{{ $comments->firstItem() + $i }}</td>
                        <td>{{ Str::limit($row->comment_content, 80) }}</td>
                        <td><span class="badge bg-{{ $row->comment_status == 'approved' ? 'success' : ($row->comment_status == 'spam' ? 'danger' : 'warning') }}">{{ ucfirst($row->comment_status) }}</span></td>
                        <td>{{ $row->created_at ? $row->created_at->format('d M Y') : '-' }}</td>
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
                    <tr><td colspan="5" class="text-center text-muted py-3">Belum ada komentar.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">{{ $comments->firstItem() ?? 0 }} - {{ $comments->lastItem() ?? 0 }} dari {{ $comments->total() }}</small>
                {{ $comments->links() }}
            </div>
        </div>
    </div>
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg"><div class="modal-content">
            <form wire:submit="save">
                <div class="modal-header"><h5 class="modal-title">Edit Komentar</h5><button type="button" class="btn-close" wire:click="closeModal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label fw-semibold">Komentar</label><textarea wire:model="comment_content" class="form-control" rows="4"></textarea></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Balasan</label><textarea wire:model="comment_reply" class="form-control" rows="3"></textarea></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Status</label><select wire:model="comment_status" class="form-select"><option value="pending">Pending</option><option value="approved">Approved</option><option value="spam">Spam</option></select></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" wire:click="closeModal">Batal</button><button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Simpan</button></div>
            </form>
        </div></div>
    </div>
    @endif
</div>
