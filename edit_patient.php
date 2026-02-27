<?php
require_once 'db_connect.php';
includeHeader('Edit Patient');

$id = $_GET['id'] ?? 0;
$errors = [];

// Fetch patient data
try {
    $stmt = $pdo->prepare("SELECT * FROM patients WHERE id = ?");
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
    $name = trim($_POST['name'] ?? '');
    $age = trim($_POST['age'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    
    if (empty($name)) {
        $errors[] = "Patient name is required.";
    }
    
    if (!empty($age) && (!is_numeric($age) || $age < 0 || $age > 150)) {
        $errors[] = "Please enter a valid age (0-150).";
    }
    
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE patients SET name = ?, age = ?, gender = ?, phone = ?, address = ? WHERE id = ?");
            $stmt->execute([$name, $age, $gender, $phone, $address, $id]);
            
            $_SESSION['success'] = "Patient updated successfully!";
            header("Location: view_patients.php");
            exit;
        } catch(PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
} else {
    // Populate form with existing data
    $name = $patient['name'];
    $age = $patient['age'];
    $gender = $patient['gender'];
    $phone = $patient['phone'];
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
            <div class="mb-3">
                <label for="name" class="form-label">Patient Name *</label>
                <input type="text" class="form-control" id="name" name="name" 
                       value="<?php echo htmlspecialchars($name); ?>" required>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="age" class="form-label">Age</label>
                    <input type="number" class="form-control" id="age" name="age" 
                           value="<?php echo htmlspecialchars($age); ?>" min="0" max="150">
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
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" class="form-control" id="phone" name="phone" 
                       value="<?php echo htmlspecialchars($phone); ?>">
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