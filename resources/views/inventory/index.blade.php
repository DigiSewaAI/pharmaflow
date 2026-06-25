{{-- resources/views/inventory/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'PharmaFlow — Inventory')
@section('page-title', 'Inventory')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
    <div>
        <h2 style="font-size:22px;font-weight:700;color:#0f172a;" class="dark:text-slate-100">Inventory Management</h2>
        <p style="color:#64748b;font-size:14px;" class="dark:text-slate-400">Stock in / out & batch tracking</p>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <button class="btn btn-success btn-sm" onclick="openModal('stockInModal')">
            <i class="fas fa-arrow-down"></i> Stock In
        </button>
        <button class="btn btn-warning btn-sm" onclick="openModal('stockOutModal')">
            <i class="fas fa-arrow-up"></i> Stock Out
        </button>
        <button class="btn btn-primary btn-sm" onclick="openModal('adjustModal')">
            <i class="fas fa-sliders-h"></i> Adjust
        </button>
    </div>
</div>

<!-- Stats -->
<div class="grid-cols-dashboard" style="margin-bottom:24px;">
    <div class="stat-card">
        <div style="display:flex;align-items:center;gap:14px;">
            <div class="icon" style="background:#d1fae5;color:#10b981;"><i class="fas fa-cubes"></i></div>
            <div>
                <div style="font-size:13px;color:#64748b;" class="dark:text-slate-400">Total Items</div>
                <div style="font-size:22px;font-weight:700;color:#0f172a;" class="dark:text-slate-100">
                    {{ number_format($totalItems ?? 0) }}
                </div>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div style="display:flex;align-items:center;gap:14px;">
            <div class="icon" style="background:#fee2e2;color:#ef4444;"><i class="fas fa-exclamation-triangle"></i></div>
            <div>
                <div style="font-size:13px;color:#64748b;" class="dark:text-slate-400">Low Stock Items</div>
                <div style="font-size:22px;font-weight:700;color:#0f172a;" class="dark:text-slate-100">
                    {{ $lowStockItems ?? 0 }}
                </div>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div style="display:flex;align-items:center;gap:14px;">
            <div class="icon" style="background:#eff6ff;color:#2563EB;"><i class="fas fa-rupee-sign"></i></div>
            <div>
                <div style="font-size:13px;color:#64748b;" class="dark:text-slate-400">Total Value</div>
                <div style="font-size:22px;font-weight:700;color:#0f172a;" class="dark:text-slate-100">
                    Rs {{ number_format($totalValue ?? 0, 0) }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Batch Tracking & Timeline -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
    <div class="card">
        <h3 style="font-size:15px;font-weight:600;margin-bottom:12px;color:#0f172a;" class="dark:text-slate-100">Inventory Timeline</h3>
        <div style="height:200px;">
            <canvas id="invTimelineChart"></canvas>
        </div>
    </div>
    <div class="card">
        <h3 style="font-size:15px;font-weight:600;margin-bottom:12px;color:#0f172a;" class="dark:text-slate-100">Batch Tracking</h3>
        <div style="space-y:8px;max-height:220px;overflow-y:auto;">
            @forelse($batches ?? [] as $batch)
                <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid #f1f5f9;" class="dark:border-slate-700">
                    <span>{{ $batch->batch_number ?? 'N/A' }}</span>
                    <span class="status-badge {{ $batch->status == 'in_stock' ? 'status-instock' : ($batch->status == 'low_stock' ? 'status-low' : 'status-expired') }}">
                        {{ ucfirst(str_replace('_',' ', $batch->status ?? 'Unknown')) }}
                    </span>
                    <span>{{ $batch->quantity ?? 0 }} units</span>
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-6">No batches found.</p>
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
                    <th>Batch</th>
                    <th>User</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions ?? [] as $txn)
                    <tr>
                        <td>{{ $txn->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $txn->medicine?->name ?? 'Deleted' }}</td>
                        <td>
                            <span class="status-badge {{ $txn->type == 'in' ? 'status-instock' : 'status-low' }}" 
                                  style="{{ $txn->type == 'in' ? 'background:#d1fae5;color:#065f46;' : 'background:#fef3c7;color:#92400e;' }}">
                                {{ ucfirst($txn->type) }}
                            </span>
                        </td>
                        <td>{{ $txn->type == 'in' ? '+' : '-' }}{{ $txn->quantity }}</td>
                        <td>{{ $txn->batch ?? '-' }}</td>
                        <td>{{ $txn->user?->name ?? 'System' }}</td>
                        <td>{{ $txn->reason ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:40px 0;color:#94a3b8;">
                            <i class="fas fa-clock" style="font-size:32px;display:block;margin-bottom:12px;"></i>
                            No transactions yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ===== PARTIALS ===== -->
@include('inventory.partials.modals')

@endsection

@push('scripts')
<script>
    // ─── Modal functions ───
    function openModal(id) {
        document.getElementById(id).classList.add('open');
    }
    function closeModal(id) {
        document.getElementById(id).classList.remove('open');
    }
    document.querySelectorAll('.modal-overlay').forEach(el => {
        el.addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('open');
        });
    });

    // ─── Stock In form ───
    document.getElementById('stockInForm')?.addEventListener('submit', function(e) {
        // Form submits via normal POST – works with controller
    });

    // ─── Stock Out form ───
    document.getElementById('stockOutForm')?.addEventListener('submit', function(e) {
        // Form submits via normal POST
    });

    // ─── Inventory Timeline Chart ───
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('invTimelineChart');
        if (ctx && typeof Chart !== 'undefined') {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [
                        {
                            label: 'Stock In',
                            data: [120, 80, 200, 60, 150, 90, 140],
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16,185,129,0.1)',
                            fill: true,
                            tension: 0.3
                        },
                        {
                            label: 'Stock Out',
                            data: [90, 110, 70, 140, 80, 120, 100],
                            borderColor: '#EF4444',
                            backgroundColor: 'rgba(239,68,68,0.1)',
                            fill: true,
                            tension: 0.3
                        }
                    ]
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
                            ticks: { color: document.body.classList.contains('dark') ? '#94a3b8' : '#64748b' }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush