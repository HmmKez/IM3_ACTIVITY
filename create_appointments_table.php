<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "db_connect.php";

try {
    $sql = "CREATE TABLE IF NOT EXISTS appointments (
        appointment_id INT AUTO_INCREMENT PRIMARY KEY,
        patient_id INT NOT NULL,
        doctor_id INT NOT NULL,
        appointment_date DATETIME NOT NULL,
        status VARCHAR(50) NOT NULL,
        notes TEXT,
        FOREIGN KEY (patient_id) REFERENCES patients(patient_id) ON DELETE CASCADE,
        FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id) ON DELETE CASCADE
    ) ENGINE=InnoDB";

    $pdo->exec($sql);

    echo "Table 'appointments' created successfully";

} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
?>