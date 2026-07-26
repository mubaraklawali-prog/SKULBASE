@extends('layouts.app')

@section('title', 'Record Payment - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Record Payment</h2>
            <p class="text-muted mb-0">Record a student fee payment</p>
        </div>
        <a href="{{ route('fees.payments.index') }}" class="sb-btn sb-btn-secondary">Back to Payments</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" style="border-radius: 8px; margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="GET" action="{{ route('fees.payments.create') }}" class="card stat-card mb-4">
        <div class="card-body">
            <div class="d-flex gap-3 align-items-end">
                <div style="flex: 1;">
                    <label class="sb-form-label">Select Student</label>
                    <select name="student_id" class="sb-form-select" required>
                        <option value="">-- Choose a student --</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>{{ $student->full_name }} ({{ $student->admission_number }}) - {{ $student->schoolClass->name ?? 'No Class' }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="sb-btn sb-btn-dark">Load Fees</button>
            </div>
        </div>
    </form>

    @if($selectedStudent)
        <div class="card stat-card mb-4">
            <div class="card-body" style="padding: 20px 24px;">
                <h5 style="font-weight: 600; margin-bottom: 4px; color: #1a1a2e;">{{ $selectedStudent->full_name }}</h5>
                <p style="margin: 0; color: #6c757d; font-size: 14px;">
                    {{ $selectedStudent->schoolClass->name ?? 'No Class' }} &middot;
                    {{ $selectedStudent->school->name ?? '' }} &middot;
                    <code>{{ $selectedStudent->admission_number }}</code>
                </p>
            </div>
        </div>

        @if($studentFeeStructures->isNotEmpty())
            <form method="POST" action="{{ route('fees.payments.store') }}">
                @csrf
                <input type="hidden" name="student_id" value="{{ $selectedStudent->id }}">

                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label class="sb-form-label">Fee Structure <span class="required">*</span></label>
                        <select name="fee_structure_id" id="fee_structure_id" class="sb-form-select" required onchange="updateOutstanding()">
                            <option value="">-- Select Fee --</option>
                            @foreach($studentFeeStructures as $fs)
                                <option value="{{ $fs->id }}" data-amount="{{ $fs->amount }}" data-outstanding="{{ $outstandingBalances[$fs->id] ?? $fs->amount }}">
                                    {{ $fs->title }} - ₦{{ number_format($fs->amount, 2) }} (Balance: ₦{{ number_format($outstandingBalances[$fs->id] ?? $fs->amount, 2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="sb-form-label">Outstanding Balance</label>
                        <div id="outstanding-display" class="sb-form-input" style="background: #f8f9fa; color: #6c757d;">₦0.00</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="sb-form-label">Amount to Pay (₦) <span class="required">*</span></label>
                        <input type="number" name="amount_paid" id="amount_paid" class="sb-form-input @error('amount_paid') is-invalid @enderror" value="{{ old('amount_paid') }}" required min="0.01" step="0.01" placeholder="0.00" oninput="validateAmount()">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="sb-form-label">Payment Date <span class="required">*</span></label>
                        <input type="date" name="payment_date" class="sb-form-input" value="{{ old('payment_date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="sb-form-label">Payment Method <span class="required">*</span></label>
                        <select name="payment_method" class="sb-form-select" required>
                            <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="transfer" {{ old('payment_method') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                            <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>Card</option>
                            <option value="other" {{ old('payment_method') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="sb-form-label">Reference</label>
                        <input type="text" name="reference" class="sb-form-input" value="{{ old('reference') }}" placeholder="Transaction ID, receipt no.">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="sb-form-label">Remarks</label>
                        <textarea name="remarks" class="sb-form-textarea" rows="2" placeholder="Optional remarks">{{ old('remarks') }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('fees.payments.index') }}" class="sb-btn sb-btn-secondary">Cancel</a>
                    <button type="submit" class="sb-btn sb-btn-primary">Record Payment</button>
                </div>
            </form>
        @else
            <div class="card stat-card">
                <div class="card-body sb-empty-state">
                    <p style="margin: 0; font-size: 15px;">No fee structures found for this student's class.</p>
                </div>
            </div>
        @endif
    @endif
</div>

@push('scripts')
<script>
    function updateOutstanding() {
        var select = document.getElementById('fee_structure_id');
        var option = select.options[select.selectedIndex];
        var display = document.getElementById('outstanding-display');
        if (option.value) {
            var outstanding = parseFloat(option.getAttribute('data-outstanding')) || 0;
            display.textContent = '₦' + outstanding.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            display.style.color = outstanding > 0 ? '#842029' : '#0f5132';
            var input = document.getElementById('amount_paid');
            input.max = outstanding;
        } else {
            display.textContent = '₦0.00';
            display.style.color = '#6c757d';
        }
    }
    function validateAmount() {
        var input = document.getElementById('amount_paid');
        var max = parseFloat(input.max) || 0;
        if (parseFloat(input.value) > max) {
            input.setCustomValidity('Amount cannot exceed outstanding balance of ₦' + max.toFixed(2));
        } else {
            input.setCustomValidity('');
        }
    }
</script>
@endpush
@endsection
