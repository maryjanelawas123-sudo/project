<?php
$pageTitle = "Edit Student";
$currentPage = "students";
require_once 'views/templates/header.php';

$student = $student ?? [];
$address = $address ?? [];
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <h4 class="mb-0"><i class="bi bi-pencil"></i> Edit Student</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo APP_URL; ?>/admin/students/edit/<?php echo $student['id']; ?>">
                        <h5 class="mb-3 text-primary">Personal Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="student_id" class="form-label">Student ID *</label>
                                <input type="text" class="form-control" id="student_id" name="student_id" 
                                       value="<?php echo htmlspecialchars($student['student_id'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contact" class="form-label">Contact Number *</label>
                                <input type="tel" class="form-control" id="contact" name="contact" 
                                       value="<?php echo htmlspecialchars($student['contact'] ?? ''); ?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                       value="<?php echo htmlspecialchars($student['first_name'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name" 
                                       value="<?php echo htmlspecialchars($student['middle_name'] ?? ''); ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                       value="<?php echo htmlspecialchars($student['last_name'] ?? ''); ?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="suffix" class="form-label">Suffix (Jr., Sr., III, etc.)</label>
                                <input type="text" class="form-control" id="suffix" name="suffix" 
                                       value="<?php echo htmlspecialchars($student['suffix'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="course" class="form-label">Course/Program *</label>
                                <select class="form-select" id="course" name="course" required>
                                    <option value="">Select Course</option>
                                    <option value="BSIT" <?php echo ($student['course'] ?? '') == 'BSIT' ? 'selected' : ''; ?>>BS Information Technology</option>
                                    <option value="BSCS" <?php echo ($student['course'] ?? '') == 'BSCS' ? 'selected' : ''; ?>>BS Computer Science</option>
                                    <option value="BSIS" <?php echo ($student['course'] ?? '') == 'BSIS' ? 'selected' : ''; ?>>BS Information Systems</option>
                                    <option value="BSCE" <?php echo ($student['course'] ?? '') == 'BSCE' ? 'selected' : ''; ?>>BS Computer Engineering</option>
                                    <option value="BSBA" <?php echo ($student['course'] ?? '') == 'BSBA' ? 'selected' : ''; ?>>BS Business Administration</option>
                                    <option value="BSED" <?php echo ($student['course'] ?? '') == 'BSED' ? 'selected' : ''; ?>>BS Education</option>
                                    <option value="Other" <?php echo ($student['course'] ?? '') == 'Other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="year_section" class="form-label">Year & Section</label>
                                <input type="text" class="form-control" id="year_section" name="year_section" 
                                       value="<?php echo htmlspecialchars($student['year_section'] ?? ''); ?>" 
                                       placeholder="e.g., 3rd Year - B">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username *</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?php echo htmlspecialchars($student['username'] ?? ''); ?>" required>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        <h5 class="mb-3 text-primary">Address Information</h5>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="barangay" class="form-label">Barangay</label>
                                <input type="text" class="form-control" id="barangay" name="barangay" 
                                       value="<?php echo htmlspecialchars($address['barangay'] ?? ''); ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="municipality" class="form-label">Municipality/City</label>
                                <input type="text" class="form-control" id="municipality" name="municipality" 
                                       value="<?php echo htmlspecialchars($address['municipality'] ?? ''); ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="province" class="form-label">Province</label>
                                <input type="text" class="form-control" id="province" name="province" 
                                       value="<?php echo htmlspecialchars($address['province'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?php echo APP_URL; ?>/admin/students" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <div>
                                <a href="<?php echo APP_URL; ?>/admin/students/delete/<?php echo $student['id']; ?>" 
                                   class="btn btn-danger confirm-delete">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-check-circle"></i> Update Student
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-key"></i> Account Management</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Reset Password</h6>
                            <p>Generate a new temporary password for this student.</p>
                            <a href="<?php echo APP_URL; ?>/admin/students/reset-password/<?php echo $student['id']; ?>" 
                               class="btn btn-warning btn-sm" onclick="return confirm('Reset password for this student?')">
                                <i class="bi bi-key"></i> Reset Password
                            </a>
                        </div>
                        <div class="col-md-6">
                            <h6>Account Status</h6>
                            <ul class="list-unstyled mb-0">
                                <li><strong>Created:</strong> <?php echo date('F j, Y', strtotime($student['created_at'])); ?></li>
                                <li><strong>Last Login:</strong> Recently</li>
                                <li><strong>Status:</strong> <span class="badge bg-success">Active</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/templates/footer.php'; ?>