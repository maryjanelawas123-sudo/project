<?php
$pageTitle = "Change Password";
require_once 'views/templates/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0 text-center"><i class="bi bi-key"></i> Change Password</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="<?php echo APP_URL; ?>/change-password">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                            <div class="form-text">Minimum 6 characters</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle"></i> Change Password
                            </button>
                            <a href="<?php echo APP_URL; ?>/student/dashboard" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center bg-light">
                    <small class="text-muted">
                        <i class="bi bi-shield-check"></i> Password Security Tips:
                        <ul class="text-start mt-2 mb-0">
                            <li>Use at least 8 characters</li>
                            <li>Include numbers and special characters</li>
                            <li>Avoid using personal information</li>
                            <li>Don't reuse old passwords</li>
                        </ul>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Password validation
document.querySelector('form').addEventListener('submit', function(e) {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (newPassword !== confirmPassword) {
        e.preventDefault();
        alert('New passwords do not match!');
        return false;
    }
    
    if (newPassword.length < 6) {
        e.preventDefault();
        alert('Password must be at least 6 characters!');
        return false;
    }
});
</script>

<?php require_once 'views/templates/footer.php'; ?>