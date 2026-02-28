@extends('layouts.admin')

@section('title', 'Detail Seleksi - ' . $selection->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">{{ $selection->name }}</h1>
            <p class="text-muted small">{{ $selection->admissionPhase->name }} • Dibuat: {{ $selection->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div class="col-md-4 text-end">
            @if ($selection->canStartSelection())
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#executeModal">
                    <i class="fas fa-play me-1"></i> Mulai Seleksi
                </button>
            @elseif ($selection->status === 'completed' && !$selection->results_announced_at)
                <form action="{{ route('backend.selection.announce-results', $selection) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Umumkan hasil seleksi?');">
                        <i class="fas fa-bullhorn me-1"></i> Umumkan Hasil
                    </button>
                </form>
            @endif
            <a href="{{ route('backend.selection.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </div>

    <!-- Alerts -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Content Row -->
    <div class="row">
        <!-- Left Column: Details & Statistics -->
        <div class="col-lg-8">
            <!-- Selection Info Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Informasi Seleksi</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-sm-8">
                            @switch($selection->status)
                                @case('draft')
                                    <span class="badge bg-secondary">Draft</span>
                                    @break
                                @case('in_progress')
                                    <span class="badge bg-warning">Sedang Berjalan</span>
                                    @break
                                @case('completed')
                                    <span class="badge bg-info">Selesai</span>
                                    @break
                                @case('announced')
                                    <span class="badge bg-success">Diumumkan</span>
                                    @break
                            @endswitch
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Total Kuota:</strong></div>
                        <div class="col-sm-8">{{ $selection->total_quota }} tempat</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Metode Ranking:</strong></div>
                        <div class="col-sm-8">{{ ucfirst(str_replace('_', ' ', $selection->ranking_method)) }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Metode Pilihan:</strong></div>
                        <div class="col-sm-8">{{ ucfirst(str_replace('_', ' ', $selection->choice_method)) }}</div>
                    </div>

                    @if ($selection->description)
                        <div class="row">
                            <div class="col-sm-4"><strong>Deskripsi:</strong></div>
                            <div class="col-sm-8">{{ $selection->description }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Statistik Seleksi</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h4 class="text-primary">{{ $statistics['total_processed'] }}</h4>
                            <p class="text-muted small mb-0">Diproses</p>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-success">{{ $statistics['passed'] }}</h4>
                            <p class="text-muted small mb-0">Diterima</p>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-danger">{{ $statistics['failed'] }}</h4>
                            <p class="text-muted small mb-0">Ditolak</p>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-warning">{{ $statistics['pending'] }}</h4>
                            <p class="text-muted small mb-0">Menunggu</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <p class="small text-muted mb-1"><strong>Nilai Rata-rata:</strong> {{ $statistics['average_score'] }}</p>
                            <p class="small text-muted mb-1"><strong>Nilai Tertinggi:</strong> {{ $statistics['highest_score'] }}</p>
                            <p class="small text-muted"><strong>Nilai Terendah:</strong> {{ $statistics['lowest_score'] }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="small text-muted mb-1">
                                <strong>Coverage Kuota:</strong> {{ $statistics['quota_coverage'] }}%
                            </p>
                            <p class="small text-muted">
                                <strong>Progres Pemrosesan:</strong> {{ $statistics['progress'] }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results by Major -->
            @if (!$resultsByMajor->isEmpty())
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Hasil Per Jurusan</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Jurusan</th>
                                        <th class="text-end">Total</th>
                                        <th class="text-end">Diterima</th>
                                        <th class="text-end">Ditolak</th>
                                        <th class="text-end">Nilai Rata-rata</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($resultsByMajor as $major => $stats)
                                        <tr>
                                            <td>{{ $major }}</td>
                                            <td class="text-end">{{ $stats['total'] }}</td>
                                            <td class="text-end"><span class="badge bg-success">{{ $stats['passed'] }}</span></td>
                                            <td class="text-end"><span class="badge bg-danger">{{ $stats['failed'] }}</span></td>
                                            <td class="text-end">{{ $stats['average_score'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Belum ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Actions & Timeline -->
        <div class="col-lg-4">
            <!-- Action Buttons -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Aksi</h5>
                </div>
                <div class="card-body d-grid gap-2">
                    @if ($selection->status === 'draft')
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#executeModal">
                            <i class="fas fa-play me-2"></i> Mulai Seleksi
                        </button>
                        <a href="{{ route('backend.selection.edit', $selection) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-edit me-2"></i> Edit Seleksi
                        </a>
                    @elseif ($selection->status === 'completed')
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#announceModal">
                            <i class="fas fa-bullhorn me-2"></i> Umumkan Hasil
                        </button>
                    @endif

                    @if ($selection->status !== 'draft')
                        <a href="{{ route('backend.selection.results', $selection) }}" class="btn btn-outline-primary">
                            <i class="fas fa-list me-2"></i> Lihat Hasil
                        </a>
                        <a href="{{ route('backend.selection.export', $selection) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-download me-2"></i> Ekspor CSV
                        </a>
                    @endif

                    @if ($selection->status === 'announced')
                        <form action="{{ route('backend.selection.rollback', $selection) }}" method="POST" style="display:inline;" onsubmit="return confirm('Kembali ke status sebelumnya?');">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-undo me-2"></i> Batalkan Pengumuman
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Timeline -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker {{ $selection->status !== 'draft' ? 'bg-success' : 'bg-secondary' }}"></div>
                            <h6 class="mb-1">Dibuat</h6>
                            <p class="text-muted small mb-3">{{ $selection->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        @if ($selection->selection_started_at)
                            <div class="timeline-item">
                                <div class="timeline-marker {{ !in_array($selection->status, ['draft']) ? 'bg-success' : 'bg-secondary' }}"></div>
                                <h6 class="mb-1">Dimulai</h6>
                                <p class="text-muted small mb-3">{{ $selection->selection_started_at->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif

                        @if ($selection->selection_completed_at)
                            <div class="timeline-item">
                                <div class="timeline-marker {{ !in_array($selection->status, ['draft', 'in_progress']) ? 'bg-success' : 'bg-secondary' }}"></div>
                                <h6 class="mb-1">Selesai</h6>
                                <p class="text-muted small mb-3">{{ $selection->selection_completed_at->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif

                        @if ($selection->results_announced_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <h6 class="mb-1">Diumumkan</h6>
                                <p class="text-muted small">{{ $selection->results_announced_at->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Execute Selection Modal -->
<div class="modal fade" id="executeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mulai Proses Seleksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Anda akan memulai proses seleksi untuk <strong>{{ $selection->name }}</strong>.</p>
                <ul class="small text-muted">
                    <li>Metode ranking: {{ ucfirst(str_replace('_', ' ', $selection->ranking_method)) }}</li>
                    <li>Total kuota: {{ $selection->total_quota }}</li>
                    <li>Peserta: {{ $selection->admissionPhase->registrants()->submitted()->count() }}</li>
                </ul>
                <p class="text-warning small"><strong>Catatan:</strong> Proses ini tidak dapat dibatalkan setelah dimulai.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('backend.selection.execute', $selection) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">Mulai Seleksi</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Announce Results Modal -->
<div class="modal fade" id="announceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Umumkan Hasil Seleksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Anda akan mengumumkan hasil seleksi kepada semua peserta.</p>
                <p class="text-info small"><i class="fas fa-info-circle me-2"></i>Email notifikasi akan dikirim kepada semua peserta.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('backend.selection.announce-results', $selection) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary">Umumkan Hasil</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
}

.timeline-item {
    position: relative;
    padding-left: 30px;
    margin-bottom: 20px;
}

.timeline-item:before {
    content: '';
    position: absolute;
    left: 5px;
    top: 30px;
    width: 2px;
    height: 20px;
    background: #dee2e6;
}

.timeline-item:last-child:before {
    display: none;
}

.timeline-marker {
    position: absolute;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    left: 0;
    top: 5px;
    border: 2px solid white;
}
</style>
@endsection
