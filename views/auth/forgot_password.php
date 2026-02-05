<?php
$pageTitle = "Forgot Password";
require_once 'views/templates/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0 text-center"><i class="bi bi-key"></i> Password Recovery</h4>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Enter your username to receive a temporary password.
                    </div>
                    
                    <form method="POST" action="<?php echo APP_URL; ?>/forgot-password">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                            <div class="form-text">Enter the username you use to login</div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning btn-lg">
                                <i class="bi bi-envelope"></i> Send Reset Code
                            </button>
                            <a href="<?php echo APP_URL; ?>/login" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Login
                            </a>
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
                <div class="alert alert-secondary">
                    <h6><i class="bi bi-lightbulb"></i> Tips:</h6>
                    <ul class="text-start">
                        <li>Check your email for the temporary password</li>
                        <li>Use the temporary password to login</li>
                        <li>Change your password immediately after login</li>
                        <li>Contact admin if you don't receive the password</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/templates/footer.php'; ?>