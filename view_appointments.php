<?php
require_once 'db_connect.php';
includeHeader('View Appointments');

// Handle delete request
if (isset($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM appointments WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $success = "Appointment deleted successfully!";
    } catch(PDOException $e) {
        $error = "Error deleting appointment: " . $e->getMessage();
    }
}

// Build query based on filters
$query = "
    SELECT a.*, d.name as doctor_name, d.specialization, p.name as patient_name 
    FROM appointments a
    JOIN doctors d ON a.doctor_id = d.id
    JOIN patients p ON a.patient_id = p.id
";

$params = [];
$conditions = [];

// Filter by today
if (isset($_GET['today'])) {
    $conditions[] = "DATE(a.appointment_date) = CURDATE()";
}

// Filter by date range
if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

$query .= " ORDER BY a.appointment_date DESC, a.appointment_time DESC";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $appointments = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Error fetching appointments: " . $e->getMessage();
}
?>

<h1 class="mb-4">Manage Appointments</h1>

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
        <h5 class="mb-0">Appointments List</h5>
        <div>
            <a href="?today=1" class="btn btn-sm btn-info me-2">Today's Appointments</a>
            <a href="view_appointments.php" class="btn btn-sm btn-secondary me-2">All Appointments</a>
            <a href="add_appointment.php" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Schedule Appointment
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($appointments)): ?>
            <p class="text-muted mb-0">No appointments found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Doctor</th>
                            <th>Patient</th>
                            <th>Status</th>
                            <th>Notes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $appointment): ?>
                        <tr>
                            <td><?php echo date('M d, Y', strtotime($appointment['appointment_date'])); ?></td>
                            <td><?php echo date('h:i A', strtotime($appointment['appointment_time'])); ?></td>
                            <td>
                                <?php echo htmlspecialchars($appointment['doctor_name']); ?>
                                <br><small class="text-muted"><?php echo htmlspecialchars($appointment['specialization'] ?? ''); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($appointment['patient_name']); ?></td>
                            <td>
                                <?php
                                $today = date('Y-m-d');
                                $appDate = $appointment['appointment_date'];
                                $appTime = $appointment['appointment_time'];
                                $currentTime = date('H:i:s');
                                
                                if ($appDate < $today) {
                                    echo '<span class="badge bg-secondary">Past</span>';
                                } elseif ($appDate == $today) {
                                    if ($appTime < $currentTime) {
                                        echo '<span class="badge bg-warning">Ongoing</span>';
                                    } else {
                                        echo '<span class="badge bg-success">Today</span>';
                                    }
                                } else {
                                    echo '<span class="badge bg-primary">Upcoming</span>';
                                }
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars(substr($appointment['notes'] ?? '', 0, 50)) . (strlen($appointment['notes'] ?? '') > 50 ? '...' : ''); ?></td>
                            <td class="action-buttons">
                                <a href="edit_appointment.php?id=<?php echo $appointment['id']; ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="?delete=<?php echo $appointment['id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this appointment?')">
                                    <i class="fas fa-trash"></i>
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