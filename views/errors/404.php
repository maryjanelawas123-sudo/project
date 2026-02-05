<?php
$pageTitle = "Page Not Found";
require_once 'views/templates/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="error-page">
                <h1 class="display-1 text-muted">404</h1>
                <h2 class="mb-4">Page Not Found</h2>
                <p class="lead mb-4">
                    The page you are looking for might have been removed, had its name changed, 
                    or is temporarily unavailable.
                </p>
                
                <div class="row mt-5">
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <i class="bi bi-house-door" style="font-size: 2rem; color: #007bff;"></i>
                                <h5 class="mt-3">Go Home</h5>
                                <a href="<?php echo APP_URL; ?>" class="btn btn-outline-primary btn-sm mt-2">
                                    Home Page
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <i class="bi bi-arrow-left" style="font-size: 2rem; color: #6c757d;"></i>
                                <h5 class="mt-3">Go Back</h5>
                                <button onclick="history.back()" class="btn btn-outline-secondary btn-sm mt-2">
                                    Previous Page
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <i class="bi bi-search" style="font-size: 2rem; color: #28a745;"></i>
                                <h5 class="mt-3">Search</h5>
                                <form action="<?php echo APP_URL; ?>/search" method="GET" class="mt-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm" placeholder="Search...">
                                        <button class="btn btn-outline-success btn-sm" type="submit">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info mt-5">
                    <h5><i class="bi bi-info-circle"></i> Need Help?</h5>
                    <p class="mb-0">
                        If you believe this is an error, please contact the system administrator at 
                        <a href="mailto:admin@mcc.edu">admin@mcc.edu</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/templates/footer.php'; ?>