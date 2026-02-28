@extends('layouts.app')

@section('title', 'Hasil Pencarian' . ($query ? " - $query" : ''))

@section('content')
<div class="container py-5">
    <!-- Search Form -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto">
            <form action="{{ route('public.search') }}" method="GET" class="mb-4">
                <div class="input-group input-group-lg">
                    <input type="text" name="q" class="form-control"
                           placeholder="Cari artikel, halaman, siswa, karyawan..."
                           value="{{ $query }}"
                           autocomplete="off"
                           id="search-input">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search me-1"></i> Cari
                    </button>
                </div>
                <small class="text-muted d-block mt-2">
                    Cari artikel, halaman, kategori, siswa, alumni, dan karyawan
                </small>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            @if($error)
                <!-- Error Message -->
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ $error }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @elseif(empty($query))
                <!-- Empty Search -->
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Masukkan kata kunci untuk mencari artikel, halaman, siswa, alumni, atau karyawan.
                </div>
            @else
                <!-- Search Summary -->
                @php
                    $totalResults = $results['posts']->count() + $results['pages']->count() +
                                    $results['categories']->count() + $results['tags']->count() +
                                    $results['students']->count() + $results['alumni']->count() +
                                    $results['employees']->count();
                @endphp

                <h4 class="mb-4">
                    Hasil Pencarian <strong>"{{ $query }}"</strong>
                    <span class="badge bg-primary">{{ $totalResults }} hasil</span>
                </h4>

                @if($totalResults == 0)
                    <!-- No Results -->
                    <div class="alert alert-info mb-5">
                        <i class="fas fa-search me-2"></i>
                        Tidak ada hasil untuk "<strong>{{ $query }}</strong>". Coba gunakan kata kunci lain.
                    </div>
                @else
                    <!-- Posts Results -->
                    @if($results['posts']->count() > 0)
                        <div class="mb-5">
                            <h5 class="mb-3">
                                <i class="fas fa-file-text text-primary me-2"></i>Artikel
                                <span class="badge bg-light text-dark">{{ $results['posts']->count() }}</span>
                            </h5>

                            @foreach($results['posts'] as $post)
                                <div class="card border-0 shadow-sm mb-3 search-result-card">
                                    <div class="card-body">
                                        <div class="row">
                                            @if($post->post_image)
                                                <div class="col-md-3 mb-3 mb-md-0">
                                                    <img src="{{ asset('storage/' . $post->post_image) }}"
                                                         alt="{{ $post->post_title }}"
                                                         class="img-fluid rounded"
                                                         style="height: 150px; object-fit: cover; width: 100%;">
                                                </div>
                                                <div class="col-md-9">
                                            @else
                                                <div class="col-12">
                                            @endif
                                                    <h6 class="mb-1">
                                                        <a href="{{ route('public.post', $post->post_slug) }}"
                                                           class="text-decoration-none">
                                                            {{ $post->post_title }}
                                                        </a>
                                                    </h6>
                                                    <small class="text-muted d-block mb-2">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        {{ $post->created_at->format('d M Y') }}
                                                    </small>
                                                    <p class="small text-muted mb-0">
                                                        {{ Str::limit(strip_tags($post->post_content), 150, '...') }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Pages Results -->
                    @if($results['pages']->count() > 0)
                        <div class="mb-5">
                            <h5 class="mb-3">
                                <i class="fas fa-file text-success me-2"></i>Halaman
                                <span class="badge bg-light text-dark">{{ $results['pages']->count() }}</span>
                            </h5>

                            @foreach($results['pages'] as $page)
                                <div class="card border-0 shadow-sm mb-3 search-result-card">
                                    <div class="card-body">
                                        <h6 class="mb-1">
                                            <a href="{{ route('public.page', $page->post_slug) }}"
                                               class="text-decoration-none">
                                                {{ $page->post_title }}
                                            </a>
                                        </h6>
                                        <p class="small text-muted mb-0">
                                            {{ Str::limit(strip_tags($page->post_content), 200, '...') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Categories Results -->
                    @if($results['categories']->count() > 0)
                        <div class="mb-5">
                            <h5 class="mb-3">
                                <i class="fas fa-folder text-info me-2"></i>Kategori
                                <span class="badge bg-light text-dark">{{ $results['categories']->count() }}</span>
                            </h5>

                            <div class="row">
                                @foreach($results['categories'] as $category)
                                    <div class="col-md-6 mb-2">
                                        <a href="{{ route('public.category', $category->category_slug) }}"
                                           class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-folder-open me-1"></i>{{ $category->category_name }}
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Tags Results -->
                    @if($results['tags']->count() > 0)
                        <div class="mb-5">
                            <h5 class="mb-3">
                                <i class="fas fa-tag text-warning me-2"></i>Label
                                <span class="badge bg-light text-dark">{{ $results['tags']->count() }}</span>
                            </h5>

                            <div>
                                @foreach($results['tags'] as $tag)
                                    <a href="{{ route('public.tag', $tag->slug) }}"
                                       class="badge bg-warning text-dark me-2 mb-2">
                                        {{ $tag->tag }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Students Results -->
                    @if($results['students']->count() > 0)
                        <div class="mb-5">
                            <h5 class="mb-3">
                                <i class="fas fa-graduation-cap text-primary me-2"></i>Siswa
                                <span class="badge bg-light text-dark">{{ $results['students']->count() }}</span>
                            </h5>

                            <div class="row">
                                @foreach($results['students'] as $student)
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body text-center">
                                                @if($student->photo)
                                                    <img src="{{ asset('storage/' . $student->photo) }}"
                                                         alt="{{ $student->full_name }}"
                                                         class="rounded-circle mb-2"
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                                         style="width: 60px; height: 60px; background-color: #e9ecef;">
                                                        <i class="fas fa-user text-muted"></i>
                                                    </div>
                                                @endif
                                                <h6 class="mb-1">{{ $student->full_name }}</h6>
                                                <small class="text-muted d-block">NISN: {{ $student->nisn ?? '-' }}</small>
                                                <a href="{{ route('public.directory.student.profile', $student) }}"
                                                   class="btn btn-sm btn-outline-primary mt-2">
                                                    Lihat Profil
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Alumni Results -->
                    @if($results['alumni']->count() > 0)
                        <div class="mb-5">
                            <h5 class="mb-3">
                                <i class="fas fa-user-graduate text-success me-2"></i>Alumni
                                <span class="badge bg-light text-dark">{{ $results['alumni']->count() }}</span>
                            </h5>

                            <div class="row">
                                @foreach($results['alumni'] as $alumnus)
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body text-center">
                                                @if($alumnus->photo)
                                                    <img src="{{ asset('storage/' . $alumnus->photo) }}"
                                                         alt="{{ $alumnus->full_name }}"
                                                         class="rounded-circle mb-2"
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                                         style="width: 60px; height: 60px; background-color: #e9ecef;">
                                                        <i class="fas fa-user text-muted"></i>
                                                    </div>
                                                @endif
                                                <h6 class="mb-1">{{ $alumnus->full_name }}</h6>
                                                <small class="text-muted d-block">NISN: {{ $alumnus->nisn ?? '-' }}</small>
                                                <a href="{{ route('public.directory.alumni.profile', $alumnus) }}"
                                                   class="btn btn-sm btn-outline-success mt-2">
                                                    Lihat Profil
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Employees Results -->
                    @if($results['employees']->count() > 0)
                        <div class="mb-5">
                            <h5 class="mb-3">
                                <i class="fas fa-briefcase text-warning me-2"></i>Karyawan
                                <span class="badge bg-light text-dark">{{ $results['employees']->count() }}</span>
                            </h5>

                            <div class="row">
                                @foreach($results['employees'] as $employee)
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body text-center">
                                                @if($employee->photo)
                                                    <img src="{{ asset('storage/' . $employee->photo) }}"
                                                         alt="{{ $employee->full_name }}"
                                                         class="rounded-circle mb-2"
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                                         style="width: 60px; height: 60px; background-color: #e9ecef;">
                                                        <i class="fas fa-user text-muted"></i>
                                                    </div>
                                                @endif
                                                <h6 class="mb-1">{{ $employee->full_name }}</h6>
                                                <small class="text-muted d-block">NIK: {{ $employee->nik ?? '-' }}</small>
                                                <a href="{{ route('public.directory.employee.profile', $employee) }}"
                                                   class="btn btn-sm btn-outline-warning mt-2">
                                                    Lihat Profil
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Trending Searches -->
            @if(!empty($trending) && count($trending) > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-fire text-danger me-2"></i>Pencarian Trending
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($trending as $item)
                                <a href="{{ route('public.search', ['q' => $item['search_query']]) }}"
                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <span>{{ $item['search_query'] }}</span>
                                    <span class="badge bg-primary rounded-pill">{{ $item['count'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Search Tips -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb me-2"></i>Tips Pencarian
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="small mb-0 list-unstyled">
                        <li class="mb-2">✓ Gunakan kata kunci yang spesifik</li>
                        <li class="mb-2">✓ Coba gunakan sinonim jika hasil tidak sesuai</li>
                        <li class="mb-2">✓ Pencarian peka terhadap judul dan konten</li>
                        <li>✓ Telusuri kategori jika kesulitan menemukan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Autocomplete functionality
    const searchInput = document.getElementById('search-input');
    let autocompleteTimeout;

    searchInput?.addEventListener('input', function(e) {
        clearTimeout(autocompleteTimeout);
        const query = this.value.trim();

        if (query.length < 2) {
            // Hide autocomplete
            return;
        }

        autocompleteTimeout = setTimeout(() => {
            fetch(`{{ url('/api/search/autocomplete') }}?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    // Handle autocomplete results
                    console.log('Autocomplete results:', data);
                })
                .catch(error => console.error('Search error:', error));
        }, 300);
    });
</script>
@endpush

<style>
    .search-result-card {
        transition: box-shadow 0.3s ease;
    }

    .search-result-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .search-result-card a {
        color: inherit;
    }

    .search-result-card:hover a {
        color: #0d6efd;
    }
</style>
@endsection
