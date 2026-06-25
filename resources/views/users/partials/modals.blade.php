{{-- resources/views/users/partials/modals.blade.php --}}

<!-- ===== ADD USER MODAL ===== -->
<div class="modal-overlay" id="userModal">
    <div class="modal">
        <h3 style="font-size:20px;font-weight:700;margin-bottom:16px;">
            <i class="fas fa-user-plus text-blue-600 mr-2"></i> Add User
        </h3>
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="grid-2col">
                <div>
                    <label class="form-label">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" class="form-input" required minlength="8">
                </div>
                <div>
                    <label class="form-label">Confirm Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" class="form-input" required>
                </div>
                <div class="col-span-2">
                    <label class="form-label">Role <span class="text-red-500">*</span></label>
                    <select name="role" class="form-input select-custom" required>
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ ucfirst(str_replace('-', ' ', $role->name)) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:20px;justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="closeModal('userModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Create User</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== EDIT USER MODAL ===== -->
<div class="modal-overlay" id="editUserModal">
    <div class="modal">
        <h3 style="font-size:20px;font-weight:700;margin-bottom:16px;">
            <i class="fas fa-edit text-blue-600 mr-2"></i> Edit User
        </h3>
        <form id="editUserForm" method="POST">
            @csrf @method('PUT')
            <div class="grid-2col">
                <div>
                    <label class="form-label">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="edit_user_name" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="edit_user_email" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Password (leave blank to keep)</label>
                    <input type="password" name="password" class="form-input" minlength="8" placeholder="••••••••">
                </div>
                <div>
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-input" placeholder="••••••••">
                </div>
                <div class="col-span-2">
                    <label class="form-label">Role <span class="text-red-500">*</span></label>
                    <select name="role" id="edit_user_role" class="form-input select-custom" required>
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ ucfirst(str_replace('-', ' ', $role->name)) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:20px;justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="closeModal('editUserModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update User</button>
            </div>
        </form>
    </div>
</div>