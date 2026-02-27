<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fa fa-envelope me-2"></i>Pesan Masuk</h5>
            <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm" placeholder="Cari..." style="width: 200px;">
        </div>
        <div class="card-body">
            @if(session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show"><i class="fa fa-check-circle me-1"></i> {{ session('message') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark"><tr><th style="width:50px">No</th><th>Pengirim</th><th>Pesan</th><th>Tanggal</th><th style="width:160px">Aksi</th></tr></thead>
                <tbody>
                    @forelse($messages as $i => $row)
                    <tr wire:key="msg-{{ $row->id }}">
                        <td>{{ $messages->firstItem() + $i }}</td>
                        <td>{{ $row->comment_author }}</td>
                        <td>{{ Str::limit($row->comment_content, 50) }}</td>
                        <td>{{ $row->created_at ? $row->created_at->format('d M Y') : '-' }}</td>
                        <td>
                            <button wire:click="view({{ $row->id }})" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Lihat</button>
                            @if($confirmingDeleteId === $row->id)
                                <button wire:click="delete({{ $row->id }})" class="btn btn-danger btn-sm">Ya</button>
                                <button wire:click="cancelDelete" class="btn btn-secondary btn-sm">Batal</button>
                            @else
                                <button wire:click="confirmDelete({{ $row->id }})" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">Belum ada pesan masuk.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">{{ $messages->firstItem() ?? 0 }} - {{ $messages->lastItem() ?? 0 }} dari {{ $messages->total() }}</small>
                {{ $messages->links() }}
            </div>
        </div>
    </div>
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg"><div class="modal-content">
            <form wire:submit="reply">
                <div class="modal-header"><h5 class="modal-title">Balas Pesan</h5><button type="button" class="btn-close" wire:click="closeModal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label fw-semibold">Subject</label><input type="text" wire:model="comment_subject" class="form-control @error('comment_subject') is-invalid @enderror">@error('comment_subject') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                    <div class="mb-3"><label class="form-label fw-semibold">Balasan</label><textarea wire:model="comment_reply" class="form-control @error('comment_reply') is-invalid @enderror" rows="5"></textarea>@error('comment_reply') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" wire:click="closeModal">Batal</button><button type="submit" class="btn btn-primary"><i class="fa fa-send me-1"></i> Kirim Balasan</button></div>
            </form>
        </div></div>
    </div>
    @endif
</div>
