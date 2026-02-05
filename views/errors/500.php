<?php
$pageTitle = "Server Error";
require_once 'views/templates/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="error-page">
                <h1 class="display-1 text-danger">500</h1>
                <h2 class="mb-4">Internal Server Error</h2>
                <p class="lead mb-4">
                    Something went wrong on our end. Please try again later.
                </p>
                
                <div class="alert alert-danger">
                    <h5><i class="bi bi-exclamation-triangle"></i> Technical Details</h5>
                    <p class="mb-0">
                        The server encountered an unexpected condition that prevented it from fulfilling the request.
                    </p>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <i class="bi bi-house-door" style="font-size: 2rem;"></i>
                                <h5 class="mt-3">Go Home</h5>
                                <a href="<?php echo APP_URL; ?>" class="btn btn-primary btn-sm mt-2">
                                    Home Page
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <i class="bi bi-arrow-clockwise" style="font-size: 2rem;"></i>
                                <h5 class="mt-3">Refresh</h5>
                                <button onclick="location.reload()" class="btn btn-warning btn-sm mt-2">
                                    Refresh Page
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <i class="bi bi-envelope" style="font-size: 2rem;"></i>
                                <h5 class="mt-3">Report</h5>
                                <a href="mailto:<?php echo getenv('ADMIN_EMAIL') ?: 'admin@mcc.edu'; ?>" class="btn btn-info btn-sm mt-2">
                                    Contact Admin
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-5">
                    <p class="text-muted">
                        If the problem persists, please contact the system administrator.
                    </p>
                    <p>
                        <small>
                            Error ID: <?php echo uniqid(); ?><br>
                            Time: <?php echo date('Y-m-d H:i:s'); ?>
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/templates/footer.php'; ?>