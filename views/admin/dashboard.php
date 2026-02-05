<?php
$pageTitle = "Admin Dashboard";
$currentPage = "dashboard";
require_once 'views/templates/header.php';

// Get data from controller
$stats = $stats ?? [];
$recentItems = $recentItems ?? [];
$recentClaims = $recentClaims ?? [];
$pendingClaims = $pendingClaims ?? [];
?>

<div class="container-fluid">
    <!-- Dashboard Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Admin Dashboard</h2>
                    <p class="text-muted mb-0">Monitor lost & found activities and manage system operations</p>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-plus-circle"></i> Quick Add
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/admin/items/add">
                            <i class="bi bi-box"></i> Add Item
                        </a></li>
                        <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/admin/students/add">
                            <i class="bi bi-person-plus"></i> Add Student
                        </a></li>
                        <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/admin/claims/add">
                            <i class="bi bi-clipboard-plus"></i> Add Claim
                        </a></li>
                    </ul>
                    <a href="<?php echo APP_URL; ?>/admin/export/items/csv" class="btn btn-success">
                        <i class="bi bi-download"></i> Export
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted fw-normal">Total Items</h6>
                            <h2 class="mb-0"><?php echo $stats['total_items'] ?? 0; ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-box-seam text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted fw-normal">Lost Items</h6>
                            <h2 class="mb-0"><?php echo $stats['lost_items'] ?? 0; ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-search text-warning" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted fw-normal">Found Items</h6>
                            <h2 class="mb-0"><?php echo $stats['found_items'] ?? 0; ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-eye text-info" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted fw-normal">Claimed Items</h6>
                            <h2 class="mb-0"><?php echo $stats['claimed_items'] ?? 0; ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-check-circle text-success" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Items Report Timeline</h5>
                </div>
                <div class="card-body">
                    <canvas id="timelineChart" height="250"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Status Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activities -->
    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Items</h5>
                    <a href="<?php echo APP_URL; ?>/admin/items" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($recentItems as $item): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if($item['image_filename']): ?>
                                                <img src="<?php echo APP_URL; ?>/uploads/items/<?php echo $item['image_filename']; ?>" 
                                                     class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                            <?php endif; ?>
                                            <div>
                                                <strong><?php echo htmlspecialchars($item['name']); ?></strong><br>
                                                <small class="text-muted"><?php echo htmlspecialchars($item['reporter_first_name'] ?? '') . ' ' . htmlspecialchars($item['reporter_last_name'] ?? ''); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($item['category'] ?? 'N/A'); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower($item['status']); ?>">
                                            <?php echo $item['status']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d', strtotime($item['timestamp'])); ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="#" class="btn btn-outline-info" data-bs-toggle="modal" 
                                               data-bs-target="#viewItemModal<?php echo $item['id']; ?>">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="<?php echo APP_URL; ?>/admin/items/edit/<?php echo $item['id']; ?>" 
                                               class="btn btn-outline-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-hourglass-split"></i> Pending Claims (<?php echo count($pendingClaims); ?>)</h5>
                    <a href="<?php echo APP_URL; ?>/admin/claims" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php if(empty($pendingClaims)): ?>
                            <div class="list-group-item text-center text-muted py-4">
                                <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                                <p class="mt-2 mb-0">No pending claims</p>
                            </div>
                        <?php else: ?>
                            <?php foreach($pendingClaims as $claim): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1"><?php echo htmlspecialchars($claim['item_name'] ?? 'Unknown Item'); ?></h6>
                                        <p class="mb-1">
                                            Claimant: <?php echo htmlspecialchars($claim['claimer_first_name'] ?? '') . ' ' . htmlspecialchars($claim['claimer_last_name'] ?? ''); ?>
                                            <br>
                                            <small class="text-muted">ID: <?php echo htmlspecialchars($claim['student_id'] ?? 'N/A'); ?></small>
                                        </p>
                                    </div>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-success" 
                                                onclick="approveClaim(<?php echo $claim['id']; ?>)">
                                            <i class="bi bi-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger" 
                                                onclick="rejectClaim(<?php echo $claim['id']; ?>)">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted">Submitted: <?php echo date('M d, g:i a', strtotime($claim['created_at'])); ?></small>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="<?php echo APP_URL; ?>/admin/items/add" class="btn btn-outline-primary w-100">
                                <i class="bi bi-plus-circle"></i> Add Item
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?php echo APP_URL; ?>/admin/students/add" class="btn btn-outline-success w-100">
                                <i class="bi bi-person-plus"></i> Add Student
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?php echo APP_URL; ?>/admin/claims" class="btn btn-outline-warning w-100">
                                <i class="bi bi-clipboard-check"></i> Manage Claims
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?php echo APP_URL; ?>/qr" target="_blank" class="btn btn-outline-info w-100">
                                <i class="bi bi-qr-code"></i> Generate QR
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Scripts -->
<script>
// Fetch chart data via AJAX
fetch('<?php echo APP_URL; ?>/admin/chart-data')
    .then(response => response.json())
    .then(data => {
        // Status Distribution Pie Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'pie',
            data: {
                labels: Object.keys(data.status),
                datasets: [{
                    data: Object.values(data.status),
                    backgroundColor: [
                        '#ffc107', // Lost
                        '#17a2b8', // Found
                        '#28a745', // Claimed
                        '#6c757d', // Pending
                        '#dc3545'  // Rejected
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
        
        // Timeline Line Chart
        const timelineCtx = document.getElementById('timelineChart').getContext('2d');
        new Chart(timelineCtx, {
            type: 'line',
            data: {
                labels: data.timeline.daily.labels,
                datasets: [{
                    label: 'Items Reported',
                    data: data.timeline.daily.values,
                    borderColor: '#3498db',
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    })
    .catch(error => console.error('Error loading chart data:', error));

// Claim approval functions
function approveClaim(claimId) {
    if (confirm('Approve this claim?')) {
        window.location.href = '<?php echo APP_URL; ?>/admin/claims/approve/' + claimId;
    }
}

function rejectClaim(claimId) {
    if (confirm('Reject this claim?')) {
        window.location.href = '<?php echo APP_URL; ?>/admin/claims/reject/' + claimId;
    }
}
</script>

<?php require_once 'views/templates/footer.php'; ?>