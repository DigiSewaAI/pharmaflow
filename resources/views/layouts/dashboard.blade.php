{{-- resources/views/layouts/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PharmaFlow — Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Tailwind & Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800;14..32,900&display=swap" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <!-- Custom Dashboard CSS -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    
    @stack('styles')
</head>
<body>
    <div id="dashboardApp">
        <!-- ===== SIDEBAR ===== -->
        <aside class="sidebar" id="dashboardSidebar">
            <div class="sidebar-brand">
                <i class="fas fa-capsules"></i>
                <span>PharmaFlow</span>
            </div>
            <nav>
                <div class="nav-section">Main</div>
                <a class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                   href="{{ route('dashboard') }}" data-page="dashboard">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
                <a class="nav-item {{ request()->routeIs('medicines*') ? 'active' : '' }}" 
                   href="{{ route('medicines.index') }}" data-page="medicines">
                    <i class="fas fa-pills"></i> Medicines
                </a>
                <a class="nav-item {{ request()->routeIs('inventory*') ? 'active' : '' }}" 
                   href="{{ route('inventory.index') }}" data-page="inventory">
                    <i class="fas fa-warehouse"></i> Inventory
                </a>
                <a class="nav-item {{ request()->routeIs('pos*') ? 'active' : '' }}" 
                   href="{{ route('pos.index') }}" data-page="pos">
                    <i class="fas fa-cash-register"></i> POS
                </a>
                <a class="nav-item {{ request()->routeIs('purchases*') ? 'active' : '' }}" 
                   href="{{ route('purchases.index') }}" data-page="purchases">
                    <i class="fas fa-truck"></i> Purchases
                </a>

                <div class="nav-section">Management</div>
                <a class="nav-item {{ request()->routeIs('customers*') ? 'active' : '' }}" 
                   href="{{ route('customers.index') }}" data-page="customers">
                    <i class="fas fa-users"></i> Customers
                </a>
                <a class="nav-item {{ request()->routeIs('suppliers*') ? 'active' : '' }}" 
                   href="{{ route('suppliers.index') }}" data-page="suppliers">
                    <i class="fas fa-building"></i> Suppliers
                </a>
                <a class="nav-item {{ request()->routeIs('reports*') ? 'active' : '' }}" 
                   href="{{ route('reports.index') }}" data-page="reports">
                    <i class="fas fa-chart-bar"></i> Reports
                </a>
                <a class="nav-item {{ request()->routeIs('expiry*') ? 'active' : '' }}" 
                   href="{{ route('expiry.index') }}" data-page="expiry">
                    <i class="fas fa-clock"></i> Expiry Center <span class="badge" id="expiryBadge">12</span>
                </a>

                <div class="nav-section">System</div>
                <a class="nav-item {{ request()->routeIs('notifications*') ? 'active' : '' }}" 
                   href="{{ route('notifications.index') }}" data-page="notifications">
                    <i class="fas fa-bell"></i> Notifications <span class="badge warning" id="notifBadge">4</span>
                </a>
                <a class="nav-item {{ request()->routeIs('users*') ? 'active' : '' }}" 
                   href="{{ route('users.index') }}" data-page="users">
                    <i class="fas fa-user-shield"></i> Users
                </a>
                <a class="nav-item {{ request()->routeIs('settings*') ? 'active' : '' }}" 
                   href="{{ route('settings.index') }}" data-page="settings">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <a class="nav-item {{ request()->routeIs('subscription*') ? 'active' : '' }}" 
                   href="{{ route('subscription.index') }}" data-page="subscription">
                    <i class="fas fa-crown"></i> Subscription
                </a>
                <a class="nav-item {{ request()->routeIs('help*') ? 'active' : '' }}" 
                   href="{{ route('help.index') }}" data-page="help">
                    <i class="fas fa-question-circle"></i> Help Center
                </a>
                <div style="margin-top:16px;border-top:1px solid #e2e8f0;padding-top:12px;padding-left:20px;padding-right:20px;">
                    <a class="nav-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color:#ef4444;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                        @csrf
                    </form>
                </div>
            </nav>
        </aside>

        <!-- ===== TOPBAR ===== -->
        <header class="topbar" id="dashboardTopbar">
            <div class="topbar-left">
                <button class="menu-toggle" onclick="toggleDashboardSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <span class="topbar-title" id="dashboardTitle">@yield('page-title', 'Dashboard')</span>
            </div>
            <div class="topbar-right">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search..." id="dashboardSearch" 
                           onkeydown="if(event.key==='Enter') openCommandPalette()">
                    <span style="font-size:11px;color:#94a3b8;font-weight:500;">⌘K</span>
                </div>
                <button class="theme-toggle" onclick="toggleTheme()">
                    <i class="fas fa-moon" id="dashThemeIcon"></i>
                </button>

                {{-- Notification Dropdown Component --}}
                @include('components.notification-dropdown', ['unreadCount' => $unreadCount ?? 0])

                <div class="avatar" onclick="navigate('settings')">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
            </div>
        </header>

        <!-- ===== MAIN CONTENT ===== -->
        <main class="main-content" id="dashboardMain">
            @yield('content')
        </main>
    </div>

    <!-- ===== TOAST ===== -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- ===== COMMAND PALETTE ===== -->
    <div class="cmd-overlay" id="cmdOverlay" onclick="closeCommandPalette()"></div>
    <div class="cmd-palette" id="cmdPalette">
        <input type="text" placeholder="Search commands..." id="cmdInput" oninput="filterCommands()" onkeydown="if(event.key==='Enter') executeCommand()">
        <div class="results" id="cmdResults"></div>
    </div>

    <!-- ===== FAB ===== -->
    <button id="fab" aria-label="Back to top" class="hidden" onclick="window.scrollTo({top:0,behavior:'smooth'})">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- ===== JAVASCRIPT ===== -->
    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script>
        // Global user data
        window.user = @json(Auth::user());
        
        @stack('scripts')
    </script>
</body>
</html>