<?php $__env->startSection('title', 'Enter Scores - Skulbase'); ?>

<?php $__env->startSection('content'); ?>
<div class="welcome-section">
    <div class="sb-section-header">
        <div>
            <h2>Enter Scores</h2>
            <p class="text-muted mb-0">Bulk score entry for your classes</p>
        </div>
        <a href="<?php echo e(route('teacher.scores.history')); ?>" class="sb-btn sb-btn-outline-secondary">Score History</a>
    </div>

    <?php if($errors->any()): ?>
        <div class="sb-alert sb-alert-danger">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="sb-card mb-4">
        <div class="card-body p-3">
            <h5 class="fw-semibold mb-3">Select Parameters</h5>
            <form method="GET" action="<?php echo e(route('teacher.scores.create')); ?>" id="filterForm">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="sb-form-label">Exam *</label>
                        <select name="exam_id" required onchange="this.form.submit()" class="sb-form-select">
                            <option value="">-- Select Exam --</option>
                            <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($exam->id); ?>" <?php echo e(old('exam_id', $selectedExam) == $exam->id ? 'selected' : ''); ?>><?php echo e($exam->name); ?> <?php echo e($exam->term ? '(' . $exam->term . ')' : ''); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="sb-form-label">Class *</label>
                        <select name="school_class_id" required onchange="this.form.submit()" class="sb-form-select">
                            <option value="">-- Select Class --</option>
                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>" <?php echo e(old('school_class_id', $selectedClass) == $class->id ? 'selected' : ''); ?>><?php echo e($class->name); ?><?php echo e($class->section ? ' - ' . $class->section : ''); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="sb-form-label">Subject *</label>
                        <select name="subject_id" required onchange="this.form.submit()" class="sb-form-select">
                            <option value="">-- Select Subject --</option>
                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($subject->id); ?>" <?php echo e(old('subject_id', $selectedSubject) == $subject->id ? 'selected' : ''); ?>><?php echo e($subject->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="sb-form-label">Assessment Type *</label>
                        <select name="assessment_type_id" required onchange="this.form.submit()" class="sb-form-select">
                            <option value="">-- Select Assessment --</option>
                            <?php $__currentLoopData = $assessmentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($type->id); ?>" <?php echo e(old('assessment_type_id', $selectedAssessmentType) == $type->id ? 'selected' : ''); ?>><?php echo e($type->name); ?> (<?php echo e($type->percentage); ?>%)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if($selectedExam && $selectedClass && $selectedSubject && $selectedAssessmentType): ?>
        <?php if($students->isEmpty()): ?>
            <div class="sb-card">
                <div class="card-body sb-empty-state">
                    <p>No active students found in the selected class.</p>
                </div>
            </div>
        <?php else: ?>
            <form method="POST" action="<?php echo e(route('teacher.scores.store')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="exam_id" value="<?php echo e($selectedExam); ?>">
                <input type="hidden" name="school_class_id" value="<?php echo e($selectedClass); ?>">
                <input type="hidden" name="subject_id" value="<?php echo e($selectedSubject); ?>">
                <input type="hidden" name="assessment_type_id" value="<?php echo e($selectedAssessmentType); ?>">

                <div class="sb-card sb-table-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-semibold mb-0">Enter Scores (<?php echo e($students->count()); ?> students)</h5>
                            <button type="button" onclick="fillAllScores(0)" class="sb-btn sb-btn-sm sb-btn-outline-danger">Clear All</button>
                        </div>
                        <div class="table-responsive">
                            <table class="sb-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Student Name</th>
                                        <th>Admission No</th>
                                        <th>Score (0-100)</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="text-muted"><?php echo e($index + 1); ?></td>
                                            <td class="fw-medium"><?php echo e($student->full_name); ?></td>
                                            <td class="text-muted"><?php echo e($student->admission_number); ?></td>
                                            <td>
                                                <input type="number" name="scores[<?php echo e($index); ?>][score]" class="score-input sb-form-input" style="width: 120px;" value="<?php echo e(old("scores.{$index}.score", $existingScores[$student->id] ?? '')); ?>" min="0" max="100" step="0.01" placeholder="0-100" required>
                                                <input type="hidden" name="scores[<?php echo e($index); ?>][student_id]" value="<?php echo e($student->id); ?>">
                                            </td>
                                            <td>
                                                <input type="text" name="scores[<?php echo e($index); ?>][remarks]" class="sb-form-input" style="width: 200px;" value="<?php echo e(old("scores.{$index}.remarks", $existingRemarks[$student->id] ?? '')); ?>" placeholder="Optional">
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="<?php echo e(route('teacher.dashboard')); ?>" class="sb-btn sb-btn-outline-secondary">Cancel</a>
                            <button type="submit" class="sb-btn sb-btn-primary">Save Scores</button>
                        </div>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    <?php else: ?>
        <div class="sb-card">
            <div class="card-body sb-empty-state">
                <p>Please select an exam, class, subject, and assessment type above to load students for score entry.</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    function fillAllScores(value) {
        document.querySelectorAll('.score-input').forEach(function(input) {
            input.value = value;
        });
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\OneDrive\Desktop\SKILL\APP School\School Project\schoolproject\school-system\resources\views/teacher/scores/create.blade.php ENDPATH**/ ?>