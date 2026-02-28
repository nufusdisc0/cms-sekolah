@extends('layouts.backend')

@section('content')
<style>
    /* Modern Dashboard Aesthetics */
    .dashboard-header {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%) !important;
        border-radius: 15px;
        padding: 2.5rem 2rem;
        color: white !important;
        margin-top: -1rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(78, 115, 223, 0.2);
        position: relative;
        overflow: hidden;
    }
    .dashboard-header h2 {
        color: white !important;
    }
    .dashboard-header p {
        color: rgba(255,255,255,0.8) !important;
    }
    .dashboard-header::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
    }
    
    .stat-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        overflow: hidden;
        position: relative;
        z-index: 1;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.08);
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(120deg, rgba(255,255,255,0.8) 0%, rgba(255,255,255,0) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: -1;
    }
    
    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-icon-wrapper {
        width: 55px;
        height: 55px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        color: #2c3e50;
        line-height: 1;
        margin-bottom: 0.25rem;
        font-family: 'Inter', 'Segoe UI', sans-serif;
    }

    .stat-label {
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #858796;
    }

    .stat-stripe {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 5px;
        transition: height 0.3s ease;
    }

    .stat-card:hover .stat-stripe {
        height: 8px;
    }

    /* Gradients for cards */
    .bg-gradient-primary-light { background: linear-gradient(135deg, #4e73df 0%, #36b9cc 100%); color: white; }
    .bg-gradient-success-light { background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); color: white; }
    .bg-gradient-info-light { background: linear-gradient(135deg, #36b9cc 0%, #258391 100%); color: white; }
    .bg-gradient-warning-light { background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%); color: white; }
    .bg-gradient-danger-light { background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%); color: white; }
    .bg-gradient-secondary-light { background: linear-gradient(135deg, #858796 0%, #60616f 100%); color: white; }
    .bg-gradient-dark-light { background: linear-gradient(135deg, #5a5c69 0%, #373840 100%); color: white; }
    .bg-gradient-purple-light { background: linear-gradient(135deg, #6f42c1 0%, #512da8 100%); color: white; }
    .bg-gradient-teal-light { background: linear-gradient(135deg, #20c997 0%, #128c68 100%); color: white; }

    /* Recent Activity Section */
    .activity-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 5px 20px rgba(0,0,0,0.04);
    }
    
    .activity-header {
        background: transparent;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 1.5rem;
    }

    .activity-item {
        padding: 1rem 1.5rem;
        border-left: 3px solid transparent;
        transition: all 0.2s ease;
    }

    .activity-item:hover {
        background: #f8f9fc;
        border-left-color: #4e73df;
    }
</style>

<!-- Welcome Header -->
<div class="dashboard-header d-flex justify-content-between align-items-center">
    <div>
        <h2 class="fw-bold mb-1">Selamat Datang, {{ Auth::user()->user_full_name }}! 👋</h2>
        <p class="mb-0 text-white-50">Berikut adalah ringkasan aktivitas situs Anda hari ini.</p>
    </div>
    <a href="{{ url('/') }}" target="_blank" class="btn btn-light rounded-pill px-4 fw-bold shadow-sm" style="color: #4e73df;">
        <i class="fa fa-globe me-2"></i> Lihat Situs
    </a>
</div>

<!-- Statistics Grid -->
<div class="row g-4 mb-5">
    
    <!-- Messages -->
    <div class="col-xl-4 col-md-6">
        <div class="stat-card p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $widget_box->messages }}</div>
                    <div class="stat-label">Pesan Masuk</div>
                </div>
                <div class="stat-icon-wrapper bg-gradient-primary-light">
                    <i class="fa fa-envelope"></i>
                </div>
            </div>
            <div class="stat-stripe bg-primary"></div>
        </div>
    </div>

    <!-- Comments -->
    <div class="col-xl-4 col-md-6">
        <div class="stat-card p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $widget_box->comments }}</div>
                    <div class="stat-label">Komentar</div>
                </div>
                <div class="stat-icon-wrapper bg-gradient-success-light">
                    <i class="fa fa-comments"></i>
                </div>
            </div>
            <div class="stat-stripe bg-success"></div>
        </div>
    </div>

    <!-- Posts -->
    <div class="col-xl-4 col-md-6">
        <div class="stat-card p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $widget_box->posts }}</div>
                    <div class="stat-label">Tulisan Berita</div>
                </div>
                <div class="stat-icon-wrapper bg-gradient-info-light">
                    <i class="fa fa-newspaper-o"></i>
                </div>
            </div>
            <div class="stat-stripe bg-info"></div>
        </div>
    </div>

    <!-- Pages -->
    <div class="col-xl-4 col-md-6">
        <div class="stat-card p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $widget_box->pages }}</div>
                    <div class="stat-label">Halaman Statis</div>
                </div>
                <div class="stat-icon-wrapper bg-gradient-warning-light">
                    <i class="fa fa-file-text"></i>
                </div>
            </div>
            <div class="stat-stripe bg-warning"></div>
        </div>
    </div>

    <!-- Categories -->
    <div class="col-xl-4 col-md-6">
        <div class="stat-card p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $widget_box->categories }}</div>
                    <div class="stat-label">Kategori</div>
                </div>
                <div class="stat-icon-wrapper bg-gradient-danger-light">
                    <i class="fa fa-tags"></i>
                </div>
            </div>
            <div class="stat-stripe bg-danger"></div>
        </div>
    </div>

    <!-- Tags -->
    <div class="col-xl-4 col-md-6">
        <div class="stat-card p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $widget_box->tags }}</div>
                    <div class="stat-label">Tags</div>
                </div>
                <div class="stat-icon-wrapper bg-gradient-secondary-light">
                    <i class="fa fa-hashtag"></i>
                </div>
            </div>
            <div class="stat-stripe bg-secondary"></div>
        </div>
    </div>

    <!-- Links -->
    <div class="col-xl-4 col-md-6">
        <div class="stat-card p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $widget_box->links }}</div>
                    <div class="stat-label">Tautan Link</div>
                </div>
                <div class="stat-icon-wrapper bg-gradient-dark-light">
                    <i class="fa fa-link"></i>
                </div>
            </div>
            <div class="stat-stripe bg-dark"></div>
        </div>
    </div>

    <!-- Quotes -->
    <div class="col-xl-4 col-md-6">
        <div class="stat-card p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $widget_box->quotes }}</div>
                    <div class="stat-label">Kutipan</div>
                </div>
                <div class="stat-icon-wrapper bg-gradient-purple-light">
                    <i class="fa fa-quote-left"></i>
                </div>
            </div>
            <div class="stat-stripe" style="background-color:#6f42c1;"></div>
        </div>
    </div>

    <!-- Banners -->
    <div class="col-xl-4 col-md-6">
        <div class="stat-card p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $widget_box->banners }}</div>
                    <div class="stat-label">Banner & Iklan</div>
                </div>
                <div class="stat-icon-wrapper bg-gradient-teal-light">
                    <i class="fa fa-image"></i>
                </div>
            </div>
            <div class="stat-stripe" style="background-color:#20c997;"></div>
        </div>
    </div>

</div>

<!-- Recent Activity & Quick Actions -->
<div class="row g-4 mb-4">
    <!-- Recent Comments -->
    <div class="col-lg-8">
        <div class="card activity-card h-100">
            <div class="card-header activity-header d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-dark mb-0"><i class="fa fa-comments-o text-success me-2"></i>Komentar Terbaru</h6>
                @if(count($recent_comments) > 0)
                <a href="{{ route('backend.post_comments_live') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Lihat Semua</a>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush rounded-bottom">
                    @forelse($recent_comments as $comment)
                        <div class="activity-item border-bottom">
                            <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                                <strong class="text-dark"><i class="fa fa-user-circle text-muted me-2"></i>{{ $comment->comment_author }}</strong>
                                <span class="badge bg-light text-dark border"><i class="fa fa-clock-o me-1"></i>{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="mb-0 text-muted" style="line-height: 1.5; font-size: 0.95rem;">"{{ Str::limit($comment->comment_content, 120) }}"</p>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="bg-light rounded-circle d-inline-flex p-4 mb-3">
                                <i class="fa fa-inbox fa-2x text-muted"></i>
                            </div>
                            <h6 class="text-muted">Belum ada komentar terbaru.</h6>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    
    <!-- Active Banners -->
    <div class="col-lg-4">
        <div class="card activity-card h-100">
            <div class="card-header activity-header d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-dark mb-0"><i class="fa fa-image text-teal me-2"></i>Banner Aktif</h6>
                <a href="{{ route('backend.banners.index') }}" class="btn btn-sm btn-outline-teal rounded-pill px-3" style="color: #20c997; border-color: #20c997;">Kelola</a>
            </div>
            <div class="card-body p-3">
                @forelse($active_banners as $banner)
                    <div class="mb-3">
                        <a href="{{ $banner->banner_url }}" target="_blank" title="{{ $banner->banner_title }}" class="d-block rounded overflow-hidden shadow-sm" style="border: 1px solid rgba(0,0,0,0.05); transition: transform 0.2s;">
                            @if($banner->banner_image)
                                <img src="{{ asset('storage/banners/' . $banner->banner_image) }}" class="img-fluid w-100" alt="{{ $banner->banner_title }}" style="object-fit: cover; max-height: 120px;">
                            @else
                                <div class="bg-light d-flex justify-content-center align-items-center w-100" style="height: 100px;">
                                    <i class="fa fa-image fa-2x text-muted"></i>
                                </div>
                            @endif
                        </a>
                        @if($banner->banner_title)
                            <div class="text-center mt-1 small text-muted fw-bold">{{ Str::limit($banner->banner_title, 30) }}</div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-4">
                        <div class="bg-light rounded-circle d-inline-flex p-3 mb-2">
                            <i class="fa fa-image fa-2x text-muted"></i>
                        </div>
                        <h6 class="text-muted small">Belum ada banner aktif.</h6>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
