<?php
$pageTitle = "Students Management";
$currentPage = "students";
require_once 'views/templates/header.php';

$students = $students ?? [];
$search = $_GET['search'] ?? '';
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Students Management</h2>
            <p class="text-muted mb-0">Manage student accounts and information</p>
        </div>
        <div>
            <a href="<?php echo APP_URL; ?>/admin/students/add" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Add Student
            </a>
            <a href="<?php echo APP_URL; ?>/admin/export/students/csv" class="btn btn-success">
                <i class="bi bi-download"></i> Export
            </a>
        </div>
    </div>
    
    <!-- Search -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo APP_URL; ?>/admin/students" class="row g-3">
                <div class="col-md-10">
                    <input type="text" class="form-control" name="search" placeholder="Search by name, student ID, or username..." 
                           value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Students Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-people"></i> Students (<?php echo count($students); ?>)</h5>
        </div>
        <div class="card-body p-0">
            <?php if(empty($students)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-people" style="font-size: 3rem; color: #6c757d;"></i>
                    <h5 class="mt-3">No students found</h5>
                    <p class="text-muted">Try adjusting your search or add a new student.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover data-table">
                        <thead class="table-light">
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Contact</th>
                                <th>Username</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($students as $student): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($student['student_id']); ?></strong>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>
                                    <?php if($student['middle_name']): ?>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($student['middle_name']); ?></small>
                                    <?php endif; ?>
                                    <?php if($student['suffix']): ?>
                                        <small class="text-muted">, <?php echo htmlspecialchars($student['suffix']); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($student['course'] ?? 'N/A'); ?>
                                    <?php if($student['year_section']): ?>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($student['year_section']); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($student['contact'] ?? 'N/A'); ?></td>
                                <td>
                                    <code><?php echo htmlspecialchars($student['username']); ?></code>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($student['created_at'])); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" 
                                                data-bs-target="#viewStudentModal<?php echo $student['id']; ?>">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <a href="<?php echo APP_URL; ?>/admin/students/edit/<?php echo $student['id']; ?>" 
                                           class="btn btn-outline-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?php echo APP_URL; ?>/admin/students/reset-password/<?php echo $student['id']; ?>" 
                                           class="btn btn-outline-secondary" 
                                           onclick="return confirm('Reset password for this student?')">
                                            <i class="bi bi-key"></i>
                                        </a>
                                        <a href="<?php echo APP_URL; ?>/admin/students/delete/<?php echo $student['id']; ?>" 
                                           class="btn btn-outline-danger confirm-delete">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- View Student Modal -->
                            <div class="modal fade" id="viewStudentModal<?php echo $student['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Student Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h4><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></h4>
                                                    <p><strong>Student ID:</strong> <?php echo htmlspecialchars($student['student_id']); ?></p>
                                                    
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p><strong>Full Name:</strong><br>
                                                            <?php echo htmlspecialchars($student['first_name']); ?>
                                                            <?php if($student['middle_name']): ?>
                                                                <?php echo htmlspecialchars($student['middle_name']); ?>
                                                            <?php endif; ?>
                                                            <?php echo htmlspecialchars($student['last_name']); ?>
                                                            <?php if($student['suffix']): ?>
                                                                , <?php echo htmlspecialchars($student['suffix']); ?>
                                                            <?php endif; ?>
                                                            </p>
                                                            <p><strong>Course:</strong> <?php echo htmlspecialchars($student['course'] ?? 'N/A'); ?></p>
                                                            <p><strong>Year & Section:</strong> <?php echo htmlspecialchars($student['year_section'] ?? 'N/A'); ?></p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p><strong>Contact:</strong> <?php echo htmlspecialchars($student['contact'] ?? 'N/A'); ?></p>
                                                            <p><strong>Username:</strong> <?php echo htmlspecialchars($student['username']); ?></p>
                                                            <p><strong>Registered:</strong> <?php echo date('F j, Y, g:i a', strtotime($student['created_at'])); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="text-center">
                                                        <div class="profile-pic bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                                                             style="width: 120px; height: 120px;">
                                                            <i class="bi bi-person" style="font-size: 3rem; color: white;"></i>
                                                        </div>
                                                        <p class="mt-2 mb-0">
                                                            <a href="<?php echo APP_URL; ?>/admin/students/reset-password/<?php echo $student['id']; ?>" 
                                                               class="btn btn-sm btn-warning mt-2">
                                                                <i class="bi bi-key"></i> Reset Password
                                                            </a>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Student Stats -->
                                            <div class="row mt-4">
                                                <div class="col-md-4">
                                                    <div class="card bg-light">
                                                        <div class="card-body text-center">
                                                            <h5 class="mb-0">0</h5>
                                                            <small>Items Reported</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="card bg-light">
                                                        <div class="card-body text-center">
                                                            <h5 class="mb-0">0</h5>
                                                            <small>Claims Made</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="card bg-light">
                                                        <div class="card-body text-center">
                                                            <h5 class="mb-0">0</h5>
                                                            <small>Successful Claims</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <a href="<?php echo APP_URL; ?>/admin/students/edit/<?php echo $student['id']; ?>" 
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
</div>

<?php require_once 'views/templates/footer.php'; ?>