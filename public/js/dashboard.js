// ============================================================
// PHARMAFLOW DASHBOARD — LARAVEL EDITION
// ============================================================

// ─── DATA FROM BLADE ───
let medicines = window.medicinesData || [];
const user = window.user || { name: 'User' };

// ─── STATE ───
let medPage = 0,
    medPageSize = 8;
let cart = [];
let currentPage = 'dashboard';
let chartInstances = {};
let cmdFiltered = [],
    cmdIndex = -1;

// ─── COMMANDS ───
const commands = [
    { name: 'Dashboard', action: () => navigate('dashboard') },
    { name: 'Medicines', action: () => navigate('medicines') },
    { name: 'Inventory', action: () => navigate('inventory') },
    { name: 'POS', action: () => navigate('pos') },
    { name: 'Purchases', action: () => navigate('purchases') },
    { name: 'Customers', action: () => navigate('customers') },
    { name: 'Suppliers', action: () => navigate('suppliers') },
    { name: 'Reports', action: () => navigate('reports') },
    { name: 'Expiry Center', action: () => navigate('expiry') },
    { name: 'Notifications', action: () => navigate('notifications') },
    { name: 'Users', action: () => navigate('users') },
    { name: 'Settings', action: () => navigate('settings') },
    { name: 'Subscription', action: () => navigate('subscription') },
    { name: 'Help Center', action: () => navigate('help') },
    { name: 'Add Medicine', action: () => openModal('medicineModal') },
    { name: 'New Sale', action: () => navigate('pos') },
    { name: 'Stock In', action: () => openModal('stockInModal') },
    { name: 'Stock Out', action: () => openModal('stockOutModal') },
    { name: 'Toggle Theme', action: () => toggleTheme() },
];

// ─── CSRF TOKEN ───
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
}

// ─── NAVIGATION ───
function navigate(page) {
    document.querySelectorAll('#dashboardMain .page').forEach(p => p.classList.remove('active'));
    const target = document.getElementById('page-' + page);
    if (target) target.classList.add('active');

    document.querySelectorAll('#dashboardSidebar .nav-item').forEach(n => n.classList.remove('active'));
    const navItem = document.querySelector(`#dashboardSidebar .nav-item[data-page="${page}"]`);
    if (navItem) navItem.classList.add('active');

    const titles = {
        dashboard: 'Dashboard',
        medicines: 'Medicines',
        inventory: 'Inventory',
        pos: 'POS',
        purchases: 'Purchases',
        customers: 'Customers',
        suppliers: 'Suppliers',
        reports: 'Reports',
        expiry: 'Expiry Center',
        notifications: 'Notifications',
        users: 'Users',
        settings: 'Settings',
        subscription: 'Subscription',
        help: 'Help Center'
    };
    document.getElementById('dashboardTitle').textContent = titles[page] || page.charAt(0).toUpperCase() + page.slice(1);
    currentPage = page;

    if (window.innerWidth <= 1024) document.getElementById('dashboardSidebar').classList.remove('open');
    if (page === 'medicines') renderMedicines();
    if (page === 'pos') { renderCart(); updatePosTotals(); }
    if (page === 'dashboard') setTimeout(initCharts, 150);
}

function toggleDashboardSidebar() {
    document.getElementById('dashboardSidebar').classList.toggle('open');
}

// ─── MEDICINE CRUD (AJAX) ───
function renderMedicines() {
    const search = (document.getElementById('medSearch')?.value || '').toLowerCase();
    const cat = document.getElementById('medCategoryFilter')?.value || '';
    const status = document.getElementById('medStatusFilter')?.value || '';
    let filtered = medicines.filter(m => {
        const matchName = m.name.toLowerCase().includes(search);
        const matchCat = !cat || m.category === cat;
        const matchStatus = !status || m.status === status;
        return matchName && matchCat && matchStatus;
    });
    const totalPages = Math.ceil(filtered.length / medPageSize) || 1;
    if (medPage >= totalPages) medPage = totalPages - 1;
    if (medPage < 0) medPage = 0;
    const start = medPage * medPageSize;
    const pageItems = filtered.slice(start, start + medPageSize);

    const tbody = document.getElementById('medicinesTableBody');
    if (!tbody) return;
    if (pageItems.length === 0) {
        tbody.innerHTML = `<tr><td colspan="10" style="text-align:center;padding:40px 0;color:#94a3b8;">No medicines found</td></tr>`;
    } else {
        tbody.innerHTML = pageItems.map(m => `
            <tr>
                <td><strong>${m.name}</strong></td>
                <td>${m.batch_number || m.batch}</td>
                <td>${m.category?.name || m.category}</td>
                <td>${m.supplier?.name || m.supplier}</td>
                <td>Rs ${(m.purchase_price || m.purchase).toFixed(0)}</td>
                <td>Rs ${(m.selling_price || m.sell).toFixed(0)}</td>
                <td>${m.quantity || m.qty}</td>
                <td>${m.expiry_date || m.expiry}</td>
                <td><span class="status-badge ${(m.status || '').toLowerCase() === 'in stock' ? 'status-instock' : (m.status || '').toLowerCase() === 'low stock' ? 'status-low' : 'status-expired'}">${m.status || 'Unknown'}</span></td>
                <td style="text-align:center;">
                    <button class="btn btn-outline btn-sm" onclick="editMedicine(${m.id})"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-danger btn-sm" onclick="deleteMedicine(${m.id})"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        `).join('');
    }
    document.getElementById('medCount').textContent = `Showing ${pageItems.length} of ${filtered.length} medicines`;
    document.getElementById('medPageInfo').textContent = `${medPage + 1} / ${totalPages}`;
}

function filterMedicines() { medPage = 0; renderMedicines(); }

function changeMedPage(d) {
    const search = (document.getElementById('medSearch')?.value || '').toLowerCase();
    const cat = document.getElementById('medCategoryFilter')?.value || '';
    const status = document.getElementById('medStatusFilter')?.value || '';
    let filtered = medicines.filter(m => {
        const matchName = m.name.toLowerCase().includes(search);
        const matchCat = !cat || m.category === cat;
        const matchStatus = !status || m.status === status;
        return matchName && matchCat && matchStatus;
    });
    const totalPages = Math.ceil(filtered.length / medPageSize) || 1;
    const newPage = medPage + d;
    if (newPage >= 0 && newPage < totalPages) { medPage = newPage; renderMedicines(); }
}

function saveMedicine() {
    const name = document.getElementById('medName').value.trim();
    const batch = document.getElementById('medBatch').value.trim();
    const category = document.getElementById('medCategory').value;
    const supplier = document.getElementById('medSupplier').value.trim();
    const purchase = parseFloat(document.getElementById('medPurchase').value) || 0;
    const sell = parseFloat(document.getElementById('medSell').value) || 0;
    const qty = parseInt(document.getElementById('medQty').value) || 0;
    const expiry = document.getElementById('medExpiry').value;
    if (!name || !batch) { showToast('Please fill in required fields', 'error'); return; }

    fetch('/medicines', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken()
        },
        body: JSON.stringify({
            name,
            batch_number: batch,
            category_id: category,
            supplier_name: supplier,
            purchase_price: purchase,
            selling_price: sell,
            quantity: qty,
            expiry_date: expiry || '2027-01-01',
            status: qty <= 0 ? 'Expired' : qty < 30 ? 'Low Stock' : 'In Stock'
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            medicines.push(data.medicine);
            closeModal('medicineModal');
            renderMedicines();
            showToast(`Added ${name} to inventory`, 'success');
            ['medName','medBatch','medSupplier','medPurchase','medSell','medQty','medExpiry'].forEach(id => document.getElementById(id).value = '');
        } else {
            showToast(data.message || 'Error saving medicine', 'error');
        }
    })
    .catch(err => showToast('Server error', 'error'));
}

function editMedicine(id) {
    const m = medicines.find(x => x.id === id);
    if (!m) return;
    const newQty = prompt(`Edit quantity for ${m.name} (current: ${m.quantity || m.qty})`, m.quantity || m.qty);
    if (newQty !== null) {
        const q = parseInt(newQty);
        if (!isNaN(q) && q >= 0) {
            fetch(`/medicines/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: JSON.stringify({ quantity: q, status: q <= 0 ? 'Expired' : q < 30 ? 'Low Stock' : 'In Stock' })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const idx = medicines.findIndex(x => x.id === id);
                    if (idx !== -1) {
                        medicines[idx].quantity = q;
                        medicines[idx].status = q <= 0 ? 'Expired' : q < 30 ? 'Low Stock' : 'In Stock';
                    }
                    renderMedicines();
                    showToast(`Updated ${m.name}`, 'success');
                } else {
                    showToast(data.message || 'Error updating', 'error');
                }
            })
            .catch(err => showToast('Server error', 'error'));
        }
    }
}

function deleteMedicine(id) {
    if (!confirm('Are you sure you want to delete this medicine?')) return;
    fetch(`/medicines/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': getCsrfToken()
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            medicines = medicines.filter(m => m.id !== id);
            renderMedicines();
            showToast('Medicine deleted', 'warning');
        } else {
            showToast(data.message || 'Error deleting', 'error');
        }
    })
    .catch(err => showToast('Server error', 'error'));
}

// ─── POS ───
function addToCart() {
    const search = document.getElementById('posSearch').value.trim();
    if (!search) { showToast('Please search for a medicine', 'warning'); return; }
    const found = medicines.find(m => m.name.toLowerCase().includes(search.toLowerCase()) || (m.batch_number || m.batch || '').toLowerCase().includes(search.toLowerCase()));
    if (!found) { showToast('Medicine not found', 'error'); return; }
    const qtyAvailable = found.quantity || found.qty || 0;
    if (qtyAvailable <= 0) { showToast('Out of stock', 'error'); return; }
    const existing = cart.find(c => c.id === found.id);
    if (existing) {
        if (existing.qty >= qtyAvailable) { showToast('Not enough stock', 'error'); return; }
        existing.qty += 1;
    } else {
        cart.push({ ...found, qty: 1 });
    }
    renderCart();
    document.getElementById('posSearch').value = '';
    updatePosTotals();
    showToast(`Added ${found.name} to cart`, 'success');
}

function renderCart() {
    const container = document.getElementById('posCartItems');
    if (!container) return;
    if (cart.length === 0) {
        container.innerHTML = `<div style="text-align:center;padding:40px 0;color:#94a3b8;font-size:14px;"><i class="fas fa-shopping-cart" style="font-size:32px;display:block;margin-bottom:12px;"></i>Cart is empty<br><span style="font-size:12px;">Search and add medicines</span></div>`;
        return;
    }
    container.innerHTML = cart.map((c, i) => `
        <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #f1f5f9;" class="dark:border-slate-700">
            <div><span style="font-weight:500;">${c.name}</span><br><span style="font-size:12px;color:#64748b;" class="dark:text-slate-400">Rs ${(c.selling_price || c.sell).toFixed(0)} × ${c.qty}</span></div>
            <div style="display:flex;align-items:center;gap:8px;">
                <button class="btn btn-outline btn-sm" onclick="changeCartQty(${i}, -1)"><i class="fas fa-minus"></i></button>
                <span style="font-weight:600;min-width:24px;text-align:center;">${c.qty}</span>
                <button class="btn btn-outline btn-sm" onclick="changeCartQty(${i}, 1)"><i class="fas fa-plus"></i></button>
                <button class="btn btn-danger btn-sm" onclick="removeFromCart(${i})"><i class="fas fa-times"></i></button>
            </div>
        </div>
    `).join('');
}

function changeCartQty(i, d) {
    if (i < 0 || i >= cart.length) return;
    const available = cart[i].quantity || cart[i].qty || 0;
    if (d > 0 && cart[i].qty >= available) { showToast('Not enough stock', 'error'); return; }
    cart[i].qty += d;
    if (cart[i].qty <= 0) { cart.splice(i, 1); }
    renderCart();
    updatePosTotals();
}

function removeFromCart(i) {
    cart.splice(i, 1);
    renderCart();
    updatePosTotals();
}

function clearCart() {
    cart = [];
    renderCart();
    updatePosTotals();
    showToast('Cart cleared', 'warning');
}

function updatePosTotals() {
    const subtotal = cart.reduce((sum, c) => sum + (c.selling_price || c.sell) * c.qty, 0);
    const discountPct = parseFloat(document.getElementById('posDiscountInput').value) || 0;
    const discount = subtotal * (discountPct / 100);
    const tax = (subtotal - discount) * 0.10;
    const total = subtotal - discount + tax;
    document.getElementById('posSubtotal').textContent = `Rs ${subtotal.toFixed(0)}`;
    document.getElementById('posTax').textContent = `Rs ${tax.toFixed(0)}`;
    document.getElementById('posDiscount').textContent = `Rs ${discount.toFixed(0)}`;
    document.getElementById('posTotal').textContent = `Rs ${total.toFixed(0)}`;
}

function processSale() {
    if (cart.length === 0) { showToast('Cart is empty', 'error'); return; }
    // In a real app, you'd POST to /sales
    cart.forEach(c => {
        const med = medicines.find(m => m.id === c.id);
        if (med) {
            med.quantity = (med.quantity || med.qty || 0) - c.qty;
            med.status = med.quantity <= 0 ? 'Expired' : med.quantity < 30 ? 'Low Stock' : 'In Stock';
            // Update via AJAX to keep server in sync
            fetch(`/medicines/${med.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: JSON.stringify({ quantity: med.quantity, status: med.status })
            }).catch(err => console.error('Failed to update stock', err));
        }
    });
    const total = document.getElementById('posTotal').textContent;
    showToast(`Sale completed! Invoice #INV-${String(Date.now()).slice(-6)} for ${total}`, 'success');
    cart = [];
    renderCart();
    updatePosTotals();
    renderMedicines();
    document.getElementById('posCustomer').value = '';
}

// ─── MODALS ───
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

// ─── TOASTS ───
function showToast(message, type = 'info') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    const icons = { success: 'fa-check-circle', error: 'fa-exclamation-circle', warning: 'fa-exclamation-triangle', info: 'fa-info-circle' };
    toast.innerHTML = `<i class="fas ${icons[type] || icons.info}"></i> ${message}`;
    container.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; toast.style.transform = 'translateX(20px)'; setTimeout(() => toast.remove(), 300); }, 3500);
}

// ─── THEME ───
function toggleTheme() {
    document.body.classList.toggle('dark');
    const icon = document.getElementById('dashThemeIcon');
    if (icon) icon.className = document.body.classList.contains('dark') ? 'fas fa-sun' : 'fas fa-moon';
    localStorage.setItem('theme', document.body.classList.contains('dark') ? 'dark' : 'light');
    setTimeout(initCharts, 150);
}

// ─── SETTINGS TABS ───
function switchSettingsTab(tab) {
    const content = document.getElementById('settingsContent');
    const tabs = {
        profile: `<div class="card"><h3 style="font-weight:600;margin-bottom:12px;">Pharmacy Profile</h3><div class="grid-2col"><div><label class="form-label">Pharmacy Name</label><input class="form-input" value="PharmaFlow Pharmacy"></div><div><label class="form-label">Email</label><input class="form-input" value="info@pharmaflow.com"></div><div><label class="form-label">Phone</label><input class="form-input" value="+1 234 567 890"></div><div><label class="form-label">Address</label><input class="form-input" value="123 Main St, New York, NY 10001"></div></div><button class="btn btn-primary" style="margin-top:14px;" onclick="showToast('Settings saved!','success')">Save Changes</button></div>`,
        billing: `<div class="card"><h3 style="font-weight:600;margin-bottom:12px;">Billing Settings</h3><div class="grid-2col"><div><label class="form-label">Invoice Prefix</label><input class="form-input" value="INV-"></div><div><label class="form-label">Invoice Footer</label><input class="form-input" value="Thank you for your business"></div></div><button class="btn btn-primary" style="margin-top:14px;" onclick="showToast('Billing settings saved!','success')">Save</button></div>`,
        taxes: `<div class="card"><h3 style="font-weight:600;margin-bottom:12px;">Tax Settings</h3><div class="grid-2col"><div><label class="form-label">Sales Tax (%)</label><input class="form-input" type="number" value="10"></div><div><label class="form-label">Tax ID</label><input class="form-input" value="TAX-12345"></div></div><button class="btn btn-primary" style="margin-top:14px;" onclick="showToast('Tax settings saved!','success')">Save</button></div>`,
        currency: `<div class="card"><h3 style="font-weight:600;margin-bottom:12px;">Currency Settings</h3><div class="grid-2col"><div><label class="form-label">Currency Symbol</label><input class="form-input" value="Rs"></div><div><label class="form-label">Currency Code</label><input class="form-input" value="NPR"></div></div><button class="btn btn-primary" style="margin-top:14px;" onclick="showToast('Currency settings saved!','success')">Save</button></div>`,
        theme: `<div class="card"><h3 style="font-weight:600;margin-bottom:12px;">Theme Settings</h3><div style="display:flex;gap:16px;"><button class="btn ${document.body.classList.contains('dark')?'btn-outline':'btn-primary'}" onclick="if(document.body.classList.contains('dark')){toggleTheme()}">Light</button><button class="btn ${document.body.classList.contains('dark')?'btn-primary':'btn-outline'}" onclick="if(!document.body.classList.contains('dark')){toggleTheme()}">Dark</button></div><p style="font-size:13px;color:#64748b;margin-top:12px;" class="dark:text-slate-400">Current: ${document.body.classList.contains('dark')?'Dark':'Light'} mode</p></div>`,
        security: `<div class="card"><h3 style="font-weight:600;margin-bottom:12px;">Security Settings</h3><div class="grid-2col"><div><label class="form-label">Current Password</label><input class="form-input" type="password" placeholder="••••••••"></div><div><label class="form-label">New Password</label><input class="form-input" type="password" placeholder="••••••••"></div></div><button class="btn btn-primary" style="margin-top:14px;" onclick="showToast('Password updated!','success')">Update Password</button></div>`,
        backup: `<div class="card"><h3 style="font-weight:600;margin-bottom:12px;">Backup Settings</h3><div style="display:flex;gap:10px;flex-wrap:wrap;"><button class="btn btn-primary" onclick="showToast('Backup initiated...','info')"><i class="fas fa-database"></i> Backup Now</button><button class="btn btn-outline" onclick="showToast('Restore backup...','info')"><i class="fas fa-undo"></i> Restore</button></div><p style="font-size:13px;color:#64748b;margin-top:12px;" class="dark:text-slate-400">Last backup: 2026-06-23 08:00 AM</p></div>`
    };
    content.innerHTML = tabs[tab] || tabs.profile;
    document.querySelectorAll('#page-settings .tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelector(`#page-settings .tab-btn[onclick*="${tab}"]`)?.classList.add('active');
}

// ─── NOTIFICATIONS ───
function clearNotifications() {
    const container = document.querySelector('#page-notifications .card');
    if (container) {
        const items = container.parentElement.querySelectorAll('.card');
        items.forEach(el => el.remove());
        container.parentElement.innerHTML = `<div style="text-align:center;padding:40px 0;color:#94a3b8;"><i class="fas fa-check-circle" style="font-size:32px;display:block;margin-bottom:12px;color:#10b981;"></i>All notifications cleared</div>`;
        showToast('All notifications marked as read', 'success');
    }
}

// ─── COMMAND PALETTE ───
function openCommandPalette() {
    document.getElementById('cmdOverlay').classList.add('open');
    document.getElementById('cmdPalette').classList.add('open');
    document.getElementById('cmdInput').value = '';
    document.getElementById('cmdInput').focus();
    cmdFiltered = [...commands];
    cmdIndex = -1;
    renderCommands();
}
function closeCommandPalette() {
    document.getElementById('cmdOverlay').classList.remove('open');
    document.getElementById('cmdPalette').classList.remove('open');
}
function filterCommands() {
    const q = document.getElementById('cmdInput').value.toLowerCase();
    cmdFiltered = commands.filter(c => c.name.toLowerCase().includes(q));
    cmdIndex = -1;
    renderCommands();
}
function renderCommands() {
    const container = document.getElementById('cmdResults');
    if (cmdFiltered.length === 0) {
        container.innerHTML = '<div style="padding:16px;text-align:center;color:#94a3b8;">No commands found</div>';
        return;
    }
    container.innerHTML = cmdFiltered.map((c, i) =>
        `<div class="item ${i === cmdIndex ? 'selected' : ''}" onclick="executeCommandIndex(${i})"><i class="fas fa-command" style="color:#94a3b8;width:20px;"></i> ${c.name}</div>`
    ).join('');
}
function executeCommandIndex(i) {
    if (i >= 0 && i < cmdFiltered.length) {
        cmdFiltered[i].action();
        closeCommandPalette();
    }
}
function executeCommand() {
    if (cmdIndex >= 0 && cmdIndex < cmdFiltered.length) {
        cmdFiltered[cmdIndex].action();
        closeCommandPalette();
    } else if (cmdFiltered.length > 0) {
        cmdFiltered[0].action();
        closeCommandPalette();
    }
}
document.addEventListener('keydown', function(e) {
    if ((e.metaKey || e.ctrlKey) && e.key === 'k') { e.preventDefault(); openCommandPalette(); }
    if (e.key === 'Escape') { closeCommandPalette(); }
    if (document.getElementById('cmdPalette').classList.contains('open')) {
        if (e.key === 'ArrowDown') { e.preventDefault(); cmdIndex = Math.min(cmdIndex + 1, cmdFiltered.length - 1); renderCommands(); }
        if (e.key === 'ArrowUp') { e.preventDefault(); cmdIndex = Math.max(cmdIndex - 1, 0); renderCommands(); }
    }
});

// ─── CHARTS ───
function initCharts() {
    const isDark = document.body.classList.contains('dark');
    const textColor = isDark ? '#94a3b8' : '#64748b';
    const gridColor = isDark ? '#334155' : '#e2e8f0';
    const commonOpts = (extra = {}) => ({
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { labels: { color: textColor, font: { size: 11 } } } },
        scales: { x: { grid: { color: gridColor }, ticks: { color: textColor } }, y: { grid: { color: gridColor }, ticks: { color: textColor } } },
        ...extra
    });

    // Dashboard Revenue
    const ctx1 = document.getElementById('dashRevenueChart');
    if (ctx1) {
        if (chartInstances.dashRevenue) chartInstances.dashRevenue.destroy();
        chartInstances.dashRevenue = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{ label: 'Revenue (Rs)', data: [32000, 45000, 28000, 51000, 63000, 42000, 58000], borderColor: '#2563EB', backgroundColor: 'rgba(37,99,235,0.08)', fill: true, tension: 0.4, pointBackgroundColor: '#2563EB' }]
            },
            options: commonOpts()
        });
    }
    // Dashboard Stock
    const ctx2 = document.getElementById('dashStockChart');
    if (ctx2) {
        if (chartInstances.dashStock) chartInstances.dashStock.destroy();
        chartInstances.dashStock = new Chart(ctx2, {
            type: 'bar',
            data: { labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'], datasets: [{ label: 'Stock Level', data: [850, 820, 790, 740, 780, 720], backgroundColor: 'rgba(14,165,233,0.6)', borderColor: '#0EA5E9', borderWidth: 1, borderRadius: 4 }] },
            options: commonOpts({ plugins: { legend: { display: false } } })
        });
    }
    // Inventory Timeline
    const ctx3 = document.getElementById('invTimelineChart');
    if (ctx3) {
        if (chartInstances.invTimeline) chartInstances.invTimeline.destroy();
        chartInstances.invTimeline = new Chart(ctx3, {
            type: 'line',
            data: {
                labels: ['Jun 1', 'Jun 5', 'Jun 10', 'Jun 15', 'Jun 20', 'Jun 23'],
                datasets: [
                    { label: 'Stock In', data: [120, 80, 200, 60, 150, 90], borderColor: '#10B981', backgroundColor: 'rgba(16,185,129,0.1)', fill: true, tension: 0.3 },
                    { label: 'Stock Out', data: [90, 110, 70, 140, 80, 120], borderColor: '#EF4444', backgroundColor: 'rgba(239,68,68,0.1)', fill: true, tension: 0.3 }
                ]
            },
            options: commonOpts()
        });
    }
    // Reports Revenue
    const ctx4 = document.getElementById('reportsRevenueChart');
    if (ctx4) {
        if (chartInstances.reportsRevenue) chartInstances.reportsRevenue.destroy();
        chartInstances.reportsRevenue = new Chart(ctx4, {
            type: 'bar',
            data: { labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'], datasets: [{ label: 'Revenue', data: [120000, 150000, 180000, 220000, 190000, 260000], backgroundColor: 'rgba(37,99,235,0.6)', borderColor: '#2563EB', borderWidth: 1, borderRadius: 4 }] },
            options: commonOpts()
        });
    }
    // Reports Sales vs Profit
    const ctx5 = document.getElementById('reportsSalesChart');
    if (ctx5) {
        if (chartInstances.reportsSales) chartInstances.reportsSales.destroy();
        chartInstances.reportsSales = new Chart(ctx5, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [
                    { label: 'Sales', data: [140000, 170000, 200000, 250000, 220000, 290000], borderColor: '#2563EB', tension: 0.3 },
                    { label: 'Profit', data: [42000, 51000, 60000, 75000, 66000, 87000], borderColor: '#10B981', tension: 0.3 }
                ]
            },
            options: commonOpts()
        });
    }
    // Reports Inventory
    const ctx6 = document.getElementById('reportsInventoryChart');
    if (ctx6) {
        if (chartInstances.reportsInventory) chartInstances.reportsInventory.destroy();
        chartInstances.reportsInventory = new Chart(ctx6, {
            type: 'bar',
            data: {
                labels: ['Category A', 'Category B', 'Category C', 'Category D', 'Category E'],
                datasets: [{ label: 'Turnover Rate', data: [3.2, 2.8, 4.1, 1.9, 3.5], backgroundColor: ['#2563EB','#0EA5E9','#10B981','#F59E0B','#EF4444'], borderRadius: 4 }]
            },
            options: commonOpts({ plugins: { legend: { display: false } } })
        });
    }
}

// ─── DOMContentLoaded ───
document.addEventListener('DOMContentLoaded', function() {
    // Theme from localStorage
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark');
        const icon = document.getElementById('dashThemeIcon');
        if (icon) icon.className = 'fas fa-sun';
    }
    // Set user avatar
    const avatar = document.getElementById('userAvatar');
    if (avatar && user.name) {
        avatar.textContent = user.name.split(' ').map(w => w[0]).join('').toUpperCase();
    }
    // Greeting
    const greet = document.getElementById('dashboardGreeting');
    if (greet && user.name) {
        greet.textContent = `Welcome back, ${user.name}. Here's your pharmacy overview.`;
    }
    // Init components
    renderMedicines();
    renderCart();
    updatePosTotals();
    setTimeout(initCharts, 300);

    // Pricing toggle
    const pricingToggle = document.getElementById('pricingToggle');
    if (pricingToggle) {
        pricingToggle.addEventListener('click', function() {
            const isYearly = this.classList.toggle('active');
            document.querySelectorAll('.price-amount').forEach(el => {
                const monthly = parseInt(el.dataset.monthly);
                const yearly = parseInt(el.dataset.yearly);
                el.textContent = isYearly ? yearly.toLocaleString() : monthly.toLocaleString();
            });
            showToast(isYearly ? '💰 Yearly pricing (save 20%)' : '💳 Monthly pricing', 'info');
        });
    }

    // FAQ accordion
    document.querySelectorAll('.faq-question').forEach(q => {
        q.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const answer = document.getElementById(targetId);
            const icon = this.querySelector('.faq-icon');
            const isOpen = answer.classList.contains('open');
            document.querySelectorAll('.faq-answer').forEach(a => {
                if (a.id !== targetId) {
                    a.classList.remove('open');
                    a.previousElementSibling?.querySelector('.faq-icon')?.classList.remove('open');
                }
            });
            answer.classList.toggle('open', !isOpen);
            icon?.classList.toggle('open', !isOpen);
        });
    });

    // Mobile menu toggle (landing page)
    const mobileToggle = document.getElementById('mobileToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    if (mobileToggle && mobileMenu) {
        let menuOpen = false;
        mobileToggle.addEventListener('click', function() {
            menuOpen = !menuOpen;
            mobileMenu.style.maxHeight = menuOpen ? '500px' : '0';
            mobileMenu.style.opacity = menuOpen ? '1' : '0';
            this.querySelector('i').className = menuOpen ? 'fas fa-times text-xl' : 'fas fa-bars text-xl';
        });
        mobileMenu.querySelectorAll('a, button').forEach(el => {
            el.addEventListener('click', () => {
                menuOpen = false;
                mobileMenu.style.maxHeight = '0';
                mobileMenu.style.opacity = '0';
                mobileToggle.querySelector('i').className = 'fas fa-bars text-xl';
            });
        });
    }

    // Navbar scroll (landing)
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', function() {
        if (navbar) navbar.classList.toggle('scrolled', window.scrollY > 20);
        const fab = document.getElementById('fab');
        if (fab) fab.classList.toggle('hidden', window.scrollY < 600);
    });

    // Cursor glow
    if (window.innerWidth > 768) {
        const glow = document.getElementById('cursor-glow');
        document.addEventListener('mousemove', function(e) {
            if (glow) {
                glow.style.left = e.clientX + 'px';
                glow.style.top = e.clientY + 'px';
                glow.style.opacity = '1';
            }
        });
        document.addEventListener('mouseleave', function() {
            if (glow) glow.style.opacity = '0';
        });
    }

    // Scroll reveal (landing)
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) entry.target.classList.add('visible');
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
    document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

    // Toast welcome
    setTimeout(() => showToast('⌘K · Quick navigation', 'info'), 800);
    console.log('💊 PharmaFlow · Laravel Dashboard Ready');
});

// ─── EXPOSE GLOBALLY ───
window.navigate = navigate;
window.toggleDashboardSidebar = toggleDashboardSidebar;
window.toggleTheme = toggleTheme;
window.openModal = openModal;
window.closeModal = closeModal;
window.showToast = showToast;
window.renderMedicines = renderMedicines;
window.filterMedicines = filterMedicines;
window.changeMedPage = changeMedPage;
window.saveMedicine = saveMedicine;
window.editMedicine = editMedicine;
window.deleteMedicine = deleteMedicine;
window.addToCart = addToCart;
window.changeCartQty = changeCartQty;
window.removeFromCart = removeFromCart;
window.clearCart = clearCart;
window.updatePosTotals = updatePosTotals;
window.processSale = processSale;
window.clearNotifications = clearNotifications;
window.switchSettingsTab = switchSettingsTab;
window.openCommandPalette = openCommandPalette;
window.closeCommandPalette = closeCommandPalette;
window.filterCommands = filterCommands;
window.executeCommand = executeCommand;
window.executeCommandIndex = executeCommandIndex;
window.initCharts = initCharts;