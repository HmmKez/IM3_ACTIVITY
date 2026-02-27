<?php
include "db_connect.php";

$sql = "CREATE TABLE patients (
    patient_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    birth_date DATE NOT NULL,
    gender VARCHAR(10) NOT NULL,
    contact_number VARCHAR(20) NOT NULL,
    address VARCHAR(100) NOT NULL
)";

if (mysqli_query($conn, $sql)) {
    echo "Table 'patients' created successfully";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}

mysqli_close($conn);
?>