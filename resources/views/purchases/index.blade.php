{{-- resources/views/purchases/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'PharmaFlow — Purchases')
@section('page-title', 'Purchases')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
    <div>
        <h2 style="font-size:22px;font-weight:700;color:#0f172a;" class="dark:text-slate-100">Purchase Management</h2>
        <p style="color:#64748b;font-size:14px;" class="dark:text-slate-400">Manage purchase orders & suppliers</p>
    </div>
    <button class="btn btn-primary btn-sm" onclick="openModal('purchaseModal')">
        <i class="fas fa-plus"></i> New Purchase Order
    </button>
</div>

<!-- Filters -->
<div class="card mb-4">
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 items-end">
        <div>
            <label class="form-label">Search</label>
            <input type="text" name="search" placeholder="PO Number..." value="{{ request('search') }}" class="form-input">
        </div>
        <div>
            <label class="form-label">Status</label>
            <select name="status" class="form-input select-custom">
                <option value="">All</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Received</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
            <a href="{{ route('purchases.index') }}" class="btn btn-outline"><i class="fas fa-undo"></i> Reset</a>
        </div>
    </form>
</div>

<!-- Table -->
<div class="card" style="padding:0;overflow:hidden;">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>PO #</th>
                    <th>Supplier</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $purchase)
                    <tr>
                        <td><strong>{{ $purchase->po_number }}</strong></td>
                        <td>{{ $purchase->supplier?->name ?? 'Deleted' }}</td>
                        <td>{{ $purchase->order_date }}</td>
                        <td>Rs {{ number_format($purchase->total, 0) }}</td>
                        <td>
                            <span class="status-badge {{ $purchase->status == 'received' ? 'status-instock' : ($purchase->status == 'cancelled' ? 'status-expired' : 'status-pending') }}">
                                {{ ucfirst($purchase->status) }}
                            </span>
                        </td>
                        <td style="text-align:center;">
                            @if($purchase->status != 'received' && $purchase->status != 'cancelled')
                                <button class="btn btn-success btn-sm" onclick="receivePO({{ $purchase->id }})">
                                    <i class="fas fa-check"></i> Receive
                                </button>
                            @endif
                            @if($purchase->status != 'received')
                                <form action="{{ route('purchases.destroy', $purchase) }}" method="POST" style="display:inline-block;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Cancel this PO?')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:60px 0;color:#94a3b8;">
                            <i class="fas fa-truck" style="font-size:48px;display:block;margin-bottom:16px;"></i>
                            No purchase orders found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:14px 20px;border-top:1px solid #eef2f6;" class="dark:border-slate-700">
        {{ $purchases->links() }}
    </div>
</div>

<!-- ===== MODALS ===== -->
@include('purchases.partials.modals')

@endsection

@push('scripts')
<script>
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

    // Add item row to PO form
    let itemIndex = 0;
    function addItemRow() {
        const container = document.getElementById('poItemsContainer');
        const row = document.createElement('div');
        row.className = 'grid-2col';
        row.style.marginBottom = '10px';
        row.innerHTML = `
            <div>
                <label class="form-label">Medicine</label>
                <select name="items[${itemIndex}][medicine_id]" class="form-input select-custom" required>
                    <option value="">Select</option>
                    @foreach($medicines as $med)
                        <option value="{{ $med->id }}">{{ $med->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Quantity</label>
                <input type="number" name="items[${itemIndex}][quantity]" class="form-input" placeholder="0" required min="1">
            </div>
            <div>
                <label class="form-label">Unit Price</label>
                <input type="number" name="items[${itemIndex}][unit_price]" class="form-input" placeholder="0" required min="0" step="0.01">
            </div>
            <div style="display:flex;align-items:flex-end;">
                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.grid-2col').remove()">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(row);
        itemIndex++;
    }

    // Receive PO
    function receivePO(id) {
        if (!confirm('Receive this purchase order? Stock will be added.')) return;
        fetch(`/purchases/${id}/receive`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                location.reload();
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(err => showToast('Server error.', 'error'));
    }
</script>
@endpush