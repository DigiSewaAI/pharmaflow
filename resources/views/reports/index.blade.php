{{-- resources/views/reports/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'PharmaFlow — Reports')
@section('page-title', 'Reports & Analytics')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
    <div>
        <h2 style="font-size:22px;font-weight:700;color:#0f172a;" class="dark:text-slate-100">Reports & Analytics</h2>
        <p style="color:#64748b;font-size:14px;" class="dark:text-slate-400">Sales, profit, and inventory insights</p>
    </div>
    <div style="display:flex;gap:8px;">
        <button class="btn btn-outline btn-sm" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
        <a href="{{ route('reports.export', 'pdf') }}" class="btn btn-outline btn-sm"><i class="fas fa-file-pdf"></i> PDF</a>
        <a href="{{ route('reports.export', 'excel') }}" class="btn btn-outline btn-sm"><i class="fas fa-file-excel"></i> Excel</a>
    </div>
</div>

<!-- Date Range Filter -->
<div class="card mb-4">
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 items-end">
        <div>
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" value="{{ $startDate }}" class="form-input">
        </div>
        <div>
            <label class="form-label">End Date</label>
            <input type="date" name="end_date" value="{{ $endDate }}" class="form-input">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Apply</button>
            <a href="{{ route('reports.index') }}" class="btn btn-outline"><i class="fas fa-undo"></i> Reset</a>
        </div>
    </form>
</div>

<!-- Summary Cards -->
<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(180px, 1fr));gap:16px;margin-bottom:24px;">
    <div class="stat-card">
        <div style="display:flex;align-items:center;gap:14px;">
            <div class="icon" style="background:#eff6ff;color:#2563EB;"><i class="fas fa-chart-line"></i></div>
            <div>
                <div style="font-size:13px;color:#64748b;" class="dark:text-slate-400">Total Revenue</div>
                <div style="font-size:22px;font-weight:700;color:#0f172a;" class="dark:text-slate-100">
                    Rs {{ number_format($totalRevenue, 0) }}
                </div>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div style="display:flex;align-items:center;gap:14px;">
            <div class="icon" style="background:#d1fae5;color:#10b981;"><i class="fas fa-coins"></i></div>
            <div>
                <div style="font-size:13px;color:#64748b;" class="dark:text-slate-400">Profit</div>
                <div style="font-size:22px;font-weight:700;color:#0f172a;" class="dark:text-slate-100">
                    Rs {{ number_format($profit, 0) }}
                </div>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div style="display:flex;align-items:center;gap:14px;">
            <div class="icon" style="background:#fef3c7;color:#f59e0b;"><i class="fas fa-shopping-cart"></i></div>
            <div>
                <div style="font-size:13px;color:#64748b;" class="dark:text-slate-400">Total Sales</div>
                <div style="font-size:22px;font-weight:700;color:#0f172a;" class="dark:text-slate-100">
                    {{ $totalSales }}
                </div>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div style="display:flex;align-items:center;gap:14px;">
            <div class="icon" style="background:#fee2e2;color:#ef4444;"><i class="fas fa-cubes"></i></div>
            <div>
                <div style="font-size:13px;color:#64748b;" class="dark:text-slate-400">Inventory Value</div>
                <div style="font-size:22px;font-weight:700;color:#0f172a;" class="dark:text-slate-100">
                    Rs {{ number_format($inventoryValue, 0) }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:24px;">
    <div class="card">
        <h3 style="font-size:15px;font-weight:600;margin-bottom:14px;color:#0f172a;" class="dark:text-slate-100">Sales Trend</h3>
        <div style="height:260px;">
            <canvas id="salesChart"></canvas>
        </div>
    </div>
    <div class="card">
        <h3 style="font-size:15px;font-weight:600;margin-bottom:14px;color:#0f172a;" class="dark:text-slate-100">Top Selling Medicines</h3>
        <div style="max-height:260px;overflow-y:auto;">
            @forelse($topMedicines as $med)
                <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f1f5f9;" class="dark:border-slate-700">
                    <span>{{ $med->name }}</span>
                    <span style="color:#2563EB;font-weight:600;">{{ $med->total_sold ?? 0 }} units</span>
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-6">No sales data.</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="card">
    <h3 style="font-size:15px;font-weight:600;margin-bottom:12px;color:#0f172a;" class="dark:text-slate-100">Recent Inventory Activity</h3>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Product</th>
                    <th>Type</th>
                    <th>Qty</th>
                    <th>User</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $txn)
                    <tr>
                        <td>{{ $txn->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $txn->medicine?->name ?? 'Deleted' }}</td>
                        <td><span class="status-badge {{ $txn->type == 'in' ? 'status-instock' : 'status-low' }}">{{ ucfirst($txn->type) }}</span></td>
                        <td>{{ $txn->type == 'in' ? '+' : '-' }}{{ $txn->quantity }}</td>
                        <td>{{ $txn->user?->name ?? 'System' }}</td>
                        <td>{{ $txn->reason ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;padding:40px 0;color:#94a3b8;">No transactions.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Toast -->
<div id="toastContainer" class="toast-container"></div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart');
        if (ctx) {
            const salesData = @json($salesData);
            const labels = salesData.map(item => item.date);
            const totals = salesData.map(item => item.total);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Daily Revenue (Rs)',
                        data: totals,
                        borderColor: '#2563EB',
                        backgroundColor: 'rgba(37,99,235,0.08)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#2563EB'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: document.body.classList.contains('dark') ? '#94a3b8' : '#64748b',
                                font: { size: 11 }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: document.body.classList.contains('dark') ? '#334155' : '#e2e8f0' },
                            ticks: { color: document.body.classList.contains('dark') ? '#94a3b8' : '#64748b' }
                        },
                        y: {
                            grid: { color: document.body.classList.contains('dark') ? '#334155' : '#e2e8f0' },
                            ticks: {
                                color: document.body.classList.contains('dark') ? '#94a3b8' : '#64748b',
                                callback: function(value) { return 'Rs ' + value; }
                            }
                        }
                    }
                }
            });
        }
    });

    // Toast fallback
    function showToast(message, type = 'info') {
        const container = document.getElementById('toastContainer');
        if (!container) return;
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        const icons = { success: 'fa-check-circle', error: 'fa-exclamation-circle', warning: 'fa-exclamation-triangle', info: 'fa-info-circle' };
        toast.innerHTML = `<i class="fas ${icons[type] || icons.info}"></i> ${message}`;
        container.appendChild(toast);
        setTimeout(() => { toast.style.opacity = '0'; toast.style.transform = 'translateX(20px)'; setTimeout(() => toast.remove(), 300); }, 3500);
    }
</script>
@endpush