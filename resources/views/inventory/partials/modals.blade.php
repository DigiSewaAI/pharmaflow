{{-- resources/views/inventory/partials/modals.blade.php --}}

<!-- ===== STOCK IN MODAL ===== -->
<div class="modal-overlay" id="stockInModal">
    <div class="modal">
        <h3 style="font-size:20px;font-weight:700;margin-bottom:16px;">
            <i class="fas fa-arrow-down text-green-600 mr-2"></i> Stock In
        </h3>
        <form id="stockInForm" action="{{ route('inventory.stock-in') }}" method="POST">
            @csrf
            <div class="grid-2col">
                <div>
                    <label class="form-label">Medicine <span class="text-red-500">*</span></label>
                    <select name="medicine_id" class="form-input select-custom" required>
                        <option value="">Select Medicine</option>
                        @foreach($medicines ?? [] as $med)
                            <option value="{{ $med->id }}">{{ $med->name }} ({{ $med->quantity }} in stock)</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Quantity <span class="text-red-500">*</span></label>
                    <input type="number" name="quantity" class="form-input" placeholder="0" required min="1">
                </div>
                <div>
                    <label class="form-label">Batch Number</label>
                    <input type="text" name="batch" class="form-input" placeholder="BATCH-2026-001">
                </div>
                <div>
                    <label class="form-label">Supplier</label>
                    <input type="text" name="supplier" class="form-input" placeholder="Supplier name">
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:20px;justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="closeModal('stockInModal')">Cancel</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-arrow-down"></i> Add Stock</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== STOCK OUT MODAL ===== -->
<div class="modal-overlay" id="stockOutModal">
    <div class="modal">
        <h3 style="font-size:20px;font-weight:700;margin-bottom:16px;">
            <i class="fas fa-arrow-up text-warning mr-2"></i> Stock Out
        </h3>
        <form id="stockOutForm" action="{{ route('inventory.stock-out') }}" method="POST">
            @csrf
            <div class="grid-2col">
                <div>
                    <label class="form-label">Medicine <span class="text-red-500">*</span></label>
                    <select name="medicine_id" class="form-input select-custom" required>
                        <option value="">Select Medicine</option>
                        @foreach($medicines ?? [] as $med)
                            <option value="{{ $med->id }}">{{ $med->name }} ({{ $med->quantity }} available)</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Quantity <span class="text-red-500">*</span></label>
                    <input type="number" name="quantity" class="form-input" placeholder="0" required min="1">
                </div>
                <div>
                    <label class="form-label">Batch Number</label>
                    <input type="text" name="batch" class="form-input" placeholder="BATCH-2026-001">
                </div>
                <div>
                    <label class="form-label">Reason <span class="text-red-500">*</span></label>
                    <select name="reason" class="form-input select-custom" required>
                        <option value="">Select Reason</option>
                        <option value="Sale">Sale</option>
                        <option value="Damaged">Damaged</option>
                        <option value="Expired">Expired</option>
                        <option value="Return">Return</option>
                        <option value="Transfer">Transfer</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:20px;justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="closeModal('stockOutModal')">Cancel</button>
                <button type="submit" class="btn btn-warning"><i class="fas fa-arrow-up"></i> Remove Stock</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== ADJUST MODAL ===== -->
<div class="modal-overlay" id="adjustModal">
    <div class="modal">
        <h3 style="font-size:20px;font-weight:700;margin-bottom:16px;">
            <i class="fas fa-sliders-h text-primary mr-2"></i> Adjust Stock
        </h3>
        <form action="{{ route('inventory.adjust') }}" method="POST">
            @csrf
            <div class="grid-2col">
                <div>
                    <label class="form-label">Medicine <span class="text-red-500">*</span></label>
                    <select name="medicine_id" class="form-input select-custom" required>
                        <option value="">Select Medicine</option>
                        @foreach($medicines ?? [] as $med)
                            <option value="{{ $med->id }}">{{ $med->name }} (Current: {{ $med->quantity }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">New Quantity <span class="text-red-500">*</span></label>
                    <input type="number" name="new_quantity" class="form-input" placeholder="0" required min="0">
                </div>
                <div class="col-span-2">
                    <label class="form-label">Reason <span class="text-red-500">*</span></label>
                    <input type="text" name="reason" class="form-input" placeholder="e.g. Inventory correction, Damaged, etc." required>
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:20px;justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="closeModal('adjustModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Apply Adjustment</button>
            </div>
        </form>
    </div>
</div>