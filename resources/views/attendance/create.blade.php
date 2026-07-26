@extends('layouts.app')

@section('title', 'Take Attendance - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="sb-section-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Take Attendance</h2>
            <p class="text-muted mb-0">Mark student attendance for a class</p>
        </div>
        <a href="{{ route('attendance.dashboard') }}" class="sb-btn sb-btn-secondary">Back to Dashboard</a>
    </div>

    @if($errors->any())
        <div style="background: #f8d7da; color: #842029; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; font-weight: 500;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="GET" action="{{ route('attendance.create') }}" class="card stat-card mb-4">
        <div class="card-body">
            <div class="d-flex gap-3 align-items-end">
                <div style="flex: 1;">
                    <label class="sb-form-label">Select Class</label>
                    <select name="class_id" required class="sb-form-select w-100">
                        <option value="">-- Choose a class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ $selectedClass == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}{{ $class->section ? ' - ' . $class->section : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="flex: 1;">
                    <label class="sb-form-label">Date</label>
                    <input type="date" name="date" value="{{ $selectedDate }}" max="{{ date('Y-m-d') }}" required class="sb-form-input w-100">
                </div>
                <button type="submit" class="sb-btn sb-btn-dark">Load Students</button>
            </div>
        </div>
    </form>

    @if($selectedClass && $students->isNotEmpty())
        <form method="POST" action="{{ route('attendance.store') }}">
            @csrf
            <input type="hidden" name="school_class_id" value="{{ $selectedClass }}">
            <input type="hidden" name="attendance_date" value="{{ $selectedDate }}">

            <div class="card stat-card mb-4">
                <div class="card-body p-0">
                    <div class="sb-section-header" style="padding: 20px 24px; border-bottom: 1px solid #e9ecef; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h5 style="font-weight: 600; margin: 0; color: #1a1a2e;">{{ $students->count() }} {{ Str::plural('Student', $students->count()) }}</h5>
                            <p style="margin: 4px 0 0 0; font-size: 13px; color: #6c757d;">
                                {{ now()->parse($selectedDate)->format('l, M d, Y') }}
                                @if(!empty($existingAttendances))
                                    &middot; <span style="color: #ffc107; font-weight: 500;">Attendance already exists — updating will overwrite</span>
                                @endif
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" onclick="markAll('present')" class="sb-btn sb-btn-sm" style="background: #d1e7dd; color: #0f5132;">All Present</button>
                            <button type="button" onclick="markAll('absent')" class="sb-btn sb-btn-sm" style="background: #f8d7da; color: #842029;">All Absent</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="sb-table table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Student</th>
                                    <th>Adm. No.</th>
                                    <th class="text-center">Status</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $index => $student)
                                    @php $currentStatus = $existingAttendances[$student->id] ?? 'present'; @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td style="font-weight: 500;">{{ $student->full_name }}</td>
                                        <td>
                                            <code style="background: #f0f2f5; padding: 2px 8px; border-radius: 4px; font-size: 13px;">{{ $student->admission_number }}</code>
                                        </td>
                                        <td class="text-center">
                                            <input type="hidden" name="attendances[{{ $index }}][student_id]" value="{{ $student->id }}">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <label style="cursor: pointer;">
                                                    <input type="radio" name="attendances[{{ $index }}][status]" value="present" {{ $currentStatus === 'present' ? 'checked' : '' }} style="display: none;">
                                                    <span class="status-badge status-present" onclick="selectStatus(this, {{ $index }}, 'present')">P</span>
                                                </label>
                                                <label style="cursor: pointer;">
                                                    <input type="radio" name="attendances[{{ $index }}][status]" value="absent" {{ $currentStatus === 'absent' ? 'checked' : '' }} style="display: none;">
                                                    <span class="status-badge status-absent" onclick="selectStatus(this, {{ $index }}, 'absent')">A</span>
                                                </label>
                                                <label style="cursor: pointer;">
                                                    <input type="radio" name="attendances[{{ $index }}][status]" value="late" {{ $currentStatus === 'late' ? 'checked' : '' }} style="display: none;">
                                                    <span class="status-badge status-late" onclick="selectStatus(this, {{ $index }}, 'late')">L</span>
                                                </label>
                                                <label style="cursor: pointer;">
                                                    <input type="radio" name="attendances[{{ $index }}][status]" value="excused" {{ $currentStatus === 'excused' ? 'checked' : '' }} style="display: none;">
                                                    <span class="status-badge status-excused" onclick="selectStatus(this, {{ $index }}, 'excused')">E</span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" name="attendances[{{ $index }}][remarks]" placeholder="Optional remarks" class="sb-form-input" style="width: 100%;">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('attendance.create') }}" class="sb-btn sb-btn-secondary">Cancel</a>
                <button type="submit" class="sb-btn sb-btn-primary">Save Attendance</button>
            </div>
        </form>
    @elseif($selectedClass && $students->isEmpty())
        <div class="card stat-card">
            <div class="card-body">
                <div class="sb-empty-state">
                    <p style="margin: 0; font-size: 15px;">No active students found in this class.</p>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<style>
    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        border: 2px solid transparent;
    }
    .status-present { background: #d1e7dd; color: #0f5132; }
    .status-present.active { border-color: #0f5132; box-shadow: 0 0 0 2px rgba(15,81,50,0.2); }
    .status-absent { background: #f8d7da; color: #842029; }
    .status-absent.active { border-color: #842029; box-shadow: 0 0 0 2px rgba(132,32,41,0.2); }
    .status-late { background: #fff3cd; color: #664d03; }
    .status-late.active { border-color: #664d03; box-shadow: 0 0 0 2px rgba(102,77,3,0.2); }
    .status-excused { background: #e7f1ff; color: #0d6efd; }
    .status-excused.active { border-color: #0d6efd; box-shadow: 0 0 0 2px rgba(13,110,253,0.2); }
</style>
<script>
    function selectStatus(el, index, status) {
        var row = el.closest('tr');
        row.querySelectorAll('.status-badge').forEach(function(b) { b.classList.remove('active'); });
        el.classList.add('active');
    }

    function markAll(status) {
        document.querySelectorAll('input[name*="[status]"]').forEach(function(radio) {
            radio.checked = (radio.value === status);
        });
        document.querySelectorAll('tbody tr').forEach(function(row) {
            row.querySelectorAll('.status-badge').forEach(function(b) { b.classList.remove('active'); });
            var selector = 'input[name*="[status]"][value="' + status + '"]';
            var activeRadio = row.querySelector(selector);
            if (activeRadio) {
                activeRadio.nextElementSibling.classList.add('active');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input[name*="[status]"]:checked').forEach(function(radio) {
            radio.nextElementSibling.classList.add('active');
        });
    });
</script>
@endpush
@endsection
