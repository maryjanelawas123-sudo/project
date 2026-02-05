<?php
$pageTitle = "Add New Item";
$currentPage = "items";
require_once 'views/templates/header.php';

$students = $students ?? [];
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Add New Item</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo APP_URL; ?>/admin/items/add" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Item Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="Lost">Lost</option>
                                    <option value="Found">Found</option>
                                    <option value="Claimed">Claimed</option>
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
                                <label for="location" class="form-label">Location *</label>
                                <select class="form-select" id="location" name="location" required>
                                    <option value="">Select Location</option>
                                    <option value="Classroom">Classroom</option>
                                    <option value="Library">Library</option>
                                    <option value="Cafeteria">Cafeteria</option>
                                    <option value="Lab">Computer Lab</option>
                                    <option value="Gym">Gym</option>
                                    <option value="Admin Office">Admin Office</option>
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
                                <label for="classroom" class="form-label">Room Number</label>
                                <input type="text" class="form-control" id="classroom" name="classroom" 
                                       placeholder="e.g., Room 101">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="reporter_id" class="form-label">Reported By (Optional)</label>
                                <select class="form-select" id="reporter_id" name="reporter_id">
                                    <option value="">Select Student</option>
                                    <?php foreach($students as $student): ?>
                                        <option value="<?php echo $student['id']; ?>">
                                            <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name'] . ' (' . $student['student_id'] . ')'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="image" class="form-label">Item Photo</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <div class="form-text">Upload a clear photo (max 10MB)</div>
                                <div id="imagePreview" class="mt-2"></div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?php echo APP_URL; ?>/admin/items" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Add Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Guidelines for Adding Items</h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>Provide a clear and descriptive item name</li>
                        <li>Select the appropriate status (Lost, Found, or Claimed)</li>
                        <li>Include as much detail as possible in the description</li>
                        <li>Upload a photo if available for better identification</li>
                        <li>If location is "Classroom", specify building and room number</li>
                        <li>For "Others" category/location, specify in the provided field</li>
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
            preview.innerHTML = `<img src="${e.target.result}" class="img-thumbnail" style="max-width: 200px;">`;
        }
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
});
</script>

<?php require_once 'views/templates/footer.php'; ?>