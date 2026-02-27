<?php
require_once 'db_connect.php';
includeHeader('Add Patient');

$first_name = $last_name = $birth_date = $gender = $phone = $address = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $birth_date = trim($_POST['birth_date'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    
    if (empty($first_name)) {
        $errors[] = "First name is required.";
    }

    if (empty($last_name)) {
        $errors[] = "Last name is required.";
    }

    if (!empty($birth_date) && !strtotime($birth_date)) {
        $errors[] = "Please enter a valid birth date.";
    }
    
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO patients (first_name, last_name, birth_date, gender, phone, address) 
                                   VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$first_name, $last_name, $birth_date, $gender, $phone, $address]);
            
            $_SESSION['success'] = "Patient added successfully!";
            header("Location: view_patients.php");
            exit;
        } catch(PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>

<h1 class="mb-4">Add New Patient</h1>

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
                <label for="birth_date" class="form-label">Birth Date</label>
                <input type="date" class="form-control" id="birth_date" name="birth_date" 
                       value="<?php echo htmlspecialchars($birth_date); ?>">
            </div>
            
            <div class="mb-3">
                <label for="gender" class="form-label">Gender</label>
                <select class="form-control" id="gender" name="gender">
                    <option value="">Select Gender</option>
                    <option value="Male" <?php echo $gender == 'Male' ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo $gender == 'Female' ? 'selected' : ''; ?>>Female</option>
                    <option value="Other" <?php echo $gender == 'Other' ? 'selected' : ''; ?>>Other</option>
                </select>
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
                <button type="submit" class="btn btn-primary">Save Patient</button>
            </div>
        </form>
    </div>
</div>

<?php includeFooter(); ?>