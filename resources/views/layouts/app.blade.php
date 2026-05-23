<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News CMS - @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.0.0/dist/css/tom-select.bootstrap5.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mystery-theme.css') }}">

    <!-- Admin Specific Styles -->
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a0ca3;
            --secondary: #7209b7;
            --success: #4cc9f0;
            --warning: #f72585;
            --info: #4895ef;
            --dark: #1e1e2d;
            --light: #f8f9fa;
            --sidebar-width: 280px;
            --header-height: 70px;
        }

        /* Enhanced Admin Layout */
        body {
            background-color: #f5f7fb;
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        /* Modern Sidebar */
        .sidebar {
            background: linear-gradient(180deg, var(--dark) 0%, #2d2d44 100%);
            min-height: 100vh;
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: var(--sidebar-width);
            z-index: 1000;
            border-right: 1px solid rgba(255,255,255,0.1);
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .sidebar-content {
            padding: 20px 0;
            height: 100%;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .sidebar-brand {
            padding: 0 1.5rem 2rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1rem;
        }

        .sidebar-brand h4 {
            color: white;
            margin: 0;
            font-weight: 700;
        }

        .sidebar-brand small {
            color: rgba(255,255,255,0.7);
            font-size: 0.8rem;
        }

        .sidebar .nav-link {
            color: #a1a5b7;
            padding: 12px 20px;
            margin: 4px 15px;
            border-radius: 10px;
            transition: all 0.3s ease;
            border: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            text-decoration: none;
        }

        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: var(--primary);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .sidebar .nav-link:hover {
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary);
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background: linear-gradient(45deg, var(--primary), var(--primary-dark));
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }

        .sidebar .nav-link.active::before {
            transform: scaleY(1);
        }

        .sidebar .nav-link i {
            width: 20px;
            margin-right: 12px;
            font-size: 1.1em;
        }

        /* User Info Section */
        .user-info-section {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(255,255,255,0.1);
            padding: 1rem 1.5rem;
            margin-top: auto;
        }

        .user-info-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: white;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(45deg, var(--primary), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .user-details {
            flex: 1;
            margin-left: 12px;
        }

        .user-details h6 {
            margin-bottom: 0;
            font-weight: 600;
        }

        .user-details small {
            color: rgba(255,255,255,0.7);
            font-size: 0.8rem;
        }

        /* Main Content Area */
        .main-content {
            margin-left: var(--sidebar-width);
            background: #f5f7fb;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
            width: calc(100% - var(--sidebar-width));
        }

        .content-area {
            padding: 2rem;
            max-width: 100%;
            overflow-x: hidden;
        }

        /* Page Header */
        .page-header {
            background: white;
            padding: 1.5rem 0;
            margin: -2rem -2rem 2rem;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            border-bottom: none;
            position: relative;
        }

        .page-header h1 {
            color: var(--dark);
            font-weight: 700;
            margin-bottom: 0;
        }

        .page-header h1 i {
            color: var(--primary);
        }

        /* Sidebar Toggle Button */
        .sidebar-toggle {
            display: none;
            background: var(--primary);
            border: none;
            color: white;
            border-radius: 8px;
            padding: 0.5rem;
            margin-right: 1rem;
            cursor: pointer;
        }

        /* Loading Overlay */
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.9);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Enhanced Cards */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #f1f3f4;
            padding: 1.25rem 1.5rem;
            border-radius: 16px 16px 0 0 !important;
        }

        .card-header h6 {
            color: var(--dark);
            font-weight: 600;
            margin-bottom: 0;
        }

        /* Enhanced Alerts */
        .alert {
            border: none;
            border-radius: 12px;
            border-left: 4px solid;
            box-shadow: 0 4px 6px rgba(0,0,0,0.04);
            padding: 1rem 1.5rem;
        }

        .alert-success {
            background: rgba(76, 201, 240, 0.1);
            border-left-color: #4cc9f0;
            color: #0c5460;
        }

        .alert-danger {
            background: rgba(247, 37, 133, 0.1);
            border-left-color: #f72585;
            color: #721c24;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                width: 260px;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            .sidebar-toggle {
                display: block;
            }
            
            .content-area {
                padding: 1rem;
            }
            
            .page-header {
                margin: -1rem -1rem 1rem;
                padding: 1rem 0;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 240px;
            }
            
            .content-area {
                padding: 0.75rem;
            }
            
            .card-body {
                padding: 1rem;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 220px;
            }
            
            .content-area {
                padding: 0.5rem;
            }
            
            .page-header h1 {
                font-size: 1.5rem;
            }
            
            .user-info-content {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .user-details {
                margin-left: 0;
            }
        }

        /* Fix for horizontal scroll */
        .container-fluid {
            padding-left: 0;
            padding-right: 0;
            max-width: 100%;
        }

        .row {
            margin-left: 0;
            margin-right: 0;
        }

        /* Ensure content doesn't overflow */
        main, .main-content, .content-area {
            max-width: 100%;
            overflow-x: hidden;
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="globalLoading">
        <div class="loading-spinner"></div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="sidebar">
                <div class="sidebar-content">
                    <div class="sidebar-brand">
                        <h4>NEWS CMS</h4>
                        <small>Content Management System</small>
                    </div>
                    
                    <!-- Menu Navigasi Sidebar -->
                    <ul class="nav flex-column nav-flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                            href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('posts.*') ? 'active' : '' }}" 
                            href="{{ route('posts.index') }}">
                                <i class="fas fa-newspaper me-2"></i>
                                Manajemen Berita
                            </a>
                        </li>
                        @auth
                            @if(auth()->user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" 
                                href="{{ route('categories.index') }}">
                                    <i class="fas fa-folder me-2"></i>
                                    Kategori
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('tags.*') ? 'active' : '' }}" 
                                href="{{ route('tags.index') }}">
                                    <i class="fas fa-tags me-2"></i>
                                    Tags
                                </a>
                            </li>
                            @endif
                        @endauth
                    </ul>

                    <!-- User Info Section -->
                    <div class="user-info-section">
                        <div class="user-info-content">
                            <div class="d-flex align-items-center">
                                <div class="user-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="user-details">
                                    <h6>{{ Auth::user()->name }}</h6>
                                    <small>{{ ucfirst(Auth::user()->role) }}</small>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-light" title="Logout">
                                    <i class="fas fa-sign-out-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main content -->
            <main class="main-content">
                <div class="content-area">
                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div class="d-flex align-items-center">
                                <button class="sidebar-toggle" id="sidebarToggle">
                                    <i class="fas fa-bars"></i>
                                </button>
                                <h1 class="h3 mb-0">
                                    <i class="fas @yield('icon', 'fa-newspaper') me-2"></i>
                                    @yield('title')
                                </h1>
                            </div>
                            <div class="btn-toolbar mb-2 mb-md-0">
                                @yield('actions')
                            </div>
                        </div>
                    </div>

                    <!-- Notifications -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mb-0 mt-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.0.0/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Enhanced Admin Functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar Toggle for Mobile
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mainContent = document.querySelector('.main-content');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    // Toggle body overflow when sidebar is open on mobile
                    if (window.innerWidth <= 992) {
                        document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
                    }
                });
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 992) {
                    const isClickInsideSidebar = sidebar.contains(event.target);
                    const isClickOnToggle = sidebarToggle ? sidebarToggle.contains(event.target) : false;
                    
                    if (!isClickInsideSidebar && !isClickOnToggle && sidebar.classList.contains('show')) {
                        sidebar.classList.remove('show');
                        document.body.style.overflow = '';
                    }
                }
            });

            // Active menu highlighting
            const currentPath = window.location.pathname;
            document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });

            // Adjust layout on resize
            function adjustLayout() {
                if (window.innerWidth >= 993) {
                    mainContent.style.marginLeft = sidebar.offsetWidth + 'px';
                    mainContent.style.width = `calc(100% - ${sidebar.offsetWidth}px)`;
                    sidebar.classList.remove('show');
                    document.body.style.overflow = '';
                } else {
                    mainContent.style.marginLeft = '0';
                    mainContent.style.width = '100%';
                }
            }

            window.addEventListener('resize', adjustLayout);
            adjustLayout(); // Initial call

            // Global Loading Handler
            const loadingOverlay = document.getElementById('globalLoading');
            
            // Show loading for form submissions
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    if (loadingOverlay) {
                        loadingOverlay.style.display = 'flex';
                    }
                });
            });

            // Hide loading when page is ready
            window.addEventListener('load', function() {
                if (loadingOverlay) {
                    loadingOverlay.style.display = 'none';
                }
            });
        });

        // Enhanced error handling
        window.addEventListener('error', function(e) {
            console.error('Error occurred:', e.error);
        });
    </script>
    
    @stack('scripts')
</body>
</html>