<?php
$pageTitle = "Login";
require_once 'views/templates/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0 text-center"><i class="bi bi-box-arrow-in-right"></i> MCC Lost & Found Login</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="<?php echo APP_URL; ?>/login">
                        <div class="mb-3">
                            <label for="role" class="form-label">Login As</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="student" <?php echo (isset($_POST['role']) && $_POST['role'] == 'student') ? 'selected' : ''; ?>>Student</option>
                                <option value="admin" <?php echo (isset($_POST['role']) && $_POST['role'] == 'admin') ? 'selected' : ''; ?>>Administrator</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?php echo $_POST['username'] ?? ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </button>
                        </div>
                        
                        <div class="mt-3 text-center">
                            <a href="<?php echo APP_URL; ?>/forgot-password" class="text-decoration-none">
                                <i class="bi bi-question-circle"></i> Forgot Password?
                            </a>
                            <span class="mx-2">|</span>
                            <a href="<?php echo APP_URL; ?>/signup" class="text-decoration-none">
                                <i class="bi bi-person-plus"></i> Create Account
                            </a>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="text-center">
                            <p class="text-muted mb-2">Or login with:</p>
                            <div class="d-grid gap-2">
                                <a href="<?php echo APP_URL; ?>/login_microsoft" class="btn btn-outline-primary">
                                    <i class="bi bi-microsoft"></i> Microsoft Account
                                </a>
                                <a href="<?php echo APP_URL; ?>/login_google" class="btn btn-outline-danger">
                                    <i class="bi bi-google"></i> Google Account
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center bg-light">
                    <small class="text-muted">
                        Need help? Contact MCC IT Support at 
                        <a href="mailto:it-support@mcc.edu">it-support@mcc.edu</a>
                    </small>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <div class="alert alert-info">
                    <h5><i class="bi bi-qr-code-scan"></i> Quick Access</h5>
                    <p>Scan the QR code below to access the system on your mobile device:</p>
                    <img src="<?php echo APP_URL; ?>/qr" alt="QR Code" class="img-fluid" style="max-width: 200px;">
                    <p class="mt-2">
                        <a href="<?php echo APP_URL; ?>/qr" target="_blank">View QR Code</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/templates/footer.php'; ?>