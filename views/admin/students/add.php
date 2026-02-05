<?php
$pageTitle = "Add New Student";
$currentPage = "students";
require_once 'views/templates/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="bi bi-person-plus"></i> Add New Student</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo APP_URL; ?>/admin/students/add">
                        <h5 class="mb-3 text-primary">Personal Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="student_id" class="form-label">Student ID *</label>
                                <input type="text" class="form-control" id="student_id" name="student_id" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contact" class="form-label">Contact Number *</label>
                                <input type="tel" class="form-control" id="contact" name="contact" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="suffix" class="form-label">Suffix (Jr., Sr., III, etc.)</label>
                                <input type="text" class="form-control" id="suffix" name="suffix">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="course" class="form-label">Course/Program *</label>
                                <select class="form-select" id="course" name="course" required>
                                    <option value="">Select Course</option>
                                    <option value="BSIT">BS Information Technology</option>
                                    <option value="BSCS">BS Computer Science</option>
                                    <option value="BSIS">BS Information Systems</option>
                                    <option value="BSCE">BS Computer Engineering</option>
                                    <option value="BSBA">BS Business Administration</option>
                                    <option value="BSED">BS Education</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="year_section" class="form-label">Year & Section</label>
                                <input type="text" class="form-control" id="year_section" name="year_section" 
                                       placeholder="e.g., 3rd Year - B">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username *</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                                <div class="form-text">Leave empty to use Student ID as username</div>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        <h5 class="mb-3 text-primary">Address Information (Optional)</h5>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="barangay" class="form-label">Barangay</label>
                                <input type="text" class="form-control" id="barangay" name="barangay">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="municipality" class="form-label">Municipality/City</label>
                                <input type="text" class="form-control" id="municipality" name="municipality">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="province" class="form-label">Province</label>
                                <input type="text" class="form-control" id="province" name="province">
                            </div>
                        </div>
                        
                        <div class="alert alert-info mt-4">
                            <i class="bi bi-info-circle"></i> 
                            <strong>Note:</strong> A default password will be automatically generated. 
                            Student can change it after first login.
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?php echo APP_URL; ?>/admin/students" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Add Student
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-lightbulb"></i> Tips for Adding Students</h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>Student ID must be unique</li>
                        <li>Username must be unique (or leave empty to use Student ID)</li>
                        <li>Provide complete name for better identification</li>
                        <li>Include contact number for communication</li>
                        <li>Address information is optional but recommended</li>
                        <li>Default password will be sent to student's contact if provided</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-generate username from student ID
document.getElementById('student_id').addEventListener('blur', function() {
    const usernameField = document.getElementById('username');
    if (!usernameField.value) {
        usernameField.value = this.value;
    }
});
</script>

<?php require_once 'views/templates/footer.php'; ?>