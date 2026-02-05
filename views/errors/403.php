<?php
$pageTitle = "Access Denied";
require_once 'views/templates/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="error-page">
                <h1 class="display-1 text-danger">403</h1>
                <h2 class="mb-4">Access Denied</h2>
                <p class="lead mb-4">
                    You do not have permission to access this page.
                </p>
                
                <div class="row mt-4">
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <i class="bi bi-house-door" style="font-size: 2rem;"></i>
                                <h5 class="mt-3">Go Home</h5>
                                <a href="<?php echo APP_URL; ?>" class="btn btn-primary mt-2">
                                    Home Page
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <i class="bi bi-arrow-left" style="font-size: 2rem;"></i>
                                <h5 class="mt-3">Go Back</h5>
                                <button onclick="history.back()" class="btn btn-secondary mt-2">
                                    Previous Page
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php if(!isset($_SESSION['student_id']) && !isset($_SESSION['admin'])): ?>
                <div class="alert alert-info mt-4">
                    <h5><i class="bi bi-info-circle"></i> Need Access?</h5>
                    <p class="mb-0">
                        You may need to <a href="<?php echo APP_URL; ?>/login">login</a> to access this page.
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/templates/footer.php'; ?>