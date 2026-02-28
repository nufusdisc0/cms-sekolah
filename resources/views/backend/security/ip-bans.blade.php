@extends('layouts.backend')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>IP Ban Management</h2>
        <a href="{{ route('settings.index', 'security') }}" class="btn btn-outline-secondary">
            <i class="fas fa-cog me-2"></i>Security Settings
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter Tabs -->
    <div class="mb-4">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ $status === 'all' ? 'active' : '' }}" href="{{ route('backend.security.ip-bans', ['status' => 'all']) }}">
                    All <span class="badge bg-secondary">{{ $bans->total() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status === 'active' ? 'active' : '' }}" href="{{ route('backend.security.ip-bans', ['status' => 'active']) }}">
                    Active Bans
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status === 'expired' ? 'active' : '' }}" href="{{ route('backend.security.ip-bans', ['status' => 'expired']) }}">
                    Expired
                </a>
            </li>
        </ul>
    </div>

    <!-- IP Bans Table -->
    @if($bans->count() > 0)
        <div class="table-responsive card">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>IP Address</th>
                        <th>Failed Attempts</th>
                        <th>Status</th>
                        <th>Ban Duration</th>
                        <th>Last Attempt</th>
                        <th>Reason</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bans as $ban)
                        <tr>
                            <td>
                                <code>{{ $ban->ip_address }}</code>
                            </td>
                            <td>
                                <span class="badge bg-{{ $ban->failed_attempts >= 3 ? 'danger' : 'warning' }}">
                                    {{ $ban->failed_attempts }} attempts
                                </span>
                            </td>
                            <td>
                                @if($ban->isBanActive())
                                    <span class="badge bg-danger">
                                        <i class="fas fa-ban me-1"></i>Banned
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>Released
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($ban->banned_until)
                                    <small class="text-muted">
                                        Until {{ $ban->banned_until->format('M d, Y H:i') }}
                                        @php
                                            $hoursLeft = $ban->hoursUntilRelease();
                                            if ($hoursLeft !== null && $hoursLeft > 0) {
                                                echo '<br><strong class="text-danger">' . round($hoursLeft) . ' hours left</strong>';
                                            }
                                        @endphp
                                    </small>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $ban->last_attempt_at ? $ban->last_attempt_at->format('M d, Y H:i') : 'N/A' }}
                                </small>
                            </td>
                            <td>
                                <small>{{ $ban->reason ?? '-' }}</small>
                            </td>
                            <td>
                                @if($ban->isBanActive())
                                    <form action="{{ route('backend.security.release-ban', $ban) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success"
                                                onclick="return confirm('Release ban for {{ $ban->ip_address }}?')">
                                            <i class="fas fa-unlock me-1"></i>Release
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('backend.security.delete-ban', $ban) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-secondary"
                                                onclick="return confirm('Delete this ban record?')">
                                            <i class="fas fa-trash me-1"></i>Delete
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-info-circle me-2"></i>No IP bans found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $bans->links() }}
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-check-circle me-2"></i>
            No IP bans to display. The system is operating normally.
        </div>
    @endif

    <!-- Ban Statistics -->
    <div class="row mt-5">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Active Bans</h5>
                    <h2 class="text-danger">
                        {{ \App\Models\IPBan::whereNotNull('banned_until')->where('banned_until', '>', now())->count() }}
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Tracked IPs</h5>
                    <h2 class="text-primary">
                        {{ \App\Models\IPBan::count() }}
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Bans This Month</h5>
                    <h2 class="text-warning">
                        {{ \App\Models\IPBan::whereNotNull('banned_until')->where('created_at', '>=', now()->subMonth())->count() }}
                    </h2>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
