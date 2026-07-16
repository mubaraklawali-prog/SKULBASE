<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Admission - Skulbase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .form-card { max-width: 800px; margin: 40px auto; }
        .form-header { background: #4f9cf7; color: #fff; padding: 30px; border-radius: 12px 12px 0 0; text-align: center; }
        .form-body { background: #fff; padding: 30px; border-radius: 0 0 12px 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        .form-control, .form-select { border-radius: 8px; border: 1px solid #dee2e6; padding: 10px 16px; }
        .btn-primary { background: #4f9cf7; border: none; border-radius: 8px; padding: 10px 24px; font-weight: 500; }
        .btn-primary:hover { background: #3a8ae8; }
        .section-title { font-weight: 600; color: #1a1a2e; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f0f2f5; }
        .required { color: #dc3545; }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h2 style="margin: 0; font-weight: 700;">School Admission Application</h2>
                <p style="margin: 10px 0 0; opacity: 0.9;">Complete the form below to apply for admission</p>
            </div>
            <div class="form-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px; background: #d1e7dd; border-color: #badbcc; color: #0f5132;">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger" style="border-radius: 10px;">
                        <strong>Please correct the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admissions.submit') }}" enctype="multipart/form-data">
                    @csrf

                    <h5 class="section-title">School Selection</h5>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label class="form-label">Select School <span class="required">*</span></label>
                            <select name="school_id" class="form-select" required>
                                <option value="">Choose a school...</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                        {{ $school->name }} - {{ $school->city ?? '' }}, {{ $school->state ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div id="classSection" style="display: none;">
                        <h5 class="section-title">Student Information</h5>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Full Name <span class="required">*</span></label>
                                <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" required placeholder="Enter student's full name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Gender <span class="required">*</span></label>
                                <select name="gender" class="form-select" required>
                                    <option value="">Select gender...</option>
                                    <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Date of Birth <span class="required">*</span></label>
                                <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Class Applying For <span class="required">*</span></label>
                                <select name="class_id" class="form-select" id="classSelect" required>
                                    <option value="">Select class...</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Previous School</label>
                                <input type="text" name="previous_school" class="form-control" value="{{ old('previous_school') }}" placeholder="Enter previous school name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Passport Photo</label>
                                <input type="file" name="passport" class="form-control" accept="image/*">
                                <small class="text-muted">Max 2MB. JPG, PNG only.</small>
                            </div>
                        </div>

                        <h5 class="section-title">Parent/Guardian Information</h5>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Parent/Guardian Name <span class="required">*</span></label>
                                <input type="text" name="parent_name" class="form-control" value="{{ old('parent_name') }}" required placeholder="Enter parent/guardian name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number <span class="required">*</span></label>
                                <input type="text" name="parent_phone" class="form-control" value="{{ old('parent_phone') }}" required placeholder="Enter phone number">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="parent_email" class="form-control" value="{{ old('parent_email') }}" placeholder="Enter email address">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Address <span class="required">*</span></label>
                                <input type="text" name="address" class="form-control" value="{{ old('address') }}" required placeholder="Enter residential address">
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Submit Application</button>
                        </div>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <a href="{{ url('/') }}" style="color: #6c757d; text-decoration: none;">← Back to Home</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const oldClassId = "{{ old('class_id') }}";

        document.addEventListener('DOMContentLoaded', function() {
            const schoolSelect = document.querySelector('select[name="school_id"]');
            const classSection = document.getElementById('classSection');
            const classSelect = document.getElementById('classSelect');

            schoolSelect.addEventListener('change', function() {
                const schoolId = this.value;
                if (schoolId) {
                    classSection.style.display = 'block';
                    fetchClasses(schoolId);
                } else {
                    classSection.style.display = 'none';
                    classSelect.innerHTML = '<option value="">Select class...</option>';
                }
            });

            if (schoolSelect.value) {
                classSection.style.display = 'block';
                fetchClasses(schoolSelect.value);
            }

            function fetchClasses(schoolId) {
                fetch(`/api/schools/${schoolId}/classes`)
                    .then(response => response.json())
                    .then(data => {
                        classSelect.innerHTML = '<option value="">Select class...</option>';
                        data.forEach(cls => {
                            const selected = oldClassId == cls.id ? "selected" : "";
                            classSelect.innerHTML += `<option value="${cls.id}" ${selected}>${cls.name}</option>`;
                        });
                    })
                    .catch(() => {
                        classSelect.innerHTML = '<option value="">Error loading classes</option>';
                    });
            }
        });
    </script>
</body>
</html>
