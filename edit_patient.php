<?php
require_once 'db_connect.php';
includeHeader('Edit Patient');

$id = $_GET['id'] ?? 0;
$errors = [];

// Fetch patient data
try {
    $stmt = $pdo->prepare("SELECT * FROM patients WHERE patient_id = ?");
    $stmt->execute([$id]);
    $patient = $stmt->fetch();
    
    if (!$patient) {
        $_SESSION['error'] = "Patient not found.";
        header("Location: view_patients.php");
        exit;
    }
} catch(PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    header("Location: view_patients.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $birth_date = trim($_POST['birth_date'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $contact_number = trim($_POST['contact_number'] ?? '');
    $address = trim($_POST['address'] ?? '');
    
    if (empty($first_name)) {
        $errors[] = "First name is required.";
    }
    if (empty($last_name)) {
        $errors[] = "Last name is required.";
    }
    
    if (!empty($birth_date) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $birth_date)) {
        $errors[] = "Please enter a valid birth date (YYYY-MM-DD).";
    }
    
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE patients SET first_name = ?, last_name = ?, birth_date = ?, gender = ?, contact_number = ?, address = ? WHERE patient_id = ?");
            $stmt->execute([$first_name, $last_name, $birth_date, $gender, $contact_number, $address, $id]);
            
            $_SESSION['success'] = "Patient updated successfully!";
            header("Location: view_patients.php");
            exit;
        } catch(PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
} else {
    // Populate form with existing data
    $first_name = $patient['first_name'];
    $last_name = $patient['last_name'];
    $birth_date = $patient['birth_date'];
    $gender = $patient['gender'];
    $contact_number = $patient['contact_number'];
    $address = $patient['address'];
}
?>

<h1 class="mb-4">Edit Patient</h1>

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
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="birth_date" class="form-label">Birth Date</label>
                    <input type="date" class="form-control" id="birth_date" name="birth_date" 
                           value="<?php echo htmlspecialchars($birth_date); ?>">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-control" id="gender" name="gender">
                        <option value="">Select Gender</option>
                        <option value="Male" <?php echo $gender == 'Male' ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo $gender == 'Female' ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo $gender == 'Other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="contact_number" class="form-label">Contact Number</label>
                <input type="tel" class="form-control" id="contact_number" name="contact_number" 
                       value="<?php echo htmlspecialchars($contact_number); ?>">
            </div>
            
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" id="address" name="address" rows="2"><?php echo htmlspecialchars($address); ?></textarea>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="view_patients.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Patient</button>
            </div>
        </form>
    </div>
</div>

<?php includeFooter(); ?>