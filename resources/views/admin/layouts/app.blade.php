<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AI-STAR') }} - 後台管理系統</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>
    <div class="wrapper">
        <!-- 側邊選單 -->
        <nav id="sidebar" class="bg-dark text-white">
            <div class="sidebar-header p-3">
                <h3>AI-STAR</h3>
                <p class="mb-0">後台管理系統</p>
            </div>

            <ul class="list-unstyled components">
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2"></i> 儀表板
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}">
                        <i class="bi bi-people"></i> 會員管理
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.orders.index') }}">
                        <i class="bi bi-cart"></i> 訂單管理
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.pages.index') }}">
                        <i class="bi bi-file-text"></i> 頁面管理
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.messages.index') }}">
                        <i class="bi bi-envelope"></i> 訊息管理
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.images.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.images.index') }}">
                        <i class="bi bi-image"></i> 圖像管理
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.accounts.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.accounts.index') }}">
                        <i class="bi bi-person-gear"></i> 帳號管理
                    </a>
                </li>
            </ul>
        </nav>

        <!-- 主要內容區 -->
        <div id="content">
            <!-- 頂部導航欄 -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-dark">
                        <i class="bi bi-list"></i>
                    </button>

                    <div class="ms-auto d-flex align-items-center">
                        <div class="dropdown">
                            <button class="btn btn-link dropdown-toggle text-dark" type="button" id="userDropdown" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                                {{ Auth::guard('admin')->user()->email }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <form action="{{ route('admin.logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right"></i> 登出
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- 頁面內容 -->
            <div class="container-fluid py-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
        });
    </script>

    @stack('scripts')
</body>
</html> 