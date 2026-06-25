{{-- resources/views/sales/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'PharmaFlow — Sales')
@section('page-title', 'Sales History')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
    <div>
        <h2 style="font-size:22px;font-weight:700;color:#0f172a;" class="dark:text-slate-100">Sales History</h2>
        <p style="color:#64748b;font-size:14px;" class="dark:text-slate-400">View all completed sales</p>
    </div>
    <div>
        <a href="{{ route('pos.index') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> New Sale</a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
        <div>
            <label class="form-label">Search</label>
            <input type="text" name="search" placeholder="Invoice #..." value="{{ request('search') }}" class="form-input">
        </div>
        <div>
            <label class="form-label">Status</label>
            <select name="status" class="form-input select-custom">
                <option value="">All</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div>
            <label class="form-label">From</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input">
        </div>
        <div>
            <label class="form-label">To</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
            <a href="{{ route('sales.index') }}" class="btn btn-outline"><i class="fas fa-undo"></i> Reset</a>
        </div>
    </form>
</div>

<!-- Sales Table -->
<div class="card" style="padding:0;overflow:hidden;">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Cashier</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                    <tr>
                        <td><strong>{{ $sale->invoice_number }}</strong></td>
                        <td>{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $sale->customer?->name ?? 'Walk-in' }}</td>
                        <td>{{ $sale->user?->name ?? 'Unknown' }}</td>
                        <td>Rs {{ number_format($sale->grand_total, 0) }}</td>
                        <td>
                            <span class="status-badge {{ $sale->status == 'completed' ? 'status-instock' : 'status-expired' }}">
                                {{ ucfirst($sale->status) }}
                            </span>
                        </td>
                        <td style="text-align:center;">
                            <a href="{{ route('sales.show', $sale) }}" class="btn btn-outline btn-sm"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('pos.invoice', $sale) }}" class="btn btn-outline btn-sm"><i class="fas fa-print"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:60px 0;color:#94a3b8;">
                            <i class="fas fa-receipt" style="font-size:48px;display:block;margin-bottom:16px;"></i>
                            <p style="font-size:16px;font-weight:500;">No sales found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:14px 20px;border-top:1px solid #eef2f6;" class="dark:border-slate-700">
        {{ $sales->links() }}
    </div>
</div>
@endsection