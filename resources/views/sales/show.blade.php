{{-- resources/views/sales/show.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'PharmaFlow — Invoice #' . $sale->invoice_number)
@section('page-title', 'Invoice #' . $sale->invoice_number)

@section('content')
<div class="card" style="max-width:800px;margin:0 auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <div>
            <h2 style="font-size:20px;font-weight:700;">{{ $sale->invoice_number }}</h2>
            <p style="color:#64748b;">{{ $sale->created_at->format('d M Y, h:i A') }}</p>
        </div>
        <div>
            <span class="status-badge {{ $sale->status == 'completed' ? 'status-instock' : 'status-expired' }}">
                {{ ucfirst($sale->status) }}
            </span>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
        <div>
            <p style="font-size:13px;color:#64748b;">Customer</p>
            <p style="font-weight:600;">{{ $sale->customer?->name ?? 'Walk-in' }}</p>
            <p style="font-size:13px;color:#64748b;">{{ $sale->customer?->phone ?? '' }}</p>
        </div>
        <div>
            <p style="font-size:13px;color:#64748b;">Cashier</p>
            <p style="font-weight:600;">{{ $sale->user?->name ?? 'Unknown' }}</p>
        </div>
    </div>

    <table class="table-wrap" style="width:100%;">
        <thead>
            <tr>
                <th>Medicine</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
                <tr>
                    <td>{{ $item->medicine?->name ?? 'Deleted' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rs {{ number_format($item->price, 0) }}</td>
                    <td>Rs {{ number_format($item->total, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align:right;font-weight:600;">Subtotal</td>
                <td>Rs {{ number_format($sale->subtotal, 0) }}</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align:right;font-weight:600;">Discount</td>
                <td>Rs {{ number_format($sale->discount, 0) }}</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align:right;font-weight:600;">Tax</td>
                <td>Rs {{ number_format($sale->tax, 0) }}</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align:right;font-weight:700;font-size:18px;">Grand Total</td>
                <td style="font-weight:700;font-size:18px;">Rs {{ number_format($sale->grand_total, 0) }}</td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top:20px;display:flex;gap:10px;justify-content:flex-end;">
        <a href="{{ route('sales.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
        <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> Print</button>
    </div>
</div>
@endsection