{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'PharmaFlow — Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:24px;">
    <div>
        <h2 style="font-size:22px;font-weight:700;color:#0f172a;" class="dark:text-slate-100">Dashboard</h2>
        <p style="color:#64748b;font-size:14px;" class="dark:text-slate-400" id="dashboardGreeting">
            Welcome back, {{ Auth::user()->name }}. Here's your pharmacy overview.
        </p>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <button class="btn btn-outline btn-sm"><i class="fas fa-download"></i> Export</button>
        <a href="{{ route('pos.index') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> New Sale</a>
    </div>
</div>

<!-- Stats -->
<div class="grid-cols-dashboard" style="margin-bottom:24px;">
    <div class="stat-card">
        <div style="display:flex;align-items:center;gap:14px;">
            <div class="icon" style="background:#eff6ff;color:#2563EB;"><i class="fas fa-dollar-sign"></i></div>
            <div>
                <div style="font-size:13px;color:#64748b;" class="dark:text-slate-400">Today's Sales</div>
                <div style="font-size:22px;font-weight:700;color:#0f172a;" class="dark:text-slate-100">
                    Rs {{ number_format($todaySales ?? 0, 0) }}
                </div>
                <div style="font-size:12px;color:#10b981;"><i class="fas fa-arrow-up"></i> +12.5%</div>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div style="display:flex;align-items:center;gap:14px;">
            <div class="icon" style="background:#fef3c7;color:#f59e0b;"><i class="fas fa-calendar-alt"></i></div>
            <div>
                <div style="font-size:13px;color:#64748b;" class="dark:text-slate-400">Monthly Revenue</div>
                <div style="font-size:22px;font-weight:700;color:#0f172a;" class="dark:text-slate-100">
                    Rs {{ number_format($monthlyRevenue ?? 0, 0) }}
                </div>
                <div style="font-size:12px;color:#10b981;"><i class="fas fa-arrow-up"></i> +8.3%</div>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div style="display:flex;align-items:center;gap:14px;">
            <div class="icon" style="background:#d1fae5;color:#10b981;"><i class="fas fa-capsules"></i></div>
            <div>
                <div style="font-size:13px;color:#64748b;" class="dark:text-slate-400">Total Medicines</div>
                <div style="font-size:22px;font-weight:700;color:#0f172a;" class="dark:text-slate-100">
                    {{ $totalMedicines ?? 0 }}
                </div>
                <div style="font-size:12px;color:#64748b;">+24 this month</div>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div style="display:flex;align-items:center;gap:14px;">
            <div class="icon" style="background:#fee2e2;color:#ef4444;"><i class="fas fa-exclamation-triangle"></i></div>
            <div>
                <div style="font-size:13px;color:#64748b;" class="dark:text-slate-400">Low Stock</div>
                <div style="font-size:22px;font-weight:700;color:#0f172a;" class="dark:text-slate-100">
                    {{ $lowStock ?? 0 }}
                </div>
                <div style="font-size:12px;color:#ef4444;">⚠️ {{ $expiringSoon ?? 0 }} expiring soon</div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:24px;">
    <div class="card">
        <h3 style="font-size:15px;font-weight:600;margin-bottom:14px;color:#0f172a;" class="dark:text-slate-100">Revenue Overview</h3>
        <div class="chart-container"><canvas id="dashRevenueChart"></canvas></div>
    </div>
    <div class="card">
        <h3 style="font-size:15px;font-weight:600;margin-bottom:14px;color:#0f172a;" class="dark:text-slate-100">Stock Trend</h3>
        <div class="chart-container-sm"><canvas id="dashStockChart"></canvas></div>
    </div>
</div>

<!-- Bottom Row -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
    <div class="card">
        <h3 style="font-size:15px;font-weight:600;margin-bottom:14px;color:#0f172a;" class="dark:text-slate-100">Top Selling Medicines</h3>
        <div style="space-y:10px;">
            @forelse($topMedicines ?? [] as $med)
                <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f1f5f9;" class="dark:border-slate-700">
                    <span style="font-weight:500;">{{ $med->name }}</span>
                    <span style="color:#2563EB;font-weight:600;">{{ $med->sale_items_count ?? 0 }} units</span>
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-6">No sales data yet.</p>
            @endforelse
        </div>
    </div>
    <div class="card">
        <h3 style="font-size:15px;font-weight:600;margin-bottom:14px;color:#0f172a;" class="dark:text-slate-100">Recent Activity</h3>
        <div class="activity-feed">
            @forelse($recentActivities ?? [] as $activity)
                <div class="activity-item">
                    <span class="activity-dot" style="background:{{ $activity->color }};"></span>
                    <div>
                        <div style="font-size:13px;font-weight:500;">{{ $activity->message }}</div>
                        <div style="font-size:12px;color:#64748b;" class="dark:text-slate-400">
                            {{ $activity->detail }} • {{ $activity->time }}
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-6">No recent activities.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize charts when page loads
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof initCharts === 'function') {
            initCharts();
        }
    });
</script>
@endpush