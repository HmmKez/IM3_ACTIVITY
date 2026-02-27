<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "db_connect.php";

try {
    $sql = "CREATE TABLE IF NOT EXISTS patients (
        patient_id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) NOT NULL,
        birth_date DATE NOT NULL,
        gender VARCHAR(10) NOT NULL,
        contact_number VARCHAR(20) NOT NULL,
        address VARCHAR(100) NOT NULL
    )";

    $pdo->exec($sql);

    echo "Table 'patients' created successfully";

} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
?>