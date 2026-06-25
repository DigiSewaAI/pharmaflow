{{-- resources/views/purchases/partials/modals.blade.php --}}

<!-- ===== NEW PURCHASE ORDER MODAL ===== -->
<div class="modal-overlay" id="purchaseModal">
    <div class="modal" style="max-width:800px;">
        <h3 style="font-size:20px;font-weight:700;margin-bottom:16px;">
            <i class="fas fa-file-invoice text-blue-600 mr-2"></i> New Purchase Order
        </h3>
        <form action="{{ route('purchases.store') }}" method="POST">
            @csrf
            <div class="grid-2col">
                <div>
                    <label class="form-label">Supplier <span class="text-red-500">*</span></label>
                    <select name="supplier_id" class="form-input select-custom" required>
                        <option value="">Select Supplier</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Order Date <span class="text-red-500">*</span></label>
                    <input type="date" name="order_date" class="form-input" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>

            <div style="margin-top:16px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                    <h4 style="font-weight:600;">Items</h4>
                    <button type="button" class="btn btn-outline btn-sm" onclick="addItemRow()">
                        <i class="fas fa-plus"></i> Add Item
                    </button>
                </div>
                <div id="poItemsContainer"></div>
            </div>

            <div style="display:flex;gap:10px;margin-top:20px;justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="closeModal('purchaseModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Create PO</button>
            </div>
        </form>
    </div>
</div>