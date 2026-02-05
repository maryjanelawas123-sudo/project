<?php
$pageTitle = "My Profile";
$currentPage = "profile";
require_once 'views/templates/header.php';

$student = $student ?? [];
$address = $address ?? [];
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-person-circle"></i> My Profile</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo APP_URL; ?>/student/profile">
                        <div class="row mb-4">
                            <div class="col-md-3 text-center">
                                <div class="mb-3">
                                    <div class="profile-pic bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                                         style="width: 150px; height: 150px;">
                                        <i class="bi bi-person" style="font-size: 4rem; color: white;"></i>
                                    </div>
                                </div>
                                <div class="alert alert-info">
                                    <small><i class="bi bi-info-circle"></i> Profile photo coming soon</small>
                                </div>
                            </div>
                            
                            <div class="col-md-9">
                                <h5 class="border-bottom pb-2 mb-3">Account Information</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Student ID</label>
                                        <input type="text" class="form-control bg-light" 
                                               value="<?php echo htmlspecialchars($student['student_id'] ?? ''); ?>" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Username</label>
                                        <input type="text" class="form-control bg-light" 
                                               value="<?php echo htmlspecialchars($student['username'] ?? ''); ?>" readonly>
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
                                        <label for="suffix" class="form-label">Suffix</label>
                                        <input type="text" class="form-control" id="suffix" name="suffix" 
                                               value="<?php echo htmlspecialchars($student['suffix'] ?? ''); ?>" 
                                               placeholder="Jr., Sr., III, etc.">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="contact" class="form-label">Contact Number *</label>
                                        <input type="tel" class="form-control" id="contact" name="contact" 
                                               value="<?php echo htmlspecialchars($student['contact'] ?? ''); ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <h5 class="border-bottom pb-2 mb-3">Academic Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="course" class="form-label">Course/Program</label>
                                <select class="form-select" id="course" name="course">
                                    <option value="">Select Course</option>
                                    <option value="BSIT" <?php echo ($student['course'] ?? '') == 'BSIT' ? 'selected' : ''; ?>>BS Information Technology</option>
                                    <option value="BSCS" <?php echo ($student['course'] ?? '') == 'BSCS' ? 'selected' : ''; ?>>BS Computer Science</option>
                                    <option value="BSIS" <?php echo ($student['course'] ?? '') == 'BSIS' ? 'selected' : ''; ?>>BS Information Systems</option>
                                    <option value="BSCE" <?php echo ($student['course'] ?? '') == 'BSCE' ? 'selected' : ''; ?>>BS Computer Engineering</option>
                                    <option value="BSBA" <?php echo ($student['course'] ?? '') == 'BSBA' ? 'selected' : ''; ?>>BS Business Administration</option>
                                    <option value="BSED" <?php echo ($student['course'] ?? '') == 'BSED' ? 'selected' : ''; ?>>BS Education</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="year_section" class="form-label">Year & Section</label>
                                <input type="text" class="form-control" id="year_section" name="year_section" 
                                       value="<?php echo htmlspecialchars($student['year_section'] ?? ''); ?>" 
                                       placeholder="e.g., 3rd Year - B">
                            </div>
                        </div>
                        
                        <h5 class="border-bottom pb-2 mb-3 mt-4">Address Information</h5>
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
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-check-circle"></i> Update Profile
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-grid gap-2">
                                    <a href="<?php echo APP_URL; ?>/change-password" class="btn btn-warning btn-lg">
                                        <i class="bi bi-key"></i> Change Password
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="card-footer bg-light">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="bi bi-clock-history"></i> Account Activity</h6>
                            <ul class="list-unstyled">
                                <li><small>Member since: <?php echo date('F j, Y', strtotime($student['created_at'] ?? 'now')); ?></small></li>
                                <li><small>Last login: Recently</small></li>
                            </ul>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="<?php echo APP_URL; ?>/student/dashboard" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/templates/footer.php'; ?>