{{-- resources/views/users/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'PharmaFlow — Users')
@section('page-title', 'Users')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
    <div>
        <h2 style="font-size:22px;font-weight:700;color:#0f172a;" class="dark:text-slate-100">User Management</h2>
        <p style="color:#64748b;font-size:14px;" class="dark:text-slate-400">Manage users and their roles</p>
    </div>
    <button class="btn btn-primary btn-sm" onclick="openModal('userModal')">
        <i class="fas fa-user-plus"></i> Add User
    </button>
</div>

<!-- Filters -->
<div class="card mb-4">
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 items-end">
        <div>
            <label class="form-label">Search</label>
            <input type="text" name="search" placeholder="Name or email..." value="{{ request('search') }}" class="form-input">
        </div>
        <div>
            <label class="form-label">Role</label>
            <select name="role" class="form-input select-custom">
                <option value="">All Roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
            <a href="{{ route('users.index') }}" class="btn btn-outline"><i class="fas fa-undo"></i> Reset</a>
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
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @php
                                $role = $user->roles->first();
                                $roleName = $role ? ucfirst(str_replace('-', ' ', $role->name)) : 'No Role';
                                $roleClass = match($role?->name) {
                                    'super-admin' => 'status-active',
                                    'admin' => 'status-active',
                                    'pharmacist' => 'status-instock',
                                    'store-keeper' => 'status-pending',
                                    'accountant' => 'status-paid',
                                    default => 'status-pending',
                                };
                            @endphp
                            <span class="status-badge {{ $roleClass }}">{{ $roleName }}</span>
                        </td>
                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                        <td style="text-align:center;">
                            <button class="btn btn-outline btn-sm" onclick="editUser({{ $user->id }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            @if(auth()->id() != $user->id)
                                <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline-block;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:60px 0;color:#94a3b8;">
                            <i class="fas fa-users" style="font-size:48px;display:block;margin-bottom:16px;"></i>
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:14px 20px;border-top:1px solid #eef2f6;" class="dark:border-slate-700">
        {{ $users->links() }}
    </div>
</div>

<!-- ===== MODALS ===== -->
@include('users.partials.modals')

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

    function editUser(id) {
        fetch(`/users/${id}/edit`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const u = data.user;
                    document.getElementById('edit_user_name').value = u.name;
                    document.getElementById('edit_user_email').value = u.email;
                    document.getElementById('edit_user_role').value = data.current_role || '';
                    document.getElementById('editUserForm').action = `/users/${id}`;
                    openModal('editUserModal');
                } else {
                    showToast('Failed to load user.', 'error');
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