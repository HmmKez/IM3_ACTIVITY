<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "db_connect.php";

try {
    $sql = "CREATE TABLE IF NOT EXISTS doctors (
        doctor_id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) NOT NULL,
        specialization VARCHAR(100) NOT NULL,
        email VARCHAR(150) NOT NULL,
        contact_number VARCHAR(20) NOT NULL
    )";

    $pdo->exec($sql);

    echo "Table 'doctors' created successfully";

} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
?>