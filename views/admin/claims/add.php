<?php
$pageTitle = "Add Claim";
$currentPage = "claims";
require_once 'views/templates/header.php';

$items = $items ?? [];
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Add New Claim</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo APP_URL; ?>/admin/claims/add">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="item_id" class="form-label">Item *</label>
                                <select class="form-select" id="item_id" name="item_id" required>
                                    <option value="">Select Item</option>
                                    <?php foreach($items as $item): ?>
                                        <option value="<?php echo $item['id']; ?>">
                                            #<?php echo $item['id']; ?> - <?php echo htmlspecialchars($item['name']); ?> 
                                            (<?php echo $item['status']; ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Claim Status *</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="Pending">Pending</option>
                                    <option value="Approved">Approved</option>
                                    <option value="Rejected">Rejected</option>
                                </select>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        <h5 class="mb-3 text-primary">Claimant Information</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="student_id" class="form-label">Student ID</label>
                                <input type="text" class="form-control" id="student_id" name="student_id" 
                                       placeholder="If existing student">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="claimer_name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="claimer_name" name="claimer_name" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contact" class="form-label">Contact Number</label>
                                <input type="tel" class="form-control" id="contact" name="contact">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="course" class="form-label">Course</label>
                                <input type="text" class="form-control" id="course" name="course">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="verification_method" class="form-label">Verification Method</label>
                                <select class="form-select" id="verification_method" name="verification_method">
                                    <option value="">Select Method</option>
                                    <option value="ID Verification">ID Verification</option>
                                    <option value="Item Description">Item Description</option>
                                    <option value="Witness Testimony">Witness Testimony</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="admin_notes" class="form-label">Admin Notes</label>
                            <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3"></textarea>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?php echo APP_URL; ?>/admin/claims" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Add Claim
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/templates/footer.php'; ?>