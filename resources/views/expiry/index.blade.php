{{-- resources/views/expiry/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'PharmaFlow — Expiry Center')
@section('page-title', 'Expiry Center')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
    <div>
        <h2 style="font-size:22px;font-weight:700;color:#0f172a;" class="dark:text-slate-100">Medicine Expiry Center</h2>
        <p style="color:#64748b;font-size:14px;" class="dark:text-slate-400">Track expiring and expired medicines</p>
    </div>
    <div>
        <a href="{{ route('reports.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-chart-bar"></i> Full Reports</a>
    </div>
</div>

<!-- Stats -->
<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(180px, 1fr));gap:16px;margin-bottom:20px;">
    <div class="stat-card" style="border-left:4px solid #10b981;">
        <div style="font-size:13px;color:#64748b;" class="dark:text-slate-400">Expiring in 7 days</div>
        <div style="font-size:26px;font-weight:700;color:#0f172a;" class="dark:text-slate-100">{{ $expiringIn7Days }}</div>
    </div>
    <div class="stat-card" style="border-left:4px solid #f59e0b;">
        <div style="font-size:13px;color:#64748b;" class="dark:text-slate-400">Expiring in 15 days</div>
        <div style="font-size:26px;font-weight:700;color:#0f172a;" class="dark:text-slate-100">{{ $expiringIn15Days }}</div>
    </div>
    <div class="stat-card" style="border-left:4px solid #f59e0b;">
        <div style="font-size:13px;color:#64748b;" class="dark:text-slate-400">Expiring in 30 days</div>
        <div style="font-size:26px;font-weight:700;color:#0f172a;" class="dark:text-slate-100">{{ $expiringIn30Days }}</div>
    </div>
    <div class="stat-card" style="border-left:4px solid #ef4444;">
        <div style="font-size:13px;color:#64748b;" class="dark:text-slate-400">Expired</div>
        <div style="font-size:26px;font-weight:700;color:#0f172a;" class="dark:text-slate-100">{{ $expiredCount }}</div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
        <div>
            <label class="form-label">Search</label>
            <input type="text" name="search" placeholder="Name or batch..." value="{{ request('search') }}" class="form-input">
        </div>
        <div>
            <label class="form-label">Category</label>
            <select name="category_id" class="form-input select-custom">
                <option value="">All</option>
                @foreach($categories ?? [] as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Days to expiry</label>
            <select name="days" class="form-input select-custom">
                <option value="">All</option>
                <option value="7" {{ request('days') == 7 ? 'selected' : '' }}>7 days</option>
                <option value="15" {{ request('days') == 15 ? 'selected' : '' }}>15 days</option>
                <option value="30" {{ request('days') == 30 ? 'selected' : '' }}>30 days</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
            <a href="{{ route('expiry.index') }}" class="btn btn-outline"><i class="fas fa-undo"></i> Reset</a>
        </div>
    </form>
</div>

<!-- Table -->
<div class="card" style="padding:0;overflow:hidden;">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Medicine</th>
                    <th>Batch</th>
                    <th>Category</th>
                    <th>Expiry Date</th>
                    <th>Days Left</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expiringMedicines as $med)
                    @php
                        $daysLeft = $med->expiry_date ? now()->diffInDays($med->expiry_date, false) : null;
                        $isExpired = $daysLeft !== null && $daysLeft < 0;
                        $statusClass = $isExpired ? 'status-expired' : ($daysLeft <= 7 ? 'status-low' : 'status-instock');
                        $statusLabel = $isExpired ? 'Expired' : ($daysLeft <= 7 ? 'Expiring Soon' : 'Active');
                    @endphp
                    <tr>
                        <td><strong>{{ $med->name }}</strong></td>
                        <td>{{ $med->batch_number ?? '-' }}</td>
                        <td>{{ $med->category?->name ?? '-' }}</td>
                        <td>{{ $med->expiry_date ?? '-' }}</td>
                        <td>{{ $daysLeft !== null ? $daysLeft . ' days' : '-' }}</td>
                        <td>{{ $med->quantity }}</td>
                        <td><span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span></td>
                        <td style="text-align:center;">
                            @if(!$isExpired && $med->expiry_date)
                                <button class="btn btn-danger btn-sm" onclick="markExpired({{ $med->id }})">
                                    <i class="fas fa-clock"></i> Mark Expired
                                </button>
                            @endif
                            <a href="{{ route('medicines.edit', $med->id) }}" class="btn btn-outline btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:60px 0;color:#94a3b8;">
                            <i class="fas fa-clock" style="font-size:48px;display:block;margin-bottom:16px;"></i>
                            No expiring medicines found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:14px 20px;border-top:1px solid #eef2f6;" class="dark:border-slate-700">
        {{ $expiringMedicines->links() }}
    </div>
</div>

<!-- Toast Container -->
<div id="toastContainer" class="toast-container"></div>

@endsection

@push('scripts')
<script>
    // ─── Mark as expired ───
    function markExpired(id) {
        if (!confirm('Mark this medicine as expired?')) return;

        fetch('{{ route('expiry.mark-expired') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ medicine_id: id })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(err => showToast('Server error.', 'error'));
    }

    // ─── Toast function ───
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