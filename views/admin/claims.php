<?php
$pageTitle = "Claims Management";
$currentPage = "claims";
require_once 'views/templates/header.php';

$claims = $claims ?? [];
$status = $_GET['status'] ?? '';
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Claims Management</h2>
            <p class="text-muted mb-0">Manage item claims and verifications</p>
        </div>
        <div>
            <a href="<?php echo APP_URL; ?>/admin/claims/add" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add Claim
            </a>
            <a href="<?php echo APP_URL; ?>/admin/export/claims/csv" class="btn btn-success">
                <i class="bi bi-download"></i> Export
            </a>
        </div>
    </div>
    
    <!-- Status Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-auto">
                    <div class="btn-group" role="group">
                        <a href="<?php echo APP_URL; ?>/admin/claims" 
                           class="btn btn-outline-secondary <?php echo empty($status) ? 'active' : ''; ?>">
                            All Claims
                        </a>
                        <a href="<?php echo APP_URL; ?>/admin/claims?status=Pending" 
                           class="btn btn-outline-warning <?php echo $status == 'Pending' ? 'active' : ''; ?>">
                            Pending
                        </a>
                        <a href="<?php echo APP_URL; ?>/admin/claims?status=Approved" 
                           class="btn btn-outline-success <?php echo $status == 'Approved' ? 'active' : ''; ?>">
                            Approved
                        </a>
                        <a href="<?php echo APP_URL; ?>/admin/claims?status=Rejected" 
                           class="btn btn-outline-danger <?php echo $status == 'Rejected' ? 'active' : ''; ?>">
                            Rejected
                        </a>
                    </div>
                </div>
                <div class="col">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search claims..." id="searchInput">
                        <button class="btn btn-outline-secondary" type="button" onclick="searchClaims()">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Claims Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-clipboard-check"></i> Claims (<?php echo count($claims); ?>)</h5>
        </div>
        <div class="card-body p-0">
            <?php if(empty($claims)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-clipboard" style="font-size: 3rem; color: #6c757d;"></i>
                    <h5 class="mt-3">No claims found</h5>
                    <p class="text-muted">Try adjusting your filter or add a new claim.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover data-table">
                        <thead class="table-light">
                            <tr>
                                <th>Claim ID</th>
                                <th>Item Details</th>
                                <th>Claimant</th>
                                <th>Status</th>
                                <th>Date Claimed</th>
                                <th>Verification</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($claims as $claim): ?>
                            <tr>
                                <td>
                                    <strong>#<?php echo str_pad($claim['id'], 4, '0', STR_PAD_LEFT); ?></strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if($claim['image_filename']): ?>
                                            <img src="<?php echo APP_URL; ?>/uploads/items/<?php echo $claim['image_filename']; ?>" 
                                                 class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                        <?php endif; ?>
                                        <div>
                                            <strong><?php echo htmlspecialchars($claim['item_name']); ?></strong><br>
                                            <small class="text-muted">Item ID: <?php echo $claim['item_id']; ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($claim['claimer_first_name'] . ' ' . $claim['claimer_last_name']); ?></strong><br>
                                    <small class="text-muted">
                                        ID: <?php echo htmlspecialchars($claim['student_id'] ?? 'N/A'); ?><br>
                                        <?php echo htmlspecialchars($claim['contact'] ?? ''); ?><br>
                                        <?php echo htmlspecialchars($claim['course'] ?? ''); ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="status-badge status-<?php echo strtolower($claim['status']); ?>">
                                        <?php echo $claim['status']; ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($claim['created_at'])); ?></td>
                                <td>
                                    <?php if($claim['verification_method']): ?>
                                        <small><?php echo htmlspecialchars($claim['verification_method']); ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">Not specified</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" 
                                                data-bs-target="#viewClaimModal<?php echo $claim['id']; ?>">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" 
                                                data-bs-target="#editClaimModal<?php echo $claim['id']; ?>">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <?php if($claim['status'] == 'Pending'): ?>
                                            <button type="button" class="btn btn-outline-success" 
                                                    onclick="approveClaim(<?php echo $claim['id']; ?>)">
                                                <i class="bi bi-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" 
                                                    onclick="rejectClaim(<?php echo $claim['id']; ?>)">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        <?php endif; ?>
                                        <a href="<?php echo APP_URL; ?>/admin/claims/delete/<?php echo $claim['id']; ?>" 
                                           class="btn btn-outline-danger confirm-delete">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- View Claim Modal -->
                            <div class="modal fade" id="viewClaimModal<?php echo $claim['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Claim Details #<?php echo $claim['id']; ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>Item Information</h6>
                                                    <p><strong>Item:</strong> <?php echo htmlspecialchars($claim['item_name']); ?></p>
                                                    <p><strong>Item ID:</strong> <?php echo $claim['item_id']; ?></p>
                                                    <?php if($claim['image_filename']): ?>
                                                        <img src="<?php echo APP_URL; ?>/uploads/items/<?php echo $claim['image_filename']; ?>" 
                                                             class="img-fluid rounded mb-3" style="max-height: 200px;">
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Claimant Information</h6>
                                                    <p><strong>Name:</strong> <?php echo htmlspecialchars($claim['claimer_first_name'] . ' ' . $claim['claimer_last_name']); ?></p>
                                                    <p><strong>Student ID:</strong> <?php echo htmlspecialchars($claim['student_id'] ?? 'N/A'); ?></p>
                                                    <p><strong>Contact:</strong> <?php echo htmlspecialchars($claim['contact'] ?? 'N/A'); ?></p>
                                                    <p><strong>Course:</strong> <?php echo htmlspecialchars($claim['course'] ?? 'N/A'); ?></p>
                                                    <p><strong>Year/Section:</strong> <?php echo htmlspecialchars($claim['year_section'] ?? 'N/A'); ?></p>
                                                </div>
                                            </div>
                                            
                                            <hr>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>Claim Details</h6>
                                                    <p><strong>Status:</strong> 
                                                        <span class="status-badge status-<?php echo strtolower($claim['status']); ?>">
                                                            <?php echo $claim['status']; ?>
                                                        </span>
                                                    </p>
                                                    <p><strong>Date Claimed:</strong> <?php echo date('F j, Y, g:i a', strtotime($claim['created_at'])); ?></p>
                                                    <p><strong>Verification Method:</strong> <?php echo htmlspecialchars($claim['verification_method'] ?? 'Not specified'); ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Admin Notes</h6>
                                                    <div class="bg-light p-3 rounded">
                                                        <?php if($claim['admin_notes']): ?>
                                                            <?php echo nl2br(htmlspecialchars($claim['admin_notes'])); ?>
                                                        <?php else: ?>
                                                            <span class="text-muted">No notes added</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <?php if($claim['status'] == 'Pending'): ?>
                                                <button type="button" class="btn btn-success" 
                                                        onclick="approveClaim(<?php echo $claim['id']; ?>)">
                                                    <i class="bi bi-check"></i> Approve
                                                </button>
                                                <button type="button" class="btn btn-danger" 
                                                        onclick="rejectClaim(<?php echo $claim['id']; ?>)">
                                                    <i class="bi bi-x"></i> Reject
                                                </button>
                                            <?php endif; ?>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Edit Claim Modal -->
                            <div class="modal fade" id="editClaimModal<?php echo $claim['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Claim #<?php echo $claim['id']; ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="<?php echo APP_URL; ?>/admin/claims/update/<?php echo $claim['id']; ?>">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="status" class="form-label">Status</label>
                                                    <select class="form-select" id="status" name="status" required>
                                                        <option value="Pending" <?php echo $claim['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="Approved" <?php echo $claim['status'] == 'Approved' ? 'selected' : ''; ?>>Approved</option>
                                                        <option value="Rejected" <?php echo $claim['status'] == 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="verification_method" class="form-label">Verification Method</label>
                                                    <select class="form-select" id="verification_method" name="verification_method">
                                                        <option value="">Select Method</option>
                                                        <option value="ID Verification" <?php echo $claim['verification_method'] == 'ID Verification' ? 'selected' : ''; ?>>ID Verification</option>
                                                        <option value="Item Description" <?php echo $claim['verification_method'] == 'Item Description' ? 'selected' : ''; ?>>Item Description</option>
                                                        <option value="Witness Testimony" <?php echo $claim['verification_method'] == 'Witness Testimony' ? 'selected' : ''; ?>>Witness Testimony</option>
                                                        <option value="Other" <?php echo $claim['verification_method'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="admin_notes" class="form-label">Admin Notes</label>
                                                    <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3"><?php echo htmlspecialchars($claim['admin_notes'] ?? ''); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-warning">Update Claim</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function searchClaims() {
    const searchTerm = document.getElementById('searchInput').value;
    if (searchTerm) {
        window.location.href = '<?php echo APP_URL; ?>/admin/claims?search=' + encodeURIComponent(searchTerm);
    }
}

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