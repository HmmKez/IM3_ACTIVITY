<?php
require_once 'db_connect.php';
includeHeader('View Doctors');

// Handle delete request
if (isset($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM doctors WHERE doctor_id = ?");
        $stmt->execute([$_GET['delete']]);
        $success = "Doctor deleted successfully!";
    } catch(PDOException $e) {
        $error = "Error deleting doctor: " . $e->getMessage();
    }
}

// Fetch all doctors
try {
    $stmt = $pdo->query("SELECT * FROM doctors ORDER BY last_name ASC");
    $doctors = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Error fetching doctors: " . $e->getMessage();
}
?>

<h1 class="mb-4">Manage Doctors</h1>

<?php if (isset($success)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $success; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Doctors List</h5>
        <a href="add_doctor.php" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Add New Doctor
        </a>
    </div>

    <div class="card-body">
        <?php if (empty($doctors)): ?>
            <p class="text-muted mb-0">No doctors found. Click "Add New Doctor" to create one.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Specialization</th>
                            <th>Contact Number</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($doctors as $doctor): ?>
                        <tr>
                            <td><?php echo $doctor['doctor_id']; ?></td>
                            <td><?php echo htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($doctor['specialization'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($doctor['contact_number'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($doctor['email'] ?? 'N/A'); ?></td>
                            <td class="action-buttons">
                                <a href="edit_doctor.php?id=<?php echo $doctor['doctor_id']; ?>" 
                                   class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="?delete=<?php echo $doctor['doctor_id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this doctor? This will affect related appointments.')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php includeFooter(); ?>