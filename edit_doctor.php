<?php
require_once 'db_connect.php';
includeHeader('Edit Doctor');

$id = $_GET['id'] ?? 0;
$errors = [];

// Fetch doctor data
try {
    $stmt = $pdo->prepare("SELECT * FROM doctors WHERE doctor_id = ?");
    $stmt->execute([$id]);
    $doctor = $stmt->fetch();
    
    if (!$doctor) {
        $_SESSION['error'] = "Doctor not found.";
        header("Location: view_doctors.php");
        exit;
    }
} catch(PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    header("Location: view_doctors.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $specialization = trim($_POST['specialization'] ?? '');
    $contact_number = trim($_POST['contact_number'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    if (empty($first_name)) {
        $errors[] = "First name is required.";
    }
    
    if (empty($last_name)) {
        $errors[] = "Last name is required.";
    }
    
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE doctors SET first_name = ?, last_name = ?, specialization = ?, contact_number = ?, email = ? WHERE doctor_id = ?");
            $stmt->execute([$first_name, $last_name, $specialization, $contact_number, $email, $id]);
            
            $_SESSION['success'] = "Doctor updated successfully!";
            header("Location: view_doctors.php");
            exit;
        } catch(PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
} else {
    // Populate form with existing data
    $first_name = $doctor['first_name'];
    $last_name = $doctor['last_name'];
    $specialization = $doctor['specialization'];
    $contact_number = $doctor['contact_number'];
    $email = $doctor['email'];
}
?>

<h1 class="mb-4">Edit Doctor</h1>

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
        <form method="POST" action="">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="first_name" class="form-label">First Name *</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" 
                           value="<?php echo htmlspecialchars($first_name); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="last_name" class="form-label">Last Name *</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" 
                           value="<?php echo htmlspecialchars($last_name); ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="specialization" class="form-label">Specialization</label>
                <input type="text" class="form-control" id="specialization" name="specialization" 
                       value="<?php echo htmlspecialchars($specialization); ?>">
            </div>
            
            <div class="mb-3">
                <label for="contact_number" class="form-label">Contact Number</label>
                <input type="tel" class="form-control" id="contact_number" name="contact_number" 
                       value="<?php echo htmlspecialchars($contact_number); ?>">
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?php echo htmlspecialchars($email); ?>">
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="view_doctors.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Doctor</button>
            </div>
        </form>
    </div>
</div>

<?php includeFooter(); ?>