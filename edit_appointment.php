<?php
require_once 'db_connect.php';
includeHeader('Edit Appointment');

$id = $_GET['id'] ?? 0;
$errors = [];

// Fetch appointment data
try {
    $stmt = $pdo->prepare("
        SELECT a.*, d.name as doctor_name, p.name as patient_name 
        FROM appointments a
        JOIN doctors d ON a.doctor_id = d.id
        JOIN patients p ON a.patient_id = p.id
        WHERE a.id = ?
    ");
    $stmt->execute([$id]);
    $appointment = $stmt->fetch();
    
    if (!$appointment) {
        $_SESSION['error'] = "Appointment not found.";
        header("Location: view_appointments.php");
        exit;
    }
} catch(PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    header("Location: view_appointments.php");
    exit;
}

// Fetch doctors for dropdown
try {
    $stmt = $pdo->query("SELECT id, name, specialization FROM doctors ORDER BY name");
    $doctors = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Error fetching doctors: " . $e->getMessage();
}

// Fetch patients for dropdown
try {
    $stmt = $pdo->query("SELECT id, name FROM patients ORDER BY name");
    $patients = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Error fetching patients: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    $doctor_id = $_POST['doctor_id'] ?? '';
    $patient_id = $_POST['patient_id'] ?? '';
    $appointment_date = $_POST['appointment_date'] ?? '';
    $appointment_time = $_POST['appointment_time'] ?? '';
    $notes = trim($_POST['notes'] ?? '');
    
    if (empty($doctor_id)) {
        $errors[] = "Please select a doctor.";
    }
    
    if (empty($patient_id)) {
        $errors[] = "Please select a patient.";
    }
    
    if (empty($appointment_date)) {
        $errors[] = "Appointment date is required.";
    }
    
    if (empty($appointment_time)) {
        $errors[] = "Appointment time is required.";
    }
    
    // Check for double booking (excluding current appointment)
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                SELECT COUNT(*) as count 
                FROM appointments 
                WHERE doctor_id = ? 
                AND appointment_date = ? 
                AND appointment_time = ?
                AND id != ?
            ");
            $stmt->execute([$doctor_id, $appointment_date, $appointment_time, $id]);
            $result = $stmt->fetch();
            
            if ($result['count'] > 0) {
                $errors[] = "This time slot is already booked for this doctor.";
            }
        } catch(PDOException $e) {
            $errors[] = "Error checking availability: " . $e->getMessage();
        }
    }
    
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                UPDATE appointments 
                SET doctor_id = ?, patient_id = ?, appointment_date = ?, appointment_time = ?, notes = ? 
                WHERE id = ?
            ");
            $stmt->execute([$doctor_id, $patient_id, $appointment_date, $appointment_time, $notes, $id]);
            
            $_SESSION['success'] = "Appointment updated successfully!";
            header("Location: view_appointments.php");
            exit;
        } catch(PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
} else {
    // Populate form with existing data
    $doctor_id = $appointment['doctor_id'];
    $patient_id = $appointment['patient_id'];
    $appointment_date = $appointment['appointment_date'];
    $appointment_time = $appointment['appointment_time'];
    $notes = $appointment['notes'];
}
?>

<h1 class="mb-4">Edit Appointment</h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST" action="" id="appointmentForm">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="doctor_id" class="form-label">Select Doctor *</label>
                    <select class="form-control" id="doctor_id" name="doctor_id" required>
                        <option value="">Choose a doctor...</option>
                        <?php foreach ($doctors as $doctor): ?>
                            <option value="<?php echo $doctor['id']; ?>" 
                                <?php echo $doctor_id == $doctor['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($doctor['name']); ?> 
                                (<?php echo htmlspecialchars($doctor['specialization'] ?? 'General'); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="patient_id" class="form-label">Select Patient *</label>
                    <select class="form-control" id="patient_id" name="patient_id" required>
                        <option value="">Choose a patient...</option>
                        <?php foreach ($patients as $patient): ?>
                            <option value="<?php echo $patient['id']; ?>" 
                                <?php echo $patient_id == $patient['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($patient['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="appointment_date" class="form-label">Appointment Date *</label>
                    <input type="date" class="form-control" id="appointment_date" name="appointment_date" 
                           value="<?php echo htmlspecialchars($appointment_date); ?>" 
                           min="<?php echo date('Y-m-d'); ?>" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="appointment_time" class="form-label">Appointment Time *</label>
                    <input type="time" class="form-control" id="appointment_time" name="appointment_time" 
                           value="<?php echo htmlspecialchars($appointment_time); ?>" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="notes" class="form-label">Notes (Optional)</label>
                <textarea class="form-control" id="notes" name="notes" rows="3"><?php echo htmlspecialchars($notes); ?></textarea>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="view_appointments.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Appointment</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('appointmentForm').addEventListener('submit', function(e) {
    const date = document.getElementById('appointment_date').value;
    const time = document.getElementById('appointment_time').value;
    
    if (date && time) {
        const selectedDateTime = new Date(date + 'T' + time);
        const now = new Date();
        
        if (selectedDateTime < now) {
            e.preventDefault();
            alert('Appointment cannot be scheduled in the past.');
        }
    }
});
</script>

<?php includeFooter(); ?>