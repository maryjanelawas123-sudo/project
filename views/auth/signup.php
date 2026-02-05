<?php
$pageTitle = "Sign Up";
require_once 'views/templates/header.php';
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0 text-center"><i class="bi bi-person-plus"></i> Student Registration</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="<?php echo APP_URL; ?>/signup" enctype="multipart/form-data">
                        <h5 class="mb-3 text-primary">Personal Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="student_id" class="form-label">Student ID *</label>
                                <input type="text" class="form-control" id="student_id" name="student_id" 
                                       value="<?php echo $_POST['student_id'] ?? ''; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contact" class="form-label">Contact Number *</label>
                                <input type="tel" class="form-control" id="contact" name="contact" 
                                       value="<?php echo $_POST['contact'] ?? ''; ?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                       value="<?php echo $_POST['first_name'] ?? ''; ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name" 
                                       value="<?php echo $_POST['middle_name'] ?? ''; ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                       value="<?php echo $_POST['last_name'] ?? ''; ?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="suffix" class="form-label">Suffix (Jr., Sr., III, etc.)</label>
                                <input type="text" class="form-control" id="suffix" name="suffix" 
                                       value="<?php echo $_POST['suffix'] ?? ''; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="course" class="form-label">Course/Program *</label>
                                <select class="form-select" id="course" name="course" required>
                                    <option value="">Select Course</option>
                                    <option value="BSIT" <?php echo (isset($_POST['course']) && $_POST['course'] == 'BSIT') ? 'selected' : ''; ?>>BS Information Technology</option>
                                    <option value="BSCS" <?php echo (isset($_POST['course']) && $_POST['course'] == 'BSCS') ? 'selected' : ''; ?>>BS Computer Science</option>
                                    <option value="BSIS" <?php echo (isset($_POST['course']) && $_POST['course'] == 'BSIS') ? 'selected' : ''; ?>>BS Information Systems</option>
                                    <option value="BSCE" <?php echo (isset($_POST['course']) && $_POST['course'] == 'BSCE') ? 'selected' : ''; ?>>BS Computer Engineering</option>
                                    <option value="BSBA" <?php echo (isset($_POST['course']) && $_POST['course'] == 'BSBA') ? 'selected' : ''; ?>>BS Business Administration</option>
                                    <option value="BSED" <?php echo (isset($_POST['course']) && $_POST['course'] == 'BSED') ? 'selected' : ''; ?>>BS Education</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="year_section" class="form-label">Year & Section</label>
                                <input type="text" class="form-control" id="year_section" name="year_section" 
                                       value="<?php echo $_POST['year_section'] ?? ''; ?>" 
                                       placeholder="e.g., 3rd Year - B">
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        <h5 class="mb-3 text-primary">Address Information</h5>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="barangay" class="form-label">Barangay</label>
                                <input type="text" class="form-control" id="barangay" name="barangay" 
                                       value="<?php echo $_POST['barangay'] ?? ''; ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="municipality" class="form-label">Municipality/City</label>
                                <input type="text" class="form-control" id="municipality" name="municipality" 
                                       value="<?php echo $_POST['municipality'] ?? ''; ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="province" class="form-label">Province</label>
                                <input type="text" class="form-control" id="province" name="province" 
                                       value="<?php echo $_POST['province'] ?? ''; ?>">
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        <h5 class="mb-3 text-primary">Account Credentials</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username *</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?php echo $_POST['username'] ?? ''; ?>" required>
                                <div class="form-text">Choose a unique username for login</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo $_POST['email'] ?? ''; ?>">
                                <div class="form-text">Optional, for account recovery</div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password *</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="form-text">Minimum 6 characters</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password *</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a> *
                            </label>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-person-check"></i> Register Account
                            </button>
                            <a href="<?php echo APP_URL; ?>/login" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Login
                            </a>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center bg-light">
                    <small class="text-muted">
                        Already have an account? <a href="<?php echo APP_URL; ?>/login">Login here</a>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>MCC Lost & Found System Terms of Use</h6>
                <p>By registering for this system, you agree to:</p>
                <ol>
                    <li>Provide accurate and truthful information</li>
                    <li>Use the system only for legitimate lost and found purposes</li>
                    <li>Respect the privacy of other users</li>
                    <li>Not submit fraudulent claims</li>
                    <li>Comply with MCC policies and regulations</li>
                    <li>Accept that false information may result in account suspension</li>
                </ol>
                <p><strong>Data Privacy:</strong> Your information will be used solely for the purpose of managing lost and found items within MCC premises.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Password confirmation validation
document.getElementById('signupForm')?.addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Passwords do not match!');
        return false;
    }
    
    if (password.length < 6) {
        e.preventDefault();
        alert('Password must be at least 6 characters!');
        return false;
    }
});
</script>

<?php require_once 'views/templates/footer.php'; ?>