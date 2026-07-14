@php
    $timetable = $timetable ?? null;
@endphp

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="class_id" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Class <span style="color: #dc3545;">*</span></label>
        <select
            name="class_id"
            id="class_id"
            class="form-control @error('class_id') is-invalid @enderror"
            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
            required
        >
            <option value="">Select Class</option>
            @foreach($classes as $class)
                <option
                    value="{{ $class->id }}"
                    data-school-id="{{ $class->school_id }}"
                    {{ old('class_id', $timetable->class_id ?? '') == $class->id ? 'selected' : '' }}
                >
                    {{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}
                </option>
            @endforeach
        </select>
        @error('class_id')
            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="section_id" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Section <span style="color: #dc3545;">*</span></label>
        <select
            name="section_id"
            id="section_id"
            class="form-control @error('section_id') is-invalid @enderror"
            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
            required
        >
            <option value="">Select Section</option>
        </select>
        @error('section_id')
            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="subject_id" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Subject <span style="color: #dc3545;">*</span></label>
        <select
            name="subject_id"
            id="subject_id"
            class="form-control @error('subject_id') is-invalid @enderror"
            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
            required
        >
            <option value="">Select Subject</option>
        </select>
        @error('subject_id')
            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="teacher_id" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Teacher <span style="color: #dc3545;">*</span></label>
        <select
            name="teacher_id"
            id="teacher_id"
            class="form-control @error('teacher_id') is-invalid @enderror"
            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
            required
        >
            <option value="">Select Teacher</option>
        </select>
        @error('teacher_id')
            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="period_id" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Period <span style="color: #dc3545;">*</span></label>
        <select
            name="period_id"
            id="period_id"
            class="form-control @error('period_id') is-invalid @enderror"
            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
            required
        >
            <option value="">Select Period</option>
        </select>
        @error('period_id')
            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="day" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Day <span style="color: #dc3545;">*</span></label>
        <select
            name="day"
            id="day"
            class="form-control @error('day') is-invalid @enderror"
            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
            required
        >
            <option value="">Select Day</option>
            @foreach($days as $dayOption)
                <option value="{{ $dayOption }}" {{ old('day', $timetable->day ?? '') === $dayOption ? 'selected' : '' }}>
                    {{ $dayOption }}
                </option>
            @endforeach
        </select>
        @error('day')
            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 mb-3">
        <label for="notes" style="display: block; font-weight: 500; font-size: 14px; color: #333; margin-bottom: 6px;">Notes</label>
        <textarea
            name="notes"
            id="notes"
            placeholder="Optional notes about this timetable entry"
            class="form-control @error('notes') is-invalid @enderror"
            style="border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px;"
            rows="3"
        >{{ old('notes', $timetable->notes ?? '') }}</textarea>
        @error('notes')
            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
        @enderror
    </div>
</div>

@php
    $oldClassId = old('class_id', $timetable->class_id ?? '');
    $oldSectionId = old('section_id', $timetable->section_id ?? '');
    $oldSubjectId = old('subject_id', $timetable->subject_id ?? '');
    $oldTeacherId = old('teacher_id', $timetable->teacher_id ?? '');
    $oldPeriodId = old('period_id', $timetable->period_id ?? '');
@endphp

@push('scripts')
<script>
(function() {
    const classSelect = document.getElementById('class_id');
    const sectionSelect = document.getElementById('section_id');
    const subjectSelect = document.getElementById('subject_id');
    const teacherSelect = document.getElementById('teacher_id');
    const periodSelect = document.getElementById('period_id');

    const oldClassId = '{{ $oldClassId }}';
    const oldSectionId = '{{ $oldSectionId }}';
    const oldSubjectId = '{{ $oldSubjectId }}';
    const oldTeacherId = '{{ $oldTeacherId }}';
    const oldPeriodId = '{{ $oldPeriodId }}';

    function getSchoolId() {
        const selected = classSelect.options[classSelect.selectedIndex];
        return selected ? selected.getAttribute('data-school-id') : null;
    }

    function populateSelect(select, items, valueField, labelField, selectedId) {
        select.innerHTML = '<option value="">Select ' + select.closest('.mb-3').querySelector('label').textContent.replace(' *', '') + '</option>';
        items.forEach(function(item) {
            const option = document.createElement('option');
            option.value = item[valueField];
            option.textContent = labelField === 'full_name' ? item.first_name + (item.other_name ? ' ' + item.other_name + ' ' : ' ') + item.last_name : (item.code ? item.name + ' (' + item.code + ')' : item.name);
            if (String(item[valueField]) === String(selectedId)) {
                option.selected = true;
            }
            select.appendChild(option);
        });
    }

    function loadDropdowns(schoolId, selected) {
        if (!schoolId) {
            sectionSelect.innerHTML = '<option value="">Select Section</option>';
            subjectSelect.innerHTML = '<option value="">Select Subject</option>';
            teacherSelect.innerHTML = '<option value="">Select Teacher</option>';
            periodSelect.innerHTML = '<option value="">Select Period</option>';
            return;
        }

        const baseUrl = '{{ url("timetables/api") }}';

        Promise.all([
            fetch(baseUrl + '/sections?school_id=' + schoolId).then(function(r) { return r.json(); }),
            fetch(baseUrl + '/subjects?school_id=' + schoolId).then(function(r) { return r.json(); }),
            fetch(baseUrl + '/teachers?school_id=' + schoolId).then(function(r) { return r.json(); }),
            fetch(baseUrl + '/periods?school_id=' + schoolId).then(function(r) { return r.json(); })
        ]).then(function(results) {
            populateSelect(sectionSelect, results[0], 'id', 'name', selected.section || oldSectionId);
            populateSelect(subjectSelect, results[1], 'id', 'name', selected.subject || oldSubjectId);
            populateSelect(teacherSelect, results[2], 'id', 'full_name', selected.teacher || oldTeacherId);
            populateSelect(periodSelect, results[3], 'id', 'name', selected.period || oldPeriodId);
        });
    }

    classSelect.addEventListener('change', function() {
        const schoolId = getSchoolId();
        loadDropdowns(schoolId, {});
    });

    if (oldClassId) {
        const schoolId = getSchoolId();
        if (schoolId) {
            loadDropdowns(schoolId, {
                section: oldSectionId,
                subject: oldSubjectId,
                teacher: oldTeacherId,
                period: oldPeriodId
            });
        }
    }
})();
</script>
@endpush
