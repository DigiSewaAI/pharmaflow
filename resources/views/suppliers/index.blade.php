{{-- resources/views/suppliers/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'PharmaFlow — Suppliers')
@section('page-title', 'Suppliers')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
    <div>
        <h2 style="font-size:22px;font-weight:700;color:#0f172a;" class="dark:text-slate-100">Supplier Management</h2>
        <p style="color:#64748b;font-size:14px;" class="dark:text-slate-400">Manage supplier profiles & outstanding</p>
    </div>
    <button class="btn btn-primary btn-sm" onclick="openModal('supplierModal')">
        <i class="fas fa-plus"></i> Add Supplier
    </button>
</div>

<!-- Filters -->
<div class="card mb-4">
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
        <div>
            <label class="form-label">Search</label>
            <input type="text" name="search" placeholder="Name, contact person..." value="{{ request('search') }}" class="form-input">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
            <a href="{{ route('suppliers.index') }}" class="btn btn-outline"><i class="fas fa-undo"></i> Reset</a>
        </div>
    </form>
</div>

<!-- Table -->
<div class="card" style="padding:0;overflow:hidden;">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Contact Person</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Outstanding</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $supplier)
                    <tr>
                        <td><strong>{{ $supplier->name }}</strong></td>
                        <td>{{ $supplier->contact_person ?? '-' }}</td>
                        <td>{{ $supplier->phone ?? '-' }}</td>
                        <td>{{ $supplier->email ?? '-' }}</td>
                        <td>Rs {{ number_format($supplier->outstanding_balance ?? 0, 0) }}</td>
                        <td style="text-align:center;">
                            <button class="btn btn-outline btn-sm" onclick="editSupplier({{ $supplier->id }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" style="display:inline-block;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:60px 0;color:#94a3b8;">
                            <i class="fas fa-building" style="font-size:48px;display:block;margin-bottom:16px;"></i>
                            No suppliers found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:14px 20px;border-top:1px solid #eef2f6;" class="dark:border-slate-700">
        {{ $suppliers->links() }}
    </div>
</div>

<!-- ===== MODALS ===== -->
<div class="modal-overlay" id="supplierModal">
    <div class="modal">
        <h3 style="font-size:20px;font-weight:700;margin-bottom:16px;">
            <i class="fas fa-building text-blue-600 mr-2"></i> Add Supplier
        </h3>
        <form action="{{ route('suppliers.store') }}" method="POST">
            @csrf
            <div class="grid-2col">
                <div>
                    <label class="form-label">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Contact Person</label>
                    <input type="text" name="contact_person" class="form-input">
                </div>
                <div>
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-input">
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input">
                </div>
                <div class="col-span-2">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-input">
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:20px;justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="closeModal('supplierModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== EDIT MODAL ===== -->
<div class="modal-overlay" id="editSupplierModal">
    <div class="modal">
        <h3 style="font-size:20px;font-weight:700;margin-bottom:16px;">
            <i class="fas fa-edit text-blue-600 mr-2"></i> Edit Supplier
        </h3>
        <form id="editSupplierForm" method="POST">
            @csrf @method('PUT')
            <div class="grid-2col">
                <div>
                    <label class="form-label">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="edit_supplier_name" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Contact Person</label>
                    <input type="text" name="contact_person" id="edit_supplier_contact" class="form-input">
                </div>
                <div>
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" id="edit_supplier_phone" class="form-input">
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" id="edit_supplier_email" class="form-input">
                </div>
                <div class="col-span-2">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" id="edit_supplier_address" class="form-input">
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:20px;justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="closeModal('editSupplierModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
            </div>
        </form>
    </div>
</div>

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

    function editSupplier(id) {
        fetch(`/suppliers/${id}/edit`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const s = data.supplier;
                    document.getElementById('edit_supplier_name').value = s.name;
                    document.getElementById('edit_supplier_contact').value = s.contact_person || '';
                    document.getElementById('edit_supplier_phone').value = s.phone || '';
                    document.getElementById('edit_supplier_email').value = s.email || '';
                    document.getElementById('edit_supplier_address').value = s.address || '';
                    document.getElementById('editSupplierForm').action = `/suppliers/${id}`;
                    openModal('editSupplierModal');
                } else {
                    showToast('Failed to load supplier.', 'error');
                }
            })
            .catch(err => showToast('Server error.', 'error'));
    }
</script>
@endpush