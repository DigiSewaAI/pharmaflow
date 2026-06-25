{{-- resources/views/settings/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'PharmaFlow — Settings')
@section('page-title', 'Settings')

@section('content')
<div style="margin-bottom:20px;">
    <h2 style="font-size:22px;font-weight:700;color:#0f172a;" class="dark:text-slate-100">Settings</h2>
    <p style="color:#64748b;font-size:14px;" class="dark:text-slate-400">Configure your pharmacy</p>
</div>

<div style="display:flex;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
    <button class="tab-btn active" onclick="switchTab('general')">General</button>
    <button class="tab-btn" onclick="switchTab('pharmacy')">Pharmacy</button>
    <button class="tab-btn" onclick="switchTab('currency')">Currency</button>
    <button class="tab-btn" onclick="switchTab('tax')">Tax</button>
    <button class="tab-btn" onclick="switchTab('invoice')">Invoice</button>
    <button class="tab-btn" onclick="switchTab('theme')">Theme</button>
</div>

<form action="{{ route('settings.update') }}" method="POST" id="settingsForm">
    @csrf

    <!-- General -->
    <div id="tab-general" class="tab-content active">
        <div class="card">
            <h3 style="font-weight:600;margin-bottom:12px;">General Settings</h3>
            <div class="grid-2col">
                <div>
                    <label class="form-label">Timezone</label>
                    <input type="text" name="timezone" class="form-input" value="{{ Setting::get('timezone', 'Asia/Kathmandu') }}">
                </div>
            </div>
        </div>
    </div>

    <!-- Pharmacy -->
    <div id="tab-pharmacy" class="tab-content">
        <div class="card">
            <h3 style="font-weight:600;margin-bottom:12px;">Pharmacy Profile</h3>
            <div class="grid-2col">
                <div>
                    <label class="form-label">Pharmacy Name</label>
                    <input type="text" name="pharmacy_name" class="form-input" value="{{ Setting::get('pharmacy_name', 'PharmaFlow Pharmacy') }}">
                </div>
                <div>
                    <label class="form-label">Phone</label>
                    <input type="text" name="pharmacy_phone" class="form-input" value="{{ Setting::get('pharmacy_phone', '+977 98XXXXXXXX') }}">
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="pharmacy_email" class="form-input" value="{{ Setting::get('pharmacy_email', 'info@pharmaflow.com') }}">
                </div>
                <div>
                    <label class="form-label">Tax ID</label>
                    <input type="text" name="tax_id" class="form-input" value="{{ Setting::get('tax_id', '') }}">
                </div>
                <div class="col-span-2">
                    <label class="form-label">Address</label>
                    <input type="text" name="pharmacy_address" class="form-input" value="{{ Setting::get('pharmacy_address', 'Kathmandu, Nepal') }}">
                </div>
            </div>
        </div>
    </div>

    <!-- Currency -->
    <div id="tab-currency" class="tab-content">
        <div class="card">
            <h3 style="font-weight:600;margin-bottom:12px;">Currency Settings</h3>
            <div class="grid-2col">
                <div>
                    <label class="form-label">Currency Symbol</label>
                    <input type="text" name="currency_symbol" class="form-input" value="{{ Setting::get('currency_symbol', 'Rs') }}">
                </div>
                <div>
                    <label class="form-label">Currency Code</label>
                    <input type="text" name="currency_code" class="form-input" value="{{ Setting::get('currency_code', 'NPR') }}">
                </div>
            </div>
        </div>
    </div>

    <!-- Tax -->
    <div id="tab-tax" class="tab-content">
        <div class="card">
            <h3 style="font-weight:600;margin-bottom:12px;">Tax Settings</h3>
            <div class="grid-2col">
                <div>
                    <label class="form-label">Tax Rate (%)</label>
                    <input type="number" name="tax_rate" class="form-input" step="0.01" min="0" max="100" value="{{ Setting::get('tax_rate', 10) }}">
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice -->
    <div id="tab-invoice" class="tab-content">
        <div class="card">
            <h3 style="font-weight:600;margin-bottom:12px;">Invoice Settings</h3>
            <div class="grid-2col">
                <div>
                    <label class="form-label">Invoice Prefix</label>
                    <input type="text" name="invoice_prefix" class="form-input" value="{{ Setting::get('invoice_prefix', 'INV-') }}">
                </div>
                <div class="col-span-2">
                    <label class="form-label">Invoice Footer</label>
                    <input type="text" name="invoice_footer" class="form-input" value="{{ Setting::get('invoice_footer', 'Thank you for your business!') }}">
                </div>
            </div>
        </div>
    </div>

    <!-- Theme -->
    <div id="tab-theme" class="tab-content">
        <div class="card">
            <h3 style="font-weight:600;margin-bottom:12px;">Theme Settings</h3>
            <div>
                <label class="form-label">Default Theme</label>
                <select name="default_theme" class="form-input select-custom">
                    <option value="light" {{ Setting::get('default_theme') == 'light' ? 'selected' : '' }}>Light</option>
                    <option value="dark" {{ Setting::get('default_theme') == 'dark' ? 'selected' : '' }}>Dark</option>
                </select>
            </div>
        </div>
    </div>

    <div style="margin-top:20px;">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save All Settings</button>
    </div>
</form>

<!-- Toast -->
<div id="toastContainer" class="toast-container"></div>

@endsection

@push('scripts')
<script>
    function switchTab(tab) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));

        // Show selected
        document.getElementById('tab-' + tab).classList.add('active');
        document.querySelector(`.tab-btn[onclick="switchTab('${tab}')"]`).classList.add('active');
    }

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

    // Handle form submission with AJAX (optional) – we'll let normal form submit
</script>
@endpush