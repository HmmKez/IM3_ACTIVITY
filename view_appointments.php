<?php
require_once 'db_connect.php';
includeHeader('View Appointments');

// Handle delete request
if (isset($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM appointments WHERE appointment_id = ?");
        $stmt->execute([$_GET['delete']]);
        $success = "Appointment deleted successfully!";
    } catch(PDOException $e) {
        $error = "Error deleting appointment: " . $e->getMessage();
    }
}

// Build query
$query = "
    SELECT a.*, 
           d.first_name AS doctor_first, d.last_name AS doctor_last, d.specialization,
           p.first_name AS patient_first, p.last_name AS patient_last
    FROM appointments a
    JOIN doctors d ON a.doctor_id = d.doctor_id
    JOIN patients p ON a.patient_id = p.patient_id
";

$params = [];
$conditions = [];

// Filter by today
if (isset($_GET['today'])) {
    $conditions[] = "DATE(a.appointment_date) = CURDATE()";
}

if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

$query .= " ORDER BY a.appointment_date DESC";

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
                            <th>ID</th>
                            <th>Appointment Date & Time</th>
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
                            <td><?php echo $appointment['appointment_id']; ?></td>
                            <td><?php echo date('M d, Y h:i A', strtotime($appointment['appointment_date'])); ?></td>
                            <td>
                                <?php echo htmlspecialchars($appointment['doctor_first'] . ' ' . $appointment['doctor_last']); ?>
                                <br><small class="text-muted"><?php echo htmlspecialchars($appointment['specialization'] ?? ''); ?></small>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($appointment['patient_first'] . ' ' . $appointment['patient_last']); ?>
                            </td>
                            <td>
                                <?php
                                $today = date('Y-m-d H:i:s');
                                $appDateTime = $appointment['appointment_date'];

                                if ($appDateTime < $today) {
                                    echo '<span class="badge bg-secondary">Past</span>';
                                } elseif ($appDateTime >= $today && $appDateTime <= date('Y-m-d H:i:s', strtotime('+1 hour'))) {
                                    echo '<span class="badge bg-warning">Ongoing</span>';
                                } else {
                                    echo '<span class="badge bg-primary">Upcoming</span>';
                                }
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars(substr($appointment['notes'] ?? '', 0, 50)) . (strlen($appointment['notes'] ?? '') > 50 ? '...' : ''); ?></td>
                            <td class="action-buttons">
                                <a href="edit_appointment.php?id=<?php echo $appointment['appointment_id']; ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="?delete=<?php echo $appointment['appointment_id']; ?>" 
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