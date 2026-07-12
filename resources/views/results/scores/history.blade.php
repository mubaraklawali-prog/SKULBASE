@extends('layouts.app')

@section('title', 'Score History - Skulbase')

@section('content')
<div class="welcome-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Score History</h2>
            <p class="text-muted mb-0">View all score entries</p>
        </div>
        <a href="{{ route('results.scores.create') }}" class="btn" style="background: #4f9cf7; color: #fff; border-radius: 8px; padding: 10px 20px; font-weight: 500; text-decoration: none;">+ Enter Scores</a>
    </div>

    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('results.scores.history') }}" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; margin-bottom: 4px;">Exam</label>
                    <select name="exam_id" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 13px;">
                        <option value="">All Exams</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>{{ $exam->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; margin-bottom: 4px;">Class</label>
                    <select name="school_class_id" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 13px;">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('school_class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; margin-bottom: 4px;">Subject</label>
                    <select name="subject_id" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 13px;">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; margin-bottom: 4px;">Assessment</label>
                    <select name="assessment_type_id" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 13px;">
                        <option value="">All Types</option>
                        @foreach($assessmentTypes as $type)
                            <option value="{{ $type->id }}" {{ request('assessment_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #6c757d; margin-bottom: 4px;">Student</label>
                    <select name="student_id" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #dee2e6; font-size: 13px;">
                        <option value="">All Students</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>{{ $student->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn" style="background: #0a1628; color: #fff; border-radius: 8px; padding: 8px 16px; font-weight: 500; font-size: 13px;">Filter</button>
                    @if(request()->hasAny(['exam_id', 'school_class_id', 'subject_id', 'assessment_type_id', 'student_id']))
                        <a href="{{ route('results.scores.history') }}" class="btn" style="background: #f0f2f5; color: #333; border-radius: 8px; padding: 8px 16px; font-weight: 500; font-size: 13px; text-decoration: none;">Clear</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card stat-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Student</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Class</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Subject</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Exam</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Assessment</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Score</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Teacher</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Date</th>
                            <th style="padding: 14px 20px; font-size: 13px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($scores as $score)
                            <tr>
                                <td style="padding: 14px 20px; font-weight: 500;">{{ $score->student->full_name ?? '—' }}</td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $score->schoolClass->name ?? '—' }}</td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $score->subject->name ?? '—' }}</td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $score->exam->name ?? '—' }}</td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $score->assessmentType->name ?? '—' }}</td>
                                <td style="padding: 14px 20px;">
                                    <span style="background: {{ $score->score >= 70 ? '#d1e7dd' : ($score->score >= 50 ? '#fff3cd' : '#f8d7da') }}; color: {{ $score->score >= 70 ? '#0f5132' : ($score->score >= 50 ? '#664d03' : '#842029') }}; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">{{ $score->score }}%</span>
                                </td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $score->teacher->full_name ?? '—' }}</td>
                                <td style="padding: 14px 20px; color: #6c757d;">{{ $score->created_at->format('M d, Y') }}</td>
                                <td style="padding: 14px 20px; text-align: right;">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('results.scores.show', $score) }}" class="btn btn-sm" style="background: #e7f1ff; color: #0d6efd; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 500; text-decoration: none;">View</a>
                                        <form method="POST" action="{{ route('results.scores.destroy', $score) }}" style="margin: 0;" onsubmit="return confirm('Delete this score entry?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm" style="background: #f8d7da; color: #842029; border-radius: 6px; padding: 6px 12px; font-size: 13px; font-weight: 500; cursor: pointer; border: none;">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="padding: 40px 20px; text-align: center; color: #6c757d;">
                                    <p style="margin: 0; font-size: 15px;">No score entries found.</p>
                                    <a href="{{ route('results.scores.create') }}" style="color: #4f9cf7; font-weight: 500; text-decoration: none;">Enter scores now</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($scores->hasPages())
        <div class="mt-3" style="display: flex; justify-content: center;">{{ $scores->links() }}</div>
    @endif
</div>
@endsection
