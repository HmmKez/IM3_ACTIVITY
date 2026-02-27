<?php
require_once 'db_connect.php';
includeHeader('View Patients');

// Handle delete request
if (isset($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM patients WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $success = "Patient deleted successfully!";
    } catch(PDOException $e) {
        $error = "Error deleting patient: " . $e->getMessage();
    }
}

// Fetch all patients
try {
    $stmt = $pdo->query("SELECT * FROM patients ORDER BY name ASC");
    $patients = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Error fetching patients: " . $e->getMessage();
}
?>

<h1 class="mb-4">Manage Patients</h1>

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
        <h5 class="mb-0">Patients List</h5>
        <a href="add_patient.php" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Add New Patient
        </a>
    </div>
    <div class="card-body">
        <?php if (empty($patients)): ?>
            <p class="text-muted mb-0">No patients found. Click "Add New Patient" to create one.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($patients as $patient): ?>
                        <tr>
                            <td><?php echo $patient['id']; ?></td>
                            <td><?php echo htmlspecialchars($patient['name']); ?></td>
                            <td><?php echo htmlspecialchars($patient['age'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($patient['gender'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($patient['phone'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($patient['address'] ?? 'N/A'); ?></td>
                            <td class="action-buttons">
                                <a href="edit_patient.php?id=<?php echo $patient['id']; ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="?delete=<?php echo $patient['id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this patient? This will affect related appointments.')">
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