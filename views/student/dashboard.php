<?php
$pageTitle = "Student Dashboard";
$currentPage = "student-dashboard";
require_once 'views/templates/header.php';

// Get student data from controller
$student = $student ?? null;
$items = $items ?? [];
$reportedItems = $reportedItems ?? [];
$claims = $claims ?? [];
?>

<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2>Welcome, <?php echo htmlspecialchars($student['first_name'] ?? 'Student'); ?>!</h2>
                            <p class="mb-0">Track lost items, report found items, and manage your claims here.</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-light btn-lg" data-bs-toggle="modal" data-bs-target="#reportModal">
                                <i class="bi bi-plus-circle"></i> Report Item
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total Items</h6>
                            <h2 class="mb-0"><?php echo count($items); ?></h2>
                        </div>
                        <i class="bi bi-box-seam" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">My Reports</h6>
                            <h2 class="mb-0"><?php echo count($reportedItems); ?></h2>
                        </div>
                        <i class="bi bi-clipboard-check" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">My Claims</h6>
                            <h2 class="mb-0"><?php echo count($claims); ?></h2>
                        </div>
                        <i class="bi bi-hand-thumbs-up" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Pending</h6>
                            <h2 class="mb-0">
                                <?php 
                                    $pending = 0;
                                    foreach($claims as $claim) {
                                        if($claim['status'] == 'Pending') $pending++;
                                    }
                                    echo $pending;
                                ?>
                            </h2>
                        </div>
                        <i class="bi bi-clock-history" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recently Found/Lost Items -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-search"></i> Recently Reported Items</h5>
                </div>
                <div class="card-body">
                    <?php if(empty($items)): ?>
                        <div class="alert alert-info">No items found at the moment.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover data-table">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Item Name</th>
                                        <th>Category</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Date Reported</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($items as $item): ?>
                                    <tr>
                                        <td>
                                            <?php if($item['image_filename']): ?>
                                                <img src="<?php echo APP_URL; ?>/uploads/items/<?php echo $item['image_filename']; ?>" 
                                                     class="item-image" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                            <?php else: ?>
                                                <div class="text-center">
                                                    <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($item['name']); ?></strong><br>
                                            <small class="text-muted"><?php echo substr($item['description'] ?? 'No description', 0, 50); ?>...</small>
                                        </td>
                                        <td><?php echo htmlspecialchars($item['category'] ?? 'N/A'); ?></td>
                                        <td>
                                            <?php echo htmlspecialchars($item['location'] ?? 'N/A'); ?>
                                            <?php if($item['building']): ?>
                                                <br><small><?php echo $item['building']; ?> - <?php echo $item['classroom']; ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="status-badge status-<?php echo strtolower($item['status']); ?>">
                                                <?php echo $item['status']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($item['timestamp'])); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" 
                                                    data-bs-target="#itemModal<?php echo $item['id']; ?>">
                                                <i class="bi bi-eye"></i> View
                                            </button>
                                            <?php if($item['status'] != 'Claimed'): ?>
                                                <a href="<?php echo APP_URL; ?>/student/claim/<?php echo $item['id']; ?>" 
                                                   class="btn btn-sm btn-success">
                                                    <i class="bi bi-hand-thumbs-up"></i> Claim
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    
                                    <!-- Item Detail Modal -->
                                    <div class="modal fade" id="itemModal<?php echo $item['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Item Details</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <?php if($item['image_filename']): ?>
                                                        <div class="col-md-4">
                                                            <img src="<?php echo APP_URL; ?>/uploads/items/<?php echo $item['image_filename']; ?>" 
                                                                 class="img-fluid rounded" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                                        </div>
                                                        <?php endif; ?>
                                                        <div class="col-md-<?php echo $item['image_filename'] ? '8' : '12'; ?>">
                                                            <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                                            <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($item['description'] ?? 'No description')); ?></p>
                                                            <p><strong>Category:</strong> <?php echo htmlspecialchars($item['category'] ?? 'N/A'); ?></p>
                                                            <p><strong>Location:</strong> <?php echo htmlspecialchars($item['location'] ?? 'N/A'); ?></p>
                                                            <?php if($item['building']): ?>
                                                                <p><strong>Building:</strong> <?php echo $item['building']; ?> - Room <?php echo $item['classroom']; ?></p>
                                                            <?php endif; ?>
                                                            <p><strong>Status:</strong> 
                                                                <span class="status-badge status-<?php echo strtolower($item['status']); ?>">
                                                                    <?php echo $item['status']; ?>
                                                                </span>
                                                            </p>
                                                            <p><strong>Reported by:</strong> 
                                                                <?php echo htmlspecialchars($item['reporter_first_name'] ?? 'N/A') . ' ' . htmlspecialchars($item['reporter_last_name'] ?? ''); ?>
                                                            </p>
                                                            <p><strong>Date Reported:</strong> <?php echo date('F j, Y, g:i a', strtotime($item['timestamp'])); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <?php if($item['status'] != 'Claimed'): ?>
                                                        <a href="<?php echo APP_URL; ?>/student/claim/<?php echo $item['id']; ?>" 
                                                           class="btn btn-success">
                                                            <i class="bi bi-hand-thumbs-up"></i> Claim This Item
                                                        </a>
                                                    <?php endif; ?>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
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
    </div>
    
    <!-- My Reported Items & Claims -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-clipboard-check"></i> My Reported Items</h5>
                </div>
                <div class="card-body">
                    <?php if(empty($reportedItems)): ?>
                        <div class="alert alert-info">You haven't reported any items yet.</div>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach($reportedItems as $item): ?>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($item['name']); ?></h6>
                                    <span class="status-badge status-<?php echo strtolower($item['status']); ?>">
                                        <?php echo $item['status']; ?>
                                    </span>
                                </div>
                                <p class="mb-1"><?php echo substr($item['description'] ?? 'No description', 0, 100); ?>...</p>
                                <small>Reported on <?php echo date('M d, Y', strtotime($item['timestamp'])); ?></small>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-hand-thumbs-up"></i> My Claims</h5>
                </div>
                <div class="card-body">
                    <?php if(empty($claims)): ?>
                        <div class="alert alert-info">You haven't made any claims yet.</div>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach($claims as $claim): ?>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Claim #<?php echo $claim['id']; ?></h6>
                                    <span class="status-badge status-<?php echo strtolower($claim['status']); ?>">
                                        <?php echo $claim['status']; ?>
                                    </span>
                                </div>
                                <p class="mb-1">Item: <?php echo htmlspecialchars($claim['item_name'] ?? 'Unknown'); ?></p>
                                <small>Claimed on <?php echo date('M d, Y', strtotime($claim['created_at'])); ?></small>
                                <?php if($claim['admin_notes']): ?>
                                    <div class="alert alert-light mt-2 mb-0 p-2">
                                        <small><strong>Admin Note:</strong> <?php echo htmlspecialchars($claim['admin_notes']); ?></small>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Report Item Modal -->
<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle"></i> Report Lost/Found Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo APP_URL; ?>/student/report-item" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Item Name *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="Lost">I Lost This Item</option>
                                <option value="Found">I Found This Item</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category">
                                <option value="">Select Category</option>
                                <option value="Electronics">Electronics</option>
                                <option value="Books">Books & Notes</option>
                                <option value="Clothing">Clothing</option>
                                <option value="Accessories">Accessories</option>
                                <option value="ID/Cards">ID & Cards</option>
                                <option value="Keys">Keys</option>
                                <option value="Others">Others</option>
                            </select>
                            <div class="mt-2" id="otherCategoryContainer" style="display: none;">
                                <input type="text" class="form-control" id="other_category" name="other_category" 
                                       placeholder="Specify category">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="location" class="form-label">Location Found/Lost</label>
                            <select class="form-select" id="location" name="location">
                                <option value="">Select Location</option>
                                <option value="Classroom">Classroom</option>
                                <option value="Library">Library</option>
                                <option value="Cafeteria">Cafeteria</option>
                                <option value="Lab">Computer Lab</option>
                                <option value="Gym">Gym</option>
                                <option value="Others">Others</option>
                            </select>
                            <div class="mt-2" id="otherLocationContainer" style="display: none;">
                                <input type="text" class="form-control" id="other_location" name="other_location" 
                                       placeholder="Specify location">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row" id="classroomFields" style="display: none;">
                        <div class="col-md-6 mb-3">
                            <label for="building" class="form-label">Building</label>
                            <input type="text" class="form-control" id="building" name="building" 
                                   placeholder="e.g., Main Building">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="room" class="form-label">Room Number</label>
                            <input type="text" class="form-control" id="room" name="room" 
                                   placeholder="e.g., Room 101">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">Item Photo</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <div class="form-text">Upload a clear photo of the item (max 10MB)</div>
                        <div id="imagePreview" class="mt-2"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Report</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Show/hide other category input
document.getElementById('category').addEventListener('change', function() {
    const otherContainer = document.getElementById('otherCategoryContainer');
    otherContainer.style.display = (this.value === 'Others') ? 'block' : 'none';
});

// Show/hide other location input and classroom fields
document.getElementById('location').addEventListener('change', function() {
    const otherContainer = document.getElementById('otherLocationContainer');
    const classroomFields = document.getElementById('classroomFields');
    
    otherContainer.style.display = (this.value === 'Others') ? 'block' : 'none';
    classroomFields.style.display = (this.value === 'Classroom') ? 'flex' : 'none';
});

// Image preview
document.getElementById('image').addEventListener('change', function() {
    const preview = document.getElementById('imagePreview');
    const file = this.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="img-thumbnail" style="max-width: 200px;">`;
        }
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
});
</script>

<?php require_once 'views/templates/footer.php'; ?>