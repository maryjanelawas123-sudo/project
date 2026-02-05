<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - <?php echo $pageTitle ?? 'Lost & Found System'; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <!-- Select2 CSS (for better select boxes) -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/static/css/style.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo APP_URL; ?>/static/images/favicon.ico">
    
    <!-- Additional CSS for specific pages -->
    <?php if(isset($additionalCSS)): ?>
        <?php foreach($additionalCSS as $css): ?>
            <link rel="stylesheet" href="<?php echo $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <style>
        /* Inline styles for immediate rendering */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --info-color: #17a2b8;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            padding-top: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .main-content {
            flex: 1;
        }
        
        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-lost { background-color: #ffc107; color: #000; }
        .status-found { background-color: #17a2b8; color: white; }
        .status-claimed { background-color: #28a745; color: white; }
        .status-pending { background-color: #6c757d; color: white; }
        .status-approved { background-color: #28a745; color: white; }
        .status-rejected { background-color: #dc3545; color: white; }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.08);
            transition: transform 0.2s;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.12);
        }
        
        .card-header {
            border-radius: 10px 10px 0 0 !important;
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
        }
        
        .btn-primary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.08);
        }
        
        .page-title {
            color: var(--primary-color);
            border-bottom: 3px solid var(--secondary-color);
            padding-bottom: 10px;
            margin-bottom: 25px;
        }
        
        .alert {
            border-radius: 8px;
            border: none;
        }
        
        .item-image {
            max-width: 150px;
            max-height: 150px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        
        .stat-card {
            transition: all 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }
        
        .sidebar {
            min-height: calc(100vh - 56px);
            background: linear-gradient(180deg, var(--primary-color) 0%, #1a252f 100%);
            color: white;
            width: 250px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 15px;
            border-left: 3px solid transparent;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
            border-left-color: var(--secondary-color);
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        
        /* Print styles */
        @media print {
            .no-print {
                display: none !important;
            }
            
            .card {
                box-shadow: none;
                border: 1px solid #dee2e6;
            }
        }
    </style>
</head>
<body>
    <?php if(!isset($hideNavbar)): ?>
    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="<?php echo APP_URL; ?>">
                <i class="bi bi-search me-2"></i>
                <span class="fw-bold"><?php echo APP_NAME; ?></span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto">
                    <?php if(isset($_SESSION['admin'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($currentPage == 'dashboard') ? 'active' : ''; ?>" 
                               href="<?php echo APP_URL; ?>/admin/dashboard">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($currentPage == 'items') ? 'active' : ''; ?>" 
                               href="<?php echo APP_URL; ?>/admin/items">
                                <i class="bi bi-box-seam"></i> Items
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($currentPage == 'students') ? 'active' : ''; ?>" 
                               href="<?php echo APP_URL; ?>/admin/students">
                                <i class="bi bi-people"></i> Students
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($currentPage == 'claims') ? 'active' : ''; ?>" 
                               href="<?php echo APP_URL; ?>/admin/claims">
                                <i class="bi bi-clipboard-check"></i> Claims
                                <?php if(isset($pendingClaimsCount) && $pendingClaimsCount > 0): ?>
                                    <span class="badge bg-danger rounded-pill ms-1"><?php echo $pendingClaimsCount; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php elseif(isset($_SESSION['student_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($currentPage == 'student-dashboard') ? 'active' : ''; ?>" 
                               href="<?php echo APP_URL; ?>/student/dashboard">
                                <i class="bi bi-house-door"></i> Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($currentPage == 'profile') ? 'active' : ''; ?>" 
                               href="<?php echo APP_URL; ?>/student/profile">
                                <i class="bi bi-person-circle"></i> Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#reportModal">
                                <i class="bi bi-plus-circle"></i> Report Item
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
                
                <ul class="navbar-nav ms-auto">
                    <?php if(isset($_SESSION['student_id']) && isset($_SESSION['student_name'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" 
                               role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-2"></i>
                                <span class="d-none d-md-inline"><?php echo htmlspecialchars($_SESSION['student_name']); ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="<?php echo APP_URL; ?>/student/profile">
                                        <i class="bi bi-person me-2"></i> My Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo APP_URL; ?>/change-password">
                                        <i class="bi bi-key me-2"></i> Change Password
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="<?php echo APP_URL; ?>/logout">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php elseif(isset($_SESSION['admin'])): ?>
                        <li class="nav-item">
                            <span class="nav-link text-success">
                                <i class="bi bi-shield-check me-1"></i>
                                <span class="d-none d-md-inline">Admin Mode</span>
                            </span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo APP_URL; ?>/logout">
                                <i class="bi bi-box-arrow-right"></i>
                                <span class="d-none d-md-inline"> Logout</span>
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo APP_URL; ?>/login">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo APP_URL; ?>/signup">
                                <i class="bi bi-person-plus"></i> Sign Up
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>
    
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar for Admin -->
            <?php if(isset($_SESSION['admin']) && !isset($hideSidebar)): ?>
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse no-print" id="sidebar">
                <div class="position-sticky pt-3">
                    <div class="px-3 mb-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-shield-check text-success" style="font-size: 1.5rem;"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">Admin Panel</h6>
                                <small class="text-white-50"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></small>
                            </div>
                        </div>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item mb-1">
                            <a class="nav-link <?php echo ($currentPage == 'dashboard') ? 'active' : ''; ?>" 
                               href="<?php echo APP_URL; ?>/admin/dashboard">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a class="nav-link <?php echo ($currentPage == 'items') ? 'active' : ''; ?>" 
                               href="<?php echo APP_URL; ?>/admin/items">
                                <i class="bi bi-box-seam"></i> Items Management
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a class="nav-link <?php echo ($currentPage == 'students') ? 'active' : ''; ?>" 
                               href="<?php echo APP_URL; ?>/admin/students">
                                <i class="bi bi-people"></i> Students Management
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a class="nav-link <?php echo ($currentPage == 'claims') ? 'active' : ''; ?>" 
                               href="<?php echo APP_URL; ?>/admin/claims">
                                <i class="bi bi-clipboard-check"></i> Claims Management
                                <?php if(isset($pendingClaimsCount) && $pendingClaimsCount > 0): ?>
                                    <span class="badge bg-danger rounded-pill float-end"><?php echo $pendingClaimsCount; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a class="nav-link" href="<?php echo APP_URL; ?>/qr" target="_blank">
                                <i class="bi bi-qr-code"></i> Generate QR Code
                            </a>
                        </li>
                        <li class="nav-item mb-1">
                            <a class="nav-link" href="<?php echo APP_URL; ?>/admin/export/items/csv">
                                <i class="bi bi-download"></i> Export Data
                            </a>
                        </li>
                    </ul>
                    
                    <hr class="bg-light my-4">
                    
                    <div class="px-3">
                        <small class="text-white-50 mb-2 d-block">Quick Stats</small>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="bg-dark rounded p-2 text-center">
                                    <small class="d-block text-white-50">Items</small>
                                    <span class="fw-bold text-white"><?php echo $totalItems ?? 0; ?></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-dark rounded p-2 text-center">
                                    <small class="d-block text-white-50">Students</small>
                                    <span class="fw-bold text-white"><?php echo $totalStudents ?? 0; ?></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-dark rounded p-2 text-center">
                                    <small class="d-block text-white-50">Pending</small>
                                    <span class="fw-bold text-warning"><?php echo $pendingClaimsCount ?? 0; ?></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-dark rounded p-2 text-center">
                                    <small class="d-block text-white-50">Claimed</small>
                                    <span class="fw-bold text-success"><?php echo $claimedItems ?? 0; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 px-3">
                        <button class="btn btn-sm btn-outline-light w-100" onclick="toggleSidebar()">
                            <i class="bi bi-arrow-left-circle"></i> Collapse Sidebar
                        </button>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Main Content Area -->
            <main class="main-content <?php echo (isset($_SESSION['admin']) && !isset($hideSidebar)) ? 'col-md-9 col-lg-10' : 'col-12'; ?> px-md-4 py-3">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2 page-title mb-0">
                        <?php if(isset($pageIcon)): ?>
                            <i class="bi bi-<?php echo $pageIcon; ?> me-2"></i>
                        <?php endif; ?>
                        <?php echo $pageTitle ?? 'Dashboard'; ?>
                    </h1>
                    
                    <div class="btn-toolbar">
                        <?php if(isset($_SESSION['student_name'])): ?>
                            <span class="me-3 text-muted">
                                <i class="bi bi-person-fill"></i> 
                                <?php echo htmlspecialchars($_SESSION['student_name']); ?>
                            </span>
                        <?php elseif(isset($_SESSION['admin_name'])): ?>
                            <span class="me-3 text-success">
                                <i class="bi bi-shield-check"></i> 
                                <?php echo htmlspecialchars($_SESSION['admin_name']); ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if(isset($actionButtons)): ?>
                            <?php foreach($actionButtons as $button): ?>
                                <a href="<?php echo $button['url']; ?>" 
                                   class="btn btn-<?php echo $button['type'] ?? 'primary'; ?> btn-sm ms-2">
                                    <i class="bi bi-<?php echo $button['icon'] ?? 'plus'; ?>"></i>
                                    <?php echo $button['text']; ?>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <?php if(isset($_SESSION['admin'])): ?>
                            <button class="btn btn-outline-secondary btn-sm ms-2 d-md-none" 
                                    onclick="toggleSidebar()">
                                <i class="bi bi-list"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Flash Messages -->
                <?php if(isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <?php echo $_SESSION['success']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>
                
                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <?php echo $_SESSION['error']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>
                
                <?php if(isset($_SESSION['warning'])): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        <?php echo $_SESSION['warning']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['warning']); ?>
                <?php endif; ?>
                
                <?php if(isset($_SESSION['info'])): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <?php echo $_SESSION['info']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['info']); ?>
                <?php endif; ?>