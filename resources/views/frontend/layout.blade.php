<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AnimeNews')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mystery-theme.css') }}">
    
    <style>
        /* ===== THE MYSTERY PALETTE ===== */
        :root {
            --primary: #1A237E;
            --secondary: #424242;
            --accent: #FFC107;
            --background: #FFFFFF;
            --dark-bg: #000033;
        }

        /* ===== FRONTEND STYLING ===== */

        /* Navbar & Header */
        .navbar.bg-dark {
            background-color: var(--primary) !important;
            border-bottom: 2px solid var(--accent);
        }

        .navbar .nav-link {
            color: white !important;
            transition: all 0.3s ease;
        }

        .navbar .nav-link:hover,
        .navbar .nav-link.active {
            color: var(--accent) !important;
            position: relative;
        }

        .navbar .nav-link:hover::after,
        .navbar .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 10%;
            width: 80%;
            height: 2px;
            background-color: var(--accent);
        }

        .navbar .form-control {
            border: 1px solid var(--accent);
        }

        .navbar .btn-outline-light {
            border-color: var(--accent);
            color: var(--accent);
        }

        .navbar .btn-outline-light:hover {
            background-color: var(--accent);
            color: var(--primary);
        }

        /* Kartu Berita */
        .news-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            transition: all 0.3s ease;
            background: var(--background);
            margin-bottom: 1.5rem;
        }

        .news-card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            transform: translateY(-3px);
        }

        .thumbnail-container {
            height: 200px;
            overflow: hidden;
        }

        .thumbnail-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
            filter: grayscale(20%);
        }

        .news-card:hover .thumbnail-container img {
            filter: grayscale(0%);
            transform: scale(1.05);
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--dark-bg) 100%);
            color: white;
            padding: 80px 0;
            position: relative;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .footer {
            background: var(--dark-bg);
            color: white;
            padding: 40px 0;
            margin-top: 3rem;
        }

        .badge-news {
            font-size: 0.75em;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            background-color: var(--primary) !important;
            color: white;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        /* Hasil Pencarian & Peringatan */
        .alert-info {
            background-color: rgba(26, 35, 126, 0.1);
            border-color: var(--primary);
            color: var(--primary);
        }

        /* Sidebar Widgets */
        .card .card-header.bg-primary {
            background: linear-gradient(135deg, var(--primary) 0%, #303F9F 100%) !important;
            border-bottom: 2px solid var(--accent);
        }

        .card .card-header.bg-success {
            background: linear-gradient(135deg, #1B5E20 0%, #2E7D32 100%) !important;
            border-bottom: 2px solid var(--accent);
        }

        .card .card-header.bg-warning {
            background: linear-gradient(135deg, var(--accent) 0%, #FFA000 100%) !important;
            color: var(--secondary) !important;
            border-bottom: 2px solid var(--primary);
        }

        /* Footer Links */
        .footer a {
            color: #CCCCCC;
            text-decoration: none;
        }
        .footer a:hover {
            color: var(--accent);
        }

        /* Perbaikan grid system */
        .container {
            max-width: 1200px;
        }

        .row {
            margin-left: -0.75rem;
            margin-right: -0.75rem;
        }

        .row > [class*="col-"] {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        
        /* Perbaikan card layout */
        .card {
            border: 1px solid rgba(0,0,0,0.125);
            border-radius: 0.5rem;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary) 0%, #303F9F 100%);
            border-bottom: 2px solid var(--accent);
            font-weight: 600;
        }

        /* Live Search Styles */
        #searchResultsDropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 1050;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .dropdown-header {
            padding: 0.5rem 1rem;
            background-color: var(--primary);
            color: white;
            font-weight: 600;
            border-radius: 0.375rem 0.375rem 0 0;
        }

        .search-result-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f8f9fa;
            text-decoration: none;
            color: #212529;
            transition: background-color 0.15s ease;
        }

        .search-result-item:hover {
            background-color: #f8f9fa;
            text-decoration: none;
            color: #212529;
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .search-result-thumbnail {
            width: 50px;
            height: 40px;
            object-fit: cover;
            border-radius: 0.25rem;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }

        .search-result-content {
            flex: 1;
            min-width: 0;
        }

        .search-result-title {
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .search-result-meta {
            font-size: 0.75rem;
            color: #6c757d;
        }

        .no-results {
            padding: 1rem;
            text-align: center;
            color: #6c757d;
        }

        .loading-results {
            padding: 1rem;
            text-align: center;
            color: #6c757d;
        }

        .dropdown-footer {
            background-color: #f8f9fa;
        }
        
        /* Perbaikan responsive */
        @media (max-width: 768px) {
            .hero-section {
                padding: 60px 0;
            }
            .hero-section h1 {
                font-size: 2rem;
            }
            .navbar-nav {
                text-align: center;
            }
            #liveSearchContainer {
                margin: 10px 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-newspaper me-2"></i>AnimeNews
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" 
                           href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i>Beranda
                        </a>
                    </li>
                    @php
                        $navCategories = \App\Models\Category::withCount(['posts' => function($query) {
                            $query->where('status', true);
                        }])->orderBy('posts_count', 'desc')->take(5)->get();
                    @endphp
                    
                    @foreach($navCategories as $category)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('kategori/' . $category->slug) ? 'active' : '' }}" 
                           href="{{ route('frontend.categories.show', $category->slug) }}">
                            <i class="fas fa-folder me-1"></i>{{ $category->name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
                
                <!-- Live Search Form -->
                <div class="d-flex ms-3 position-relative" id="liveSearchContainer">
                    <div class="input-group">
                        <input class="form-control" type="text" id="liveSearchInput" 
                               placeholder="Cari berita..." autocomplete="off">
                        <button class="btn btn-outline-light" type="button" id="liveSearchButton">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    
                    <!-- Dropdown Results -->
                    <div class="dropdown-menu w-100" id="searchResultsDropdown" 
                         style="display: none; max-height: 400px; overflow-y: auto;">
                        <div class="dropdown-header">
                            <i class="fas fa-search me-2"></i>Hasil Pencarian
                            <span class="badge bg-primary ms-2" id="resultsCount">0</span>
                        </div>
                        <div id="searchResultsList">
                            <!-- Results will be populated here -->
                        </div>
                        <div class="dropdown-footer p-2 border-top" id="searchFooter" style="display: none;">
                            <a href="{{ route('frontend.posts') }}" class="btn btn-sm btn-outline-primary w-100" id="viewAllResults">

                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Admin Login Link -->
                <ul class="navbar-nav ms-3">
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i>Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <!-- World Clock -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-dark text-light">
                        <div class="card-body text-center py-2">
                            <i class="fas fa-clock me-2"></i>
                            <span id="world-clock">Loading waktu...</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <h5>AnimeNews</h5>
                    <p>Media berita anime terpercaya menyajikan informasi aktual dan terupdate dari berbagai kategori.</p>
                </div>
                <div class="col-md-4">
                    <h5>Menu Cepat</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-light">Beranda</a></li>
                        @foreach($navCategories as $category)
                        <li><a href="{{ route('frontend.categories.show', $category->slug) }}" class="text-light">{{ $category->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Kontak</h5>
                    <p>
                        <i class="fas fa-envelope me-2"></i>animenews@gmail.com<br>
                        <i class="fas fa-phone me-2"></i>+62 838 6273 1325<br>
                        <i class="fas fa-map-marker-alt me-2"></i>Bandung, Indonesia
                    </p>
                </div>
            </div>
            <hr class="bg-light">
            <div class="text-center">
                <p>&copy; 2025 AnimeNews. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // World Clock Function
        function updateWorldClock() {
            const now = new Date();
            
            // Format untuk Indonesia
            const optionsID = {
                timeZone: 'Asia/Jakarta',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                weekday: 'long',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            
            // Format untuk UTC
            const optionsUTC = {
                timeZone: 'UTC',
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            
            const timeID = now.toLocaleString('id-ID', optionsID);
            const timeUTC = now.toLocaleString('en-US', optionsUTC);
            
            document.getElementById('world-clock').innerHTML = 
                `<strong>Waktu Indonesia (WIB):</strong> ${timeID} | <strong>UTC:</strong> ${timeUTC}`;
        }

        // Update clock every second
        setInterval(updateWorldClock, 1000);
        updateWorldClock(); // Initial call
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('liveSearchInput');
            const searchButton = document.getElementById('liveSearchButton');
            const dropdown = document.getElementById('searchResultsDropdown');
            const resultsList = document.getElementById('searchResultsList');
            const resultsCount = document.getElementById('resultsCount');
            const searchFooter = document.getElementById('searchFooter');
            const viewAllResults = document.getElementById('viewAllResults');

            let searchTimeout;
            let currentSearchTerm = '';

            // Focus management
            searchInput.addEventListener('focus', function() {
                if (currentSearchTerm.length >= 2) {
                    dropdown.style.display = 'block';
                }
            });

            // Input handler with debounce
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const query = this.value.trim();
                currentSearchTerm = query;

                if (query.length < 2) {
                    dropdown.style.display = 'none';
                    return;
                }

                // Show loading
                resultsList.innerHTML = '<div class="loading-results"><i class="fas fa-spinner fa-spin me-2"></i>Mencari...</div>';
                dropdown.style.display = 'block';
                searchFooter.style.display = 'none';

                searchTimeout = setTimeout(() => {
                    performLiveSearch(query);
                }, 300);
            });

            // Perform search
            function performLiveSearch(query) {
                fetch(`/api/live-search?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        displayResults(data, query);
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                        resultsList.innerHTML = '<div class="no-results">Terjadi kesalahan saat mencari</div>';
                    });
            }

            // Display results
            function displayResults(results, query) {
                resultsCount.textContent = results.length;
                
                if (results.length === 0) {
                    resultsList.innerHTML = `
                        <div class="no-results">
                            <i class="fas fa-search me-2"></i>
                            Tidak ditemukan hasil untuk "<strong>${query}</strong>"
                        </div>
                    `;
                    searchFooter.style.display = 'none';
                    return;
                }

                let html = '';
                results.forEach(item => {
                    const thumbnail = item.thumbnail || `https://via.placeholder.com/50x40/4361ee/ffffff?text=📰`;
                    
                    html += `
                        <a href="${item.url}" class="search-result-item">
                            <img src="${thumbnail}" alt="${item.title}" class="search-result-thumbnail" 
                                 onerror="this.src='https://via.placeholder.com/50x40/4361ee/ffffff?text=📰'">
                            <div class="search-result-content">
                                <div class="search-result-title">${item.title}</div>
                                <div class="search-result-meta">
                                    <span class="badge bg-primary badge-news me-2">${item.category}</span>
                                    ${item.date}
                                </div>
                            </div>
                        </a>
                    `;
                });

                resultsList.innerHTML = html;
                
                // Update "View All" link
                viewAllResults.href = `{{ route('frontend.posts') }}?search=${encodeURIComponent(query)}`;
                searchFooter.style.display = 'block';
            }

            // Search button click
            searchButton.addEventListener('click', function() {
                const query = searchInput.value.trim();
                if (query.length >= 2) {
                    window.location.href = `{{ route('frontend.posts') }}?search=${encodeURIComponent(query)}`;
                } else {
                    searchInput.focus();
                }
            });

            // Enter key handler
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const query = searchInput.value.trim();
                    if (query.length >= 2) {
                        window.location.href = `{{ route('frontend.posts') }}?search=${encodeURIComponent(query)}`;
                    }
                    e.preventDefault();
                    dropdown.style.display = 'none';
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('#liveSearchContainer')) {
                    dropdown.style.display = 'none';
                }
            });

            // Escape key to close dropdown
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    dropdown.style.display = 'none';
                    searchInput.blur();
                }
            });

            console.log('Live search initialized');
        });
    </script>

    @stack('scripts')
</body>
</html>