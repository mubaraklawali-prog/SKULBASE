<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $payment->id }} - Skulbase</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: #f4f6f9; padding: 20px; }
        .receipt { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); overflow: hidden; }
        .receipt-header { background: #0a1628; color: #fff; padding: 32px; text-align: center; }
        .receipt-header h1 { font-size: 28px; font-weight: 700; margin-bottom: 4px; }
        .receipt-header h1 span { color: #4f9cf7; }
        .receipt-header p { font-size: 13px; opacity: 0.7; }
        .receipt-body { padding: 32px; }
        .receipt-title { text-align: center; margin-bottom: 24px; }
        .receipt-title h2 { font-size: 20px; font-weight: 700; color: #1a1a2e; margin-bottom: 4px; }
        .receipt-title p { font-size: 13px; color: #6c757d; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px; }
        .info-item label { display: block; font-size: 11px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
        .info-item p { font-size: 14px; color: #333; font-weight: 500; }
        .amount-section { background: #f8f9fa; border-radius: 8px; padding: 20px; margin-bottom: 24px; }
        .amount-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 14px; }
        .amount-row.total { border-top: 2px solid #dee2e6; margin-top: 8px; padding-top: 12px; font-weight: 700; font-size: 16px; }
        .amount-row .label { color: #6c757d; }
        .amount-row .value { color: #333; }
        .amount-row.total .value { color: #0f5132; }
        .receipt-footer { text-align: center; padding: 20px 32px; border-top: 1px solid #e9ecef; }
        .receipt-footer p { font-size: 12px; color: #adb5bd; }
        .print-btn { display: block; max-width: 600px; margin: 16px auto 0; text-align: center; }
        .print-btn button { background: #4f9cf7; color: #fff; border: none; padding: 12px 32px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; }
        .print-btn button:hover { background: #3b82f6; }
        @media print {
            body { background: #fff; padding: 0; }
            .receipt { box-shadow: none; border: 1px solid #e9ecef; }
            .print-btn { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="receipt-header">
            <h1><span>Skul</span>base</h1>
            <p>{{ $payment->school->name ?? 'School Management System' }}</p>
        </div>
        <div class="receipt-body">
            <div class="receipt-title">
                <h2>Payment Receipt</h2>
                <p>Receipt #{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }} &middot; {{ $payment->payment_date->format('M d, Y') }}</p>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <label>Student Name</label>
                    <p>{{ $payment->student->full_name }}</p>
                </div>
                <div class="info-item">
                    <label>Admission No.</label>
                    <p>{{ $payment->student->admission_number }}</p>
                </div>
                <div class="info-item">
                    <label>Class</label>
                    <p>{{ $payment->feeStructure->schoolClass->name ?? '—' }}</p>
                </div>
                <div class="info-item">
                    <label>Fee Type</label>
                    <p>{{ $payment->feeStructure->title }}</p>
                </div>
                <div class="info-item">
                    <label>Payment Method</label>
                    <p style="text-transform: capitalize;">{{ $payment->payment_method }}</p>
                </div>
                <div class="info-item">
                    <label>Reference</label>
                    <p>{{ $payment->reference ?? '—' }}</p>
                </div>
            </div>

            <div class="amount-section">
                <div class="amount-row">
                    <span class="label">Total Fee Amount</span>
                    <span class="value">₦{{ number_format($payment->feeStructure->amount, 2) }}</span>
                </div>
                <div class="amount-row">
                    <span class="label">Previous Payments</span>
                    <span class="value">₦{{ number_format($totalPaid - $payment->amount_paid, 2) }}</span>
                </div>
                <div class="amount-row">
                    <span class="label">This Payment</span>
                    <span class="value">₦{{ number_format($payment->amount_paid, 2) }}</span>
                </div>
                <div class="amount-row total">
                    <span class="label">Outstanding Balance</span>
                    <span class="value">{{ $balance > 0 ? '₦' . number_format($balance, 2) : 'Fully Paid' }}</span>
                </div>
            </div>

            @if($payment->remarks)
                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Remarks</label>
                    <p style="font-size: 14px; color: #333;">{{ $payment->remarks }}</p>
                </div>
            @endif
        </div>
        <div class="receipt-footer">
            <p>This is a computer-generated receipt. No signature required.</p>
            <p style="margin-top: 4px;">Generated on {{ now()->format('M d, Y \a\t h:i A') }}</p>
        </div>
    </div>

    <div class="print-btn">
        <button onclick="window.print()">Print Receipt</button>
    </div>
</body>
</html>
