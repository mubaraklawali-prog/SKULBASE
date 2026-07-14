@php
    $period = $period ?? null;
@endphp

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="school_id" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">School <span style="color: #dc3545;">*</span></label>
        <select
            name="school_id"
            id="school_id"
            class="form-control @error('school_id') is-invalid @enderror"
            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
            required
        >
            <option value="">Select School</option>
            @foreach($schools as $school)
                <option value="{{ $school->id }}" {{ old('school_id', $period->school_id ?? '') == $school->id ? 'selected' : '' }}>
                    {{ $school->name }}
                </option>
            @endforeach
        </select>
        @error('school_id')
            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="name" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Period Name <span style="color: #dc3545;">*</span></label>
        <input
            type="text"
            name="name"
            id="name"
            value="{{ old('name', $period->name ?? '') }}"
            placeholder="e.g. Period 1, Assembly, Break"
            class="form-control @error('name') is-invalid @enderror"
            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
            required
        >
        @error('name')
            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="type" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Type <span style="color: #dc3545;">*</span></label>
        <select
            name="type"
            id="type"
            class="form-control @error('type') is-invalid @enderror"
            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
            required
        >
            <option value="">Select Type</option>
            <option value="academic" {{ old('type', $period->type ?? '') === 'academic' ? 'selected' : '' }}>Academic</option>
            <option value="break" {{ old('type', $period->type ?? '') === 'break' ? 'selected' : '' }}>Break</option>
            <option value="lunch" {{ old('type', $period->type ?? '') === 'lunch' ? 'selected' : '' }}>Lunch</option>
            <option value="assembly" {{ old('type', $period->type ?? '') === 'assembly' ? 'selected' : '' }}>Assembly</option>
            <option value="other" {{ old('type', $period->type ?? '') === 'other' ? 'selected' : '' }}>Other</option>
        </select>
        @error('type')
            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="sort_order" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Sort Order</label>
        <input
            type="number"
            name="sort_order"
            id="sort_order"
            value="{{ old('sort_order', $period->sort_order ?? 0) }}"
            min="0"
            class="form-control @error('sort_order') is-invalid @enderror"
            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
        >
        @error('sort_order')
            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="start_time" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Start Time <span style="color: #dc3545;">*</span></label>
        <input
            type="time"
            name="start_time"
            id="start_time"
            value="{{ old('start_time', $period->start_time ?? '') }}"
            class="form-control @error('start_time') is-invalid @enderror"
            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
            required
        >
        @error('start_time')
            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="end_time" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">End Time <span style="color: #dc3545;">*</span></label>
        <input
            type="time"
            name="end_time"
            id="end_time"
            value="{{ old('end_time', $period->end_time ?? '') }}"
            class="form-control @error('end_time') is-invalid @enderror"
            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
            required
        >
        @error('end_time')
            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
        @enderror
    </div>

    @if(isset($period) && $period)
        <div class="col-md-6 mb-3">
            <label for="status" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Status <span style="color: #dc3545;">*</span></label>
            <select
                name="status"
                id="status"
                class="form-control @error('status') is-invalid @enderror"
                style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
                required
            >
                <option value="1" {{ old('status', $period->status ? '1' : '0') === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('status', $period->status ? '1' : '0') === '0' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status')
                <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>
    @endif
</div>
