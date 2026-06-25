{{-- resources/views/pos/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'PharmaFlow — POS')
@section('page-title', 'Point of Sale')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
    <div>
        <h2 style="font-size:22px;font-weight:700;color:#0f172a;" class="dark:text-slate-100">Point of Sale</h2>
        <p style="color:#64748b;font-size:14px;" class="dark:text-slate-400">Quick checkout</p>
    </div>
    <div>
        <button class="btn btn-outline btn-sm" onclick="clearCart()"><i class="fas fa-trash"></i> Clear Cart</button>
        <a href="{{ route('sales.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-list"></i> Sales History</a>
    </div>
</div>

<div class="pos-grid">
    <!-- Left: Products & Cart -->
    <div class="card">
        <!-- Search -->
        <div style="display:flex;gap:10px;margin-bottom:16px;">
            <input class="form-input" placeholder="Search medicine by name or barcode..." id="posSearch" onkeydown="if(event.key==='Enter') addToCart()">
            <button class="btn btn-primary" onclick="addToCart()"><i class="fas fa-search"></i></button>
        </div>

        <!-- Quick product list (optional) -->
        <div style="margin-bottom:12px;">
            <p style="font-size:13px;font-weight:500;color:#64748b;" class="dark:text-slate-400">Quick Select:</p>
            <div style="display:flex;flex-wrap:wrap;gap:6px;margin-top:4px;">
                @foreach($medicines->take(6) as $med)
                    <button class="btn btn-outline btn-sm" onclick="quickAdd({{ $med->id }}, '{{ $med->name }}', {{ $med->selling_price }}, {{ $med->quantity }})">
                        {{ $med->name }} ({{ $med->quantity }})
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Cart Items -->
        <div style="max-height:420px;overflow-y:auto;" id="posCartItems">
            <div style="text-align:center;padding:40px 0;color:#94a3b8;font-size:14px;">
                <i class="fas fa-shopping-cart" style="font-size:32px;display:block;margin-bottom:12px;"></i>
                Cart is empty<br><span style="font-size:12px;">Search and add medicines</span>
            </div>
        </div>
    </div>

    <!-- Right: Order Summary -->
    <div class="card" style="display:flex;flex-direction:column;gap:14px;">
        <h3 style="font-size:16px;font-weight:600;color:#0f172a;" class="dark:text-slate-100">Order Summary</h3>
        <div style="display:flex;justify-content:space-between;font-size:14px;">
            <span>Items</span>
            <span id="posItemCount">0</span>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:14px;">
            <span>Subtotal</span>
            <span id="posSubtotal">Rs 0</span>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:14px;">
            <span>Tax (10%)</span>
            <span id="posTax">Rs 0</span>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:14px;">
            <span>Discount</span>
            <span id="posDiscount">Rs 0</span>
        </div>
        <div style="border-top:2px solid #e2e8f0;padding-top:12px;display:flex;justify-content:space-between;font-size:20px;font-weight:700;">
            <span>Total</span>
            <span id="posTotal">Rs 0</span>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:6px;">
            <div>
                <label class="form-label">Discount %</label>
                <input class="form-input" placeholder="0" id="posDiscountInput" oninput="updateTotals()" style="text-align:center;">
            </div>
            <div>
                <label class="form-label">Customer</label>
                <select class="form-input select-custom" id="posCustomer">
                    <option value="">Walk-in</option>
                    @foreach($customers as $cust)
                        <option value="{{ $cust->id }}">{{ $cust->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="display:flex;gap:10px;margin-top:4px;">
            <button class="btn btn-success" style="flex:1;" onclick="processSale()">
                <i class="fas fa-check"></i> Pay & Invoice
            </button>
            <button class="btn btn-outline" onclick="clearCart()"><i class="fas fa-trash"></i></button>
        </div>
    </div>
</div>

<!-- Toast Container (used by showToast) -->
<div id="toastContainer" class="toast-container"></div>

@endsection

@push('scripts')
<script>
    // ─── Cart state ───
    let cart = [];

    // ─── Add to Cart via search ───
    function addToCart() {
        const search = document.getElementById('posSearch').value.trim();
        if (!search) {
            showToast('Please search for a medicine.', 'warning');
            return;
        }
        
        // Find medicine by name or batch (using data from controller)
        const medicines = @json($medicines);
        const found = medicines.find(m => 
            m.name.toLowerCase().includes(search.toLowerCase()) || 
            (m.batch_number && m.batch_number.toLowerCase().includes(search.toLowerCase()))
        );
        
        if (!found) {
            showToast('Medicine not found.', 'error');
            return;
        }
        
        quickAdd(found.id, found.name, found.selling_price, found.quantity);
        document.getElementById('posSearch').value = '';
    }

    // ─── Quick add from button ───
    function quickAdd(id, name, price, maxQty) {
        // Check if already in cart
        const existing = cart.find(item => item.id === id);
        if (existing) {
            if (existing.quantity >= maxQty) {
                showToast('Not enough stock.', 'error');
                return;
            }
            existing.quantity += 1;
        } else {
            if (maxQty < 1) {
                showToast('Out of stock.', 'error');
                return;
            }
            cart.push({ id, name, price, quantity: 1, maxQty });
        }
        renderCart();
        updateTotals();
        showToast(`Added ${name} to cart.`, 'success');
    }

    // ─── Render cart items ───
    function renderCart() {
        const container = document.getElementById('posCartItems');
        if (cart.length === 0) {
            container.innerHTML = `
                <div style="text-align:center;padding:40px 0;color:#94a3b8;font-size:14px;">
                    <i class="fas fa-shopping-cart" style="font-size:32px;display:block;margin-bottom:12px;"></i>
                    Cart is empty<br><span style="font-size:12px;">Search and add medicines</span>
                </div>`;
            return;
        }

        container.innerHTML = cart.map((item, index) => `
            <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #f1f5f9;" class="dark:border-slate-700">
                <div>
                    <span style="font-weight:500;">${item.name}</span>
                    <br>
                    <span style="font-size:12px;color:#64748b;" class="dark:text-slate-400">
                        Rs ${item.price} × ${item.quantity}
                    </span>
                </div>
                <div style="display:flex;align-items:center;gap:8px;">
                    <button class="btn btn-outline btn-sm" onclick="changeQty(${index}, -1)"><i class="fas fa-minus"></i></button>
                    <span style="font-weight:600;min-width:24px;text-align:center;">${item.quantity}</span>
                    <button class="btn btn-outline btn-sm" onclick="changeQty(${index}, 1)"><i class="fas fa-plus"></i></button>
                    <button class="btn btn-danger btn-sm" onclick="removeItem(${index})"><i class="fas fa-times"></i></button>
                </div>
            </div>
        `).join('');
    }

    // ─── Change quantity ───
    function changeQty(index, delta) {
        const item = cart[index];
        const newQty = item.quantity + delta;
        if (newQty < 1) {
            cart.splice(index, 1);
        } else if (newQty > item.maxQty) {
            showToast('Not enough stock.', 'error');
            return;
        } else {
            item.quantity = newQty;
        }
        renderCart();
        updateTotals();
    }

    // ─── Remove item ───
    function removeItem(index) {
        cart.splice(index, 1);
        renderCart();
        updateTotals();
        showToast('Item removed.', 'info');
    }

    // ─── Clear cart ───
    function clearCart() {
        if (cart.length === 0) return;
        if (!confirm('Clear entire cart?')) return;
        cart = [];
        renderCart();
        updateTotals();
        showToast('Cart cleared.', 'warning');
    }

    // ─── Update totals ───
    function updateTotals() {
        let subtotal = 0;
        let count = 0;
        cart.forEach(item => {
            subtotal += item.price * item.quantity;
            count += item.quantity;
        });

        const discountPct = parseFloat(document.getElementById('posDiscountInput').value) || 0;
        const taxPct = 10; // fixed for now, can be from settings later

        const discount = subtotal * (discountPct / 100);
        const tax = (subtotal - discount) * (taxPct / 100);
        const total = subtotal - discount + tax;

        document.getElementById('posItemCount').textContent = count;
        document.getElementById('posSubtotal').textContent = `Rs ${subtotal.toFixed(0)}`;
        document.getElementById('posTax').textContent = `Rs ${tax.toFixed(0)}`;
        document.getElementById('posDiscount').textContent = `Rs ${discount.toFixed(0)}`;
        document.getElementById('posTotal').textContent = `Rs ${total.toFixed(0)}`;
    }

    // ─── Process sale ───
    function processSale() {
        if (cart.length === 0) {
            showToast('Cart is empty.', 'error');
            return;
        }

        const customerId = document.getElementById('posCustomer').value;
        const discount = parseFloat(document.getElementById('posDiscountInput').value) || 0;

        // Prepare data
        const items = cart.map(item => ({
            medicine_id: item.id,
            quantity: item.quantity
        }));

        fetch('{{ route('pos.checkout') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                customer_id: customerId || null,
                discount: discount,
                tax: 10
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(`Sale completed! Invoice #${data.invoice_number} - Rs ${data.grand_total}`, 'success');
                cart = [];
                renderCart();
                updateTotals();
                document.getElementById('posDiscountInput').value = '';
                document.getElementById('posCustomer').value = '';
                // Optionally redirect to invoice
                // window.location.href = `/pos/invoice/${data.sale_id}`;
            } else {
                showToast(data.message || 'Sale failed.', 'error');
            }
        })
        .catch(err => {
            showToast('Server error. Please try again.', 'error');
            console.error(err);
        });
    }

    // ─── Toast (fallback) ───
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

    // ─── Keyboard shortcut: Ctrl+Enter to checkout ───
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            e.preventDefault();
            processSale();
        }
    });

    // Initialize
    renderCart();
    updateTotals();
</script>
@endpush