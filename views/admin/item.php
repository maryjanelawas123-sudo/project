<?php
$pageTitle = "Items Management";
$currentPage = "items";
require_once 'views/templates/header.php';

$items = $items ?? [];
$archivedItems = $archivedItems ?? [];
$categories = $categories ?? [];
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';
$category = $_GET['category'] ?? '';
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Items Management</h2>
            <p class="text-muted mb-0">Manage lost, found, and claimed items</p>
        </div>
        <div>
            <a href="<?php echo APP_URL; ?>/admin/items/add" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Item
            </a>
            <a href="<?php echo APP_URL; ?>/admin/export/items/csv" class="btn btn-success">
                <i class="bi bi-download"></i> Export
            </a>
        </div>
    </div>
    
    <!-- Search and Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo APP_URL; ?>/admin/items" class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" placeholder="Search items..." 
                           value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="Lost" <?php echo $status == 'Lost' ? 'selected' : ''; ?>>Lost</option>
                        <option value="Found" <?php echo $status == 'Found' ? 'selected' : ''; ?>>Found</option>
                        <option value="Claimed" <?php echo $status == 'Claimed' ? 'selected' : ''; ?>>Claimed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="category">
                        <option value="">All Categories</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat); ?>" 
                                <?php echo $category == $cat ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Active Items -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-box-seam"></i> Active Items (<?php echo count($items); ?>)</h5>
            <div class="btn-group">
                <button class="btn btn-sm btn-outline-secondary" onclick="selectAll()">Select All</button>
                <button class="btn btn-sm btn-outline-warning" onclick="archiveSelected()">Archive Selected</button>
            </div>
        </div>
        <div class="card-body p-0">
            <?php if(empty($items)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem; color: #6c757d;"></i>
                    <h5 class="mt-3">No items found</h5>
                    <p class="text-muted">Try adjusting your search or add a new item.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover data-table">
                        <thead class="table-light">
                            <tr>
                                <th width="30">
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>Item</th>
                                <th>Category</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Reporter</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($items as $item): ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="item-checkbox" value="<?php echo $item['id']; ?>">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if($item['image_filename']): ?>
                                            <img src="<?php echo APP_URL; ?>/uploads/items/<?php echo $item['image_filename']; ?>" 
                                                 class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                        <?php endif; ?>
                                        <div>
                                            <strong><?php echo htmlspecialchars($item['name']); ?></strong><br>
                                            <small class="text-muted"><?php echo substr($item['description'] ?? '', 0, 50); ?>...</small>
                                        </div>
                                    </div>
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
                                <td>
                                    <?php if($item['reporter_first_name']): ?>
                                        <?php echo htmlspecialchars($item['reporter_first_name'] . ' ' . $item['reporter_last_name']); ?><br>
                                        <small class="text-muted">ID: <?php echo htmlspecialchars($item['reporter_student_id'] ?? 'N/A'); ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($item['timestamp'])); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" 
                                                data-bs-target="#viewItemModal<?php echo $item['id']; ?>">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <a href="<?php echo APP_URL; ?>/admin/items/edit/<?php echo $item['id']; ?>" 
                                           class="btn btn-outline-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?php echo APP_URL; ?>/admin/items/archive/<?php echo $item['id']; ?>" 
                                           class="btn btn-outline-secondary" 
                                           onclick="return confirm('Archive this item?')">
                                            <i class="bi bi-archive"></i>
                                        </a>
                                        <a href="<?php echo APP_URL; ?>/admin/items/delete/<?php echo $item['id']; ?>" 
                                           class="btn btn-outline-danger confirm-delete">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- View Item Modal -->
                            <div class="modal fade" id="viewItemModal<?php echo $item['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Item Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <?php if($item['image_filename']): ?>
                                                <div class="col-md-5">
                                                    <img src="<?php echo APP_URL; ?>/uploads/items/<?php echo $item['image_filename']; ?>" 
                                                         class="img-fluid rounded" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                                </div>
                                                <?php endif; ?>
                                                <div class="col-md-<?php echo $item['image_filename'] ? '7' : '12'; ?>">
                                                    <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                                    <p><strong>Description:</strong><br>
                                                    <?php echo nl2br(htmlspecialchars($item['description'] ?? 'No description')); ?></p>
                                                    
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p><strong>Category:</strong> <?php echo htmlspecialchars($item['category'] ?? 'N/A'); ?></p>
                                                            <p><strong>Location:</strong> <?php echo htmlspecialchars($item['location'] ?? 'N/A'); ?></p>
                                                            <?php if($item['building']): ?>
                                                                <p><strong>Building:</strong> <?php echo $item['building']; ?></p>
                                                                <p><strong>Room:</strong> <?php echo $item['classroom']; ?></p>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p><strong>Status:</strong> 
                                                                <span class="status-badge status-<?php echo strtolower($item['status']); ?>">
                                                                    <?php echo $item['status']; ?>
                                                                </span>
                                                            </p>
                                                            <p><strong>Reported by:</strong><br>
                                                            <?php echo htmlspecialchars($item['reporter_first_name'] ?? '') . ' ' . htmlspecialchars($item['reporter_last_name'] ?? ''); ?>
                                                            <br>
                                                            <small>ID: <?php echo htmlspecialchars($item['reporter_student_id'] ?? 'N/A'); ?></small></p>
                                                            <p><strong>Date Reported:</strong><br>
                                                            <?php echo date('F j, Y, g:i a', strtotime($item['timestamp'])); ?></p>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Other Information -->
                                                    <?php if($item['other_location'] || $item['other_category']): ?>
                                                        <div class="alert alert-light mt-3">
                                                            <h6>Additional Information:</h6>
                                                            <?php if($item['other_location']): ?>
                                                                <p><strong>Other Location:</strong> <?php echo htmlspecialchars($item['other_location']); ?></p>
                                                            <?php endif; ?>
                                                            <?php if($item['other_category']): ?>
                                                                <p><strong>Other Category:</strong> <?php echo htmlspecialchars($item['other_category']); ?></p>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <a href="<?php echo APP_URL; ?>/admin/items/edit/<?php echo $item['id']; ?>" 
                                               class="btn btn-warning">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
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
    
    <!-- Archived Items (Collapsible) -->
    <?php if(!empty($archivedItems)): ?>
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">
                <a class="text-decoration-none" data-bs-toggle="collapse" href="#archivedItems">
                    <i class="bi bi-archive"></i> Archived Items (<?php echo count($archivedItems); ?>)
                    <i class="bi bi-chevron-down float-end"></i>
                </a>
            </h5>
        </div>
        <div class="collapse" id="archivedItems">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-secondary">
                            <tr>
                                <th>Item</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Date Archived</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($archivedItems as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if($item['image_filename']): ?>
                                            <img src="<?php echo APP_URL; ?>/uploads/items/<?php echo $item['image_filename']; ?>" 
                                                 class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        <?php endif; ?>
                                        <div>
                                            <strong><?php echo htmlspecialchars($item['name']); ?></strong><br>
                                            <small class="text-muted"><?php echo substr($item['description'] ?? '', 0, 50); ?>...</small>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($item['category'] ?? 'N/A'); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo strtolower($item['status']); ?>">
                                        <?php echo $item['status']; ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($item['timestamp'])); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo APP_URL; ?>/admin/items/restore/<?php echo $item['id']; ?>" 
                                           class="btn btn-outline-success" 
                                           onclick="return confirm('Restore this item?')">
                                            <i class="bi bi-arrow-counterclockwise"></i> Restore
                                        </a>
                                        <a href="<?php echo APP_URL; ?>/admin/items/delete/<?php echo $item['id']; ?>" 
                                           class="btn btn-outline-danger confirm-delete">
                                            <i class="bi bi-trash"></i> Delete
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
    <?php endif; ?>
</div>

<script>
// Select/Deselect All
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
});

function selectAll() {
    document.getElementById('selectAll').checked = true;
    const checkboxes = document.querySelectorAll('.item-checkbox');
    checkboxes.forEach(checkbox => checkbox.checked = true);
}

function archiveSelected() {
    const selected = Array.from(document.querySelectorAll('.item-checkbox:checked'))
                         .map(cb => cb.value);
    
    if (selected.length === 0) {
        alert('Please select items to archive.');
        return;
    }
    
    if (confirm(`Archive ${selected.length} selected item(s)?`)) {
        selected.forEach(id => {
            // You'll need to implement batch archiving via AJAX
            console.log('Archive item:', id);
        });
        alert('Batch archiving feature coming soon.');
    }
}
</script>

<?php require_once 'views/templates/footer.php'; ?>