<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fa fa-users me-2"></i>{{ $title }}</h5>
            <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm" placeholder="Cari nama..." style="width: 200px;">
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark"><tr><th style="width:50px">No</th><th>Nama Lengkap</th><th>No. Registrasi</th><th>Status</th><th>Tanggal</th></tr></thead>
                <tbody>
                    @forelse($registrants as $i => $row)
                    <tr>
                        <td>{{ $registrants->firstItem() + $i }}</td>
                        <td>{{ $row->full_name }}</td>
                        <td>{{ $row->registration_number }}</td>
                        <td><span class="badge bg-{{ $filter == 'approved' ? 'success' : 'danger' }}">{{ ucfirst($row->registration_status) }}</span></td>
                        <td>{{ $row->created_at ? $row->created_at->format('d M Y') : '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">{{ $registrants->firstItem() ?? 0 }} - {{ $registrants->lastItem() ?? 0 }} dari {{ $registrants->total() }}</small>
                {{ $registrants->links() }}
            </div>
        </div>
    </div>
</div>
