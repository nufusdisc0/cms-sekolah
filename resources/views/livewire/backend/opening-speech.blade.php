<div>
    <div class="card">
        <div class="card-header"><h5 class="mb-0"><i class="fa fa-microphone me-2"></i>Sambutan Kepala Sekolah</h5></div>
        <div class="card-body">
            @if(session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show"><i class="fa fa-check-circle me-1"></i> {{ session('message') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            <form wire:submit="save">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Isi Sambutan <span class="text-danger">*</span></label>
                    <textarea wire:model="post_content" class="form-control @error('post_content') is-invalid @enderror" rows="12" placeholder="Tulis sambutan kepala sekolah di sini..."></textarea>
                    @error('post_content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save"><i class="fa fa-save me-1"></i> Simpan</span>
                    <span wire:loading wire:target="save"><i class="fa fa-spinner fa-spin me-1"></i> Menyimpan...</span>
                </button>
            </form>
        </div>
    </div>
</div>
