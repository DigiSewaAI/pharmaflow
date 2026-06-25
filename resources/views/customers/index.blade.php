{{-- resources/views/customers/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'PharmaFlow — Customers')
@section('page-title', 'Customers')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
    <div>
        <h2 style="font-size:22px;font-weight:700;color:#0f172a;" class="dark:text-slate-100">Customer Management</h2>
        <p style="color:#64748b;font-size:14px;" class="dark:text-slate-400">Manage customer profiles & loyalty</p>
    </div>
    <button class="btn btn-primary btn-sm" onclick="openModal('customerModal')">
        <i class="fas fa-user-plus"></i> Add Customer
    </button>
</div>

<!-- Filters -->
<div class="card mb-4">
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
        <div>
            <label class="form-label">Search</label>
            <input type="text" name="search" placeholder="Name, phone, email..." value="{{ request('search') }}" class="form-input">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
            <a href="{{ route('customers.index') }}" class="btn btn-outline"><i class="fas fa-undo"></i> Reset</a>
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
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Loyalty Points</th>
                    <th>Total Purchases</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td><strong>{{ $customer->name }}</strong></td>
                        <td>{{ $customer->phone ?? '-' }}</td>
                        <td>{{ $customer->email ?? '-' }}</td>
                        <td>{{ $customer->loyalty_points ?? 0 }}</td>
                        <td>Rs {{ number_format($customer->sales->sum('grand_total') ?? 0, 0) }}</td>
                        <td style="text-align:center;">
                            <button class="btn btn-outline btn-sm" onclick="editCustomer({{ $customer->id }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('customers.destroy', $customer) }}" method="POST" style="display:inline-block;">
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
                            <i class="fas fa-users" style="font-size:48px;display:block;margin-bottom:16px;"></i>
                            No customers found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:14px 20px;border-top:1px solid #eef2f6;" class="dark:border-slate-700">
        {{ $customers->links() }}
    </div>
</div>

<!-- ===== MODAL ===== -->
<div class="modal-overlay" id="customerModal">
    <div class="modal">
        <h3 style="font-size:20px;font-weight:700;margin-bottom:16px;">
            <i class="fas fa-user-plus text-blue-600 mr-2"></i> Add Customer
        </h3>
        <form action="{{ route('customers.store') }}" method="POST">
            @csrf
            <div class="grid-2col">
                <div>
                    <label class="form-label">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-input">
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input">
                </div>
                <div>
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-input">
                </div>
                <div>
                    <label class="form-label">Loyalty Points</label>
                    <input type="number" name="loyalty_points" class="form-input" value="0">
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:20px;justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="closeModal('customerModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== EDIT MODAL ===== -->
<div class="modal-overlay" id="editCustomerModal">
    <div class="modal">
        <h3 style="font-size:20px;font-weight:700;margin-bottom:16px;">
            <i class="fas fa-edit text-blue-600 mr-2"></i> Edit Customer
        </h3>
        <form id="editCustomerForm" method="POST">
            @csrf @method('PUT')
            <div class="grid-2col">
                <div>
                    <label class="form-label">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="edit_customer_name" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" id="edit_customer_phone" class="form-input">
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" id="edit_customer_email" class="form-input">
                </div>
                <div>
                    <label class="form-label">Address</label>
                    <input type="text" name="address" id="edit_customer_address" class="form-input">
                </div>
                <div>
                    <label class="form-label">Loyalty Points</label>
                    <input type="number" name="loyalty_points" id="edit_customer_points" class="form-input">
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:20px;justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="closeModal('editCustomerModal')">Cancel</button>
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

    function editCustomer(id) {
        fetch(`/customers/${id}/edit`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const c = data.customer;
                    document.getElementById('edit_customer_name').value = c.name;
                    document.getElementById('edit_customer_phone').value = c.phone || '';
                    document.getElementById('edit_customer_email').value = c.email || '';
                    document.getElementById('edit_customer_address').value = c.address || '';
                    document.getElementById('edit_customer_points').value = c.loyalty_points || 0;
                    document.getElementById('editCustomerForm').action = `/customers/${id}`;
                    openModal('editCustomerModal');
                } else {
                    showToast('Failed to load customer.', 'error');
                }
            })
            .catch(err => showToast('Server error.', 'error'));
    }

    // Toast fallback
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