<?php if(!isset($hideNavbar)): ?>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?php echo APP_URL; ?>">
            <img src="<?php echo APP_URL; ?>/static/images/logo.png" alt="MCC Logo" height="40" class="me-2">
            <span class="fw-bold text-primary">Lost & Found</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto">
                <?php if(isset($_SESSION['student_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($currentPage == 'student-dashboard') ? 'active' : ''; ?>" 
                           href="<?php echo APP_URL; ?>/student/dashboard">
                            <i class="bi bi-house-door"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($currentPage == 'report-item') ? 'active' : ''; ?>" 
                           href="#" data-bs-toggle="modal" data-bs-target="#reportModal">
                            <i class="bi bi-plus-circle"></i> Report Item
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($currentPage == 'my-items') ? 'active' : ''; ?>" 
                           href="#myItems">
                            <i class="bi bi-box"></i> My Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($currentPage == 'my-claims') ? 'active' : ''; ?>" 
                           href="#myClaims">
                            <i class="bi bi-clipboard-check"></i> My Claims
                        </a>
                    </li>
                <?php elseif(isset($_SESSION['admin'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-shield-check"></i> Admin
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/admin/dashboard">Dashboard</a></li>
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/admin/items">Items Management</a></li>
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/admin/students">Students</a></li>
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/admin/claims">Claims</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/admin/export/items/csv">Export Data</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
            
            <ul class="navbar-nav ms-auto">
                <?php if(isset($_SESSION['student_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['student_name']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/student/profile">
                                <i class="bi bi-person"></i> Profile
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/change-password">
                                <i class="bi bi-key"></i> Change Password
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?php echo APP_URL; ?>/logout">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a></li>
                        </ul>
                    </li>
                <?php elseif(isset($_SESSION['admin'])): ?>
                    <li class="nav-item">
                        <span class="nav-link text-success">
                            <i class="bi bi-shield-check"></i> Admin Mode
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo APP_URL; ?>/logout">
                            <i class="bi bi-box-arrow-right"></i> Logout
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