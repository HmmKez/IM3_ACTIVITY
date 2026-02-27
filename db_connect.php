<?php
// db_connect.php - Enhanced database connection
$host = 'localhost';
$dbname = 'im3_activity'; // Change to your database name
$username = 'root'; // Change to your MySQL username
$password = ''; // Change to your MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Create a simple function for header includes
function includeHeader($title) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $title; ?> - IM3 Activity</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <style>
            body { padding-top: 20px; background-color: #f8f9fa; }
            .container { max-width: 1200px; }
            .navbar { margin-bottom: 30px; background-color: #343a40 !important; }
            .navbar-brand, .nav-link { color: white !important; }
            .nav-link:hover { color: #ffc107 !important; }
            .card { box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
            .btn-group-sm { margin-right: 5px; }
            .table td { vertical-align: middle; }
            .action-buttons { white-space: nowrap; }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
            <div class="container">
                <a class="navbar-brand" href="dashboard.php">IM3 Activity System</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="view_doctors.php">Doctors</a></li>
                        <li class="nav-item"><a class="nav-link" href="view_patients.php">Patients</a></li>
                        <li class="nav-item"><a class="nav-link" href="view_appointments.php">Appointments</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container" style="margin-top: 80px;">
    <?php
}

function includeFooter() {
    ?>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </body>
    </html>
    <?php
}
?>