{{-- resources/views/medicines/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'PharmaFlow — Medicines')
@section('page-title', 'Medicines')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
    <div>
        <h2 style="font-size:22px;font-weight:700;color:#0f172a;" class="dark:text-slate-100">Medicine Management</h2>
        <p style="color:#64748b;font-size:14px;" class="dark:text-slate-400">Manage your pharmacy inventory</p>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <a href="{{ route('medicines.export') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-file-export"></i> Export
        </a>
        <button onclick="document.getElementById('importInput').click()" class="btn btn-outline btn-sm">
            <i class="fas fa-file-import"></i> Import
        </button>
        <form id="importForm" action="{{ route('medicines.import') }}" method="POST" enctype="multipart/form-data" style="display:none;">
            @csrf
            <input type="file" id="importInput" name="file" accept=".xlsx,.csv" onchange="this.form.submit()">
        </form>
        <button class="btn btn-primary btn-sm" onclick="openModal('medicineModal')">
            <i class="fas fa-plus"></i> Add Medicine
        </button>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
        <div>
            <label class="form-label">Search</label>
            <input type="text" name="search" placeholder="Name or batch..." 
                   value="{{ request('search') }}" class="form-input">
        </div>
        <div>
            <label class="form-label">Category</label>
            <select name="category_id" class="form-input select-custom">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Status</label>
            <select name="status" class="form-input select-custom">
                <option value="">All Status</option>
                <option value="in_stock" {{ request('status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> Filter
            </button>
            <a href="{{ route('medicines.index') }}" class="btn btn-outline">
                <i class="fas fa-undo"></i> Reset
            </a>
        </div>
    </form>
</div>

<!-- Success Message -->
@if(session('success'))
    <div style="display:flex;align-items:center;gap:12px;padding:12px 20px;border-radius:12px;background:#d1fae5;color:#065f46;border-left:4px solid #10b981;margin-bottom:20px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<!-- Table -->
<div class="card" style="padding:0;overflow:hidden;">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Medicine</th>
                    <th>Batch</th>
                    <th>Category</th>
                    <th>Supplier</th>
                    <th>Purchase</th>
                    <th>Sell</th>
                    <th>Qty</th>
                    <th>Expiry</th>
                    <th>Manufacture</th>
                    <th>Status</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($medicines as $med)
                    <tr>
                        <td><strong>{{ $med->name }}</strong></td>
                        <td>{{ $med->batch_number ?? '-' }}</td>
                        <td>{{ $med->category?->name ?? '-' }}</td>
                        <td>{{ $med->supplier?->name ?? '-' }}</td>
                        <td>Rs {{ number_format($med->purchase_price, 0) }}</td>
                        <td>Rs {{ number_format($med->selling_price, 0) }}</td>
                        <td>{{ $med->quantity }}</td>
                        <td>{{ $med->expiry_date ?? '-' }}</td>
                        <td>{{ $med->manufacture_date ?? '-' }}</td>
                        <td>
                            @php
                                $statusMap = [
                                    'in_stock' => ['label' => 'In Stock', 'class' => 'status-instock'],
                                    'low_stock' => ['label' => 'Low Stock', 'class' => 'status-low'],
                                    'expired' => ['label' => 'Expired', 'class' => 'status-expired'],
                                ];
                                $status = $statusMap[$med->status] ?? ['label' => ucfirst($med->status), 'class' => ''];
                            @endphp
                            <span class="status-badge {{ $status['class'] }}">{{ $status['label'] }}</span>
                        </td>
                        <td style="text-align:center;">
                            <button class="btn btn-outline btn-sm" onclick="editMedicine({{ $med->id }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('medicines.destroy', $med) }}" method="POST" style="display:inline-block;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" style="text-align:center;padding:60px 0;color:#94a3b8;">
                            <i class="fas fa-pills" style="font-size:48px;display:block;margin-bottom:16px;color:#cbd5e1;"></i>
                            <p style="font-size:16px;font-weight:500;color:#64748b;">No medicines found.</p>
                            <p style="font-size:13px;margin-top:4px;">Start by adding your first medicine!</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;padding:14px 20px;border-top:1px solid #eef2f6;" class="dark:border-slate-700">
        <span style="font-size:13px;color:#64748b;" class="dark:text-slate-400">
            Showing {{ $medicines->firstItem() ?? 0 }} – {{ $medicines->lastItem() ?? 0 }} of {{ $medicines->total() }} medicines
        </span>
        {{ $medicines->links() }}
    </div>
</div>

<!-- ===== ADD MEDICINE MODAL ===== -->
<div class="modal-overlay" id="medicineModal">
    <div class="modal">
        <h3 style="font-size:20px;font-weight:700;margin-bottom:16px;">
            <i class="fas fa-plus-circle text-blue-600 mr-2"></i> Add New Medicine
        </h3>
        <form action="{{ route('medicines.store') }}" method="POST">
            @csrf
            <div class="grid-2col">
                <div>
                    <label class="form-label">Medicine Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" class="form-input" placeholder="e.g. Paracetamol 500mg" required>
                </div>
                <div>
                    <label class="form-label">Batch Number</label>
                    <input type="text" name="batch_number" class="form-input" placeholder="BATCH-2026-001">
                </div>
                <div>
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-input select-custom">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Supplier</label>
                    <select name="supplier_id" class="form-input select-custom">
                        <option value="">Select Supplier</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Purchase Price (Rs)</label>
                    <input type="number" name="purchase_price" class="form-input" placeholder="0.00" step="0.01">
                </div>
                <div>
                    <label class="form-label">Selling Price (Rs)</label>
                    <input type="number" name="selling_price" class="form-input" placeholder="0.00" step="0.01">
                </div>
                <div>
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity" class="form-input" placeholder="0">
                </div>
                <div>
                    <label class="form-label">Expiry Date</label>
                    <input type="date" name="expiry_date" class="form-input">
                </div>
                <div>
                    <label class="form-label">Manufacture Date</label>
                    <input type="date" name="manufacture_date" class="form-input">
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:20px;justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="closeModal('medicineModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Medicine</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== EDIT MEDICINE MODAL ===== -->
<div class="modal-overlay" id="editMedicineModal">
    <div class="modal">
        <h3 style="font-size:20px;font-weight:700;margin-bottom:16px;">
            <i class="fas fa-edit text-blue-600 mr-2"></i> Edit Medicine
        </h3>
        <form id="editMedicineForm" method="POST">
            @csrf
            @method('PUT')
            <div class="grid-2col">
                <div>
                    <label class="form-label">Medicine Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="edit_name" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Batch Number</label>
                    <input type="text" name="batch_number" id="edit_batch_number" class="form-input">
                </div>
                <div>
                    <label class="form-label">Category</label>
                    <select name="category_id" id="edit_category_id" class="form-input select-custom">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Supplier</label>
                    <select name="supplier_id" id="edit_supplier_id" class="form-input select-custom">
                        <option value="">Select Supplier</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Purchase Price (Rs)</label>
                    <input type="number" name="purchase_price" id="edit_purchase_price" class="form-input" step="0.01">
                </div>
                <div>
                    <label class="form-label">Selling Price (Rs)</label>
                    <input type="number" name="selling_price" id="edit_selling_price" class="form-input" step="0.01">
                </div>
                <div>
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity" id="edit_quantity" class="form-input">
                </div>
                <div>
                    <label class="form-label">Expiry Date</label>
                    <input type="date" name="expiry_date" id="edit_expiry_date" class="form-input">
                </div>
                <div>
                    <label class="form-label">Manufacture Date</label>
                    <input type="date" name="manufacture_date" id="edit_manufacture_date" class="form-input">
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:20px;justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="closeModal('editMedicineModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Medicine</button>
            </div>
        </form>
    </div>
</div>

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

    // ─── Edit Medicine ───
    function editMedicine(id) {
        // Fetch medicine data via AJAX
        fetch(`/medicines/${id}/edit`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const med = data.medicine;
                    document.getElementById('edit_name').value = med.name;
                    document.getElementById('edit_batch_number').value = med.batch_number || '';
                    document.getElementById('edit_category_id').value = med.category_id || '';
                    document.getElementById('edit_supplier_id').value = med.supplier_id || '';
                    document.getElementById('edit_purchase_price').value = med.purchase_price || 0;
                    document.getElementById('edit_selling_price').value = med.selling_price || 0;
                    document.getElementById('edit_quantity').value = med.quantity || 0;
                    document.getElementById('edit_expiry_date').value = med.expiry_date || '';
                    document.getElementById('edit_manufacture_date').value = med.manufacture_date || '';
                    document.getElementById('editMedicineForm').action = `/medicines/${id}`;
                    openModal('editMedicineModal');
                } else {
                    showToast('Failed to load medicine data.', 'error');
                }
            })
            .catch(err => showToast('Server error.', 'error'));
    }

    // ─── Toast (fallback if not defined in dashboard.js) ───
    if (typeof showToast !== 'function') {
        window.showToast = function(message, type = 'info') {
            const container = document.getElementById('toastContainer');
            if (!container) return;
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            const icons = { success: 'fa-check-circle', error: 'fa-exclamation-circle', warning: 'fa-exclamation-triangle', info: 'fa-info-circle' };
            toast.innerHTML = `<i class="fas ${icons[type] || icons.info}"></i> ${message}`;
            container.appendChild(toast);
            setTimeout(() => { toast.style.opacity = '0'; toast.style.transform = 'translateX(20px)'; setTimeout(() => toast.remove(), 300); }, 3500);
        };
    }
</script>
@endpush