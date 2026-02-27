<div>
    <div class="card">
        <div class="card-header"><h5 class="mb-0"><i class="fa fa-paint-brush me-2"></i>Tema</h5></div>
        <div class="card-body">
            @if(session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show"><i class="fa fa-check-circle me-1"></i> {{ session('message') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            <div class="row">
                @forelse($themes as $theme)
                <div class="col-md-4 mb-3" wire:key="theme-{{ $theme->id }}">
                    <div class="card {{ $theme->theme_is_active == 'true' ? 'border-primary border-2' : '' }}">
                        <div class="card-body text-center">
                            <h6 class="card-title fw-semibold">{{ $theme->theme_name }}</h6>
                            <p class="text-muted small">{{ $theme->theme_folder ?? '-' }}</p>
                            @if($theme->theme_is_active == 'true')
                                <span class="badge bg-success"><i class="fa fa-check"></i> Aktif</span>
                            @elseif($confirmingActivateId === $theme->id)
                                <button wire:click="activate({{ $theme->id }})" class="btn btn-success btn-sm">Aktifkan</button>
                                <button wire:click="cancelActivate" class="btn btn-secondary btn-sm">Batal</button>
                            @else
                                <button wire:click="confirmActivate({{ $theme->id }})" class="btn btn-outline-primary btn-sm">Gunakan Tema</button>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center text-muted py-4">Belum ada tema.</div>
                @endforelse
            </div>
            {{ $themes->links() }}
        </div>
    </div>
</div>
