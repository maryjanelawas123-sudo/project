<?php
$pageTitle = "Edit Item";
$currentPage = "items";
require_once 'views/templates/header.php';

$item = $item ?? [];
$students = $students ?? [];
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <h4 class="mb-0"><i class="bi bi-pencil"></i> Edit Item</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo APP_URL; ?>/admin/items/edit/<?php echo $item['id']; ?>" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Item Name *</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?php echo htmlspecialchars($item['name'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="Lost" <?php echo ($item['status'] ?? '') == 'Lost' ? 'selected' : ''; ?>>Lost</option>
                                    <option value="Found" <?php echo ($item['status'] ?? '') == 'Found' ? 'selected' : ''; ?>>Found</option>
                                    <option value="Claimed" <?php echo ($item['status'] ?? '') == 'Claimed' ? 'selected' : ''; ?>>Claimed</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($item['description'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="">Select Category</option>
                                    <option value="Electronics" <?php echo ($item['category'] ?? '') == 'Electronics' ? 'selected' : ''; ?>>Electronics</option>
                                    <option value="Books" <?php echo ($item['category'] ?? '') == 'Books' ? 'selected' : ''; ?>>Books & Notes</option>
                                    <option value="Clothing" <?php echo ($item['category'] ?? '') == 'Clothing' ? 'selected' : ''; ?>>Clothing</option>
                                    <option value="Accessories" <?php echo ($item['category'] ?? '') == 'Accessories' ? 'selected' : ''; ?>>Accessories</option>
                                    <option value="ID/Cards" <?php echo ($item['category'] ?? '') == 'ID/Cards' ? 'selected' : ''; ?>>ID & Cards</option>
                                    <option value="Keys" <?php echo ($item['category'] ?? '') == 'Keys' ? 'selected' : ''; ?>>Keys</option>
                                    <option value="Others" <?php echo ($item['category'] == 'Others' || !in_array($item['category'] ?? '', ['Electronics', 'Books', 'Clothing', 'Accessories', 'ID/Cards', 'Keys'])) ? 'selected' : ''; ?>>Others</option>
                                </select>
                                <div class="mt-2" id="otherCategoryContainer" style="display: <?php echo ($item['category'] == 'Others' || !in_array($item['category'] ?? '', ['Electronics', 'Books', 'Clothing', 'Accessories', 'ID/Cards', 'Keys'])) ? 'block' : 'none'; ?>;">
                                    <input type="text" class="form-control" id="other_category" name="other_category" 
                                           value="<?php echo htmlspecialchars($item['other_category'] ?? ''); ?>"
                                           placeholder="Specify category">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Location *</label>
                                <select class="form-select" id="location" name="location" required>
                                    <option value="">Select Location</option>
                                    <option value="Classroom" <?php echo ($item['location'] ?? '') == 'Classroom' ? 'selected' : ''; ?>>Classroom</option>
                                    <option value="Library" <?php echo ($item['location'] ?? '') == 'Library' ? 'selected' : ''; ?>>Library</option>
                                    <option value="Cafeteria" <?php echo ($item['location'] ?? '') == 'Cafeteria' ? 'selected' : ''; ?>>Cafeteria</option>
                                    <option value="Lab" <?php echo ($item['location'] ?? '') == 'Lab' ? 'selected' : ''; ?>>Computer Lab</option>
                                    <option value="Gym" <?php echo ($item['location'] ?? '') == 'Gym' ? 'selected' : ''; ?>>Gym</option>
                                    <option value="Admin Office" <?php echo ($item['location'] ?? '') == 'Admin Office' ? 'selected' : ''; ?>>Admin Office</option>
                                    <option value="Others" <?php echo ($item['location'] == 'Others' || !in_array($item['location'] ?? '', ['Classroom', 'Library', 'Cafeteria', 'Lab', 'Gym', 'Admin Office'])) ? 'selected' : ''; ?>>Others</option>
                                </select>
                                <div class="mt-2" id="otherLocationContainer" style="display: <?php echo ($item['location'] == 'Others' || !in_array($item['location'] ?? '', ['Classroom', 'Library', 'Cafeteria', 'Lab', 'Gym', 'Admin Office'])) ? 'block' : 'none'; ?>;">
                                    <input type="text" class="form-control" id="other_location" name="other_location" 
                                           value="<?php echo htmlspecialchars($item['other_location'] ?? ''); ?>"
                                           placeholder="Specify location">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row" id="classroomFields" style="display: <?php echo ($item['location'] ?? '') == 'Classroom' ? 'flex' : 'none'; ?>;">
                            <div class="col-md-6 mb-3">
                                <label for="building" class="form-label">Building</label>
                                <input type="text" class="form-control" id="building" name="building" 
                                       value="<?php echo htmlspecialchars($item['building'] ?? ''); ?>"
                                       placeholder="e.g., Main Building">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="classroom" class="form-label">Room Number</label>
                                <input type="text" class="form-control" id="classroom" name="classroom" 
                                       value="<?php echo htmlspecialchars($item['classroom'] ?? ''); ?>"
                                       placeholder="e.g., Room 101">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="reporter_id" class="form-label">Reported By</label>
                                <select class="form-select" id="reporter_id" name="reporter_id">
                                    <option value="">Select Student</option>
                                    <?php foreach($students as $student): ?>
                                        <option value="<?php echo $student['id']; ?>"
                                            <?php echo ($item['reporter_id'] ?? '') == $student['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name'] . ' (' . $student['student_id'] . ')'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="image" class="form-label">Item Photo</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <div class="form-text">Leave empty to keep current image</div>
                                
                                <?php if($item['image_filename']): ?>
                                    <div class="mt-2">
                                        <p class="mb-1">Current Image:</p>
                                        <img src="<?php echo APP_URL; ?>/uploads/items/<?php echo $item['image_filename']; ?>" 
                                             class="img-thumbnail" style="max-width: 200px;">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image">
                                            <label class="form-check-label" for="remove_image">
                                                Remove current image
                                            </label>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <div id="imagePreview" class="mt-2"></div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?php echo APP_URL; ?>/admin/items" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <div>
                                <a href="<?php echo APP_URL; ?>/admin/items/delete/<?php echo $item['id']; ?>" 
                                   class="btn btn-danger confirm-delete">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-check-circle"></i> Update Item
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-clock-history"></i> Item History</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li><strong>Created:</strong> <?php echo date('F j, Y, g:i a', strtotime($item['timestamp'])); ?></li>
                        <?php if($item['reporter_first_name']): ?>
                            <li><strong>Reported by:</strong> <?php echo htmlspecialchars($item['reporter_first_name'] . ' ' . $item['reporter_last_name']); ?></li>
                        <?php endif; ?>
                        <li><strong>Last Updated:</strong> Just now</li>
                    </ul>
                </div>
            </div>
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
            preview.innerHTML = `<p class="mb-1">New Image Preview:</p>
                                 <img src="${e.target.result}" class="img-thumbnail" style="max-width: 200px;">`;
        }
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
});
</script>

<?php require_once 'views/templates/footer.php'; ?>