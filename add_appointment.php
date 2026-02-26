<?php
include "db_connect.php";

if (isset($_POST['submit'])) {

    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $status = $_POST['status'];
    $notes = $_POST['notes'];

    $sql = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, status, notes)
            VALUES ('$patient_id', '$doctor_id', '$appointment_date', '$status', '$notes')";

    if (mysqli_query($conn, $sql)) {
        echo "Appointment added successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>

<h2>Add Appointment</h2>
<form method="POST">
    Patient ID: <input type="number" name="patient_id" required><br><br>
    Doctor ID: <input type="number" name="doctor_id" required><br><br>
    Appointment Date: <input type="datetime-local" name="appointment_date" required><br><br>
    Status:
    <select name="status" required>
        <option value="Scheduled">Scheduled</option>
        <option value="Completed">Completed</option>
        <option value="Cancelled">Cancelled</option>
    </select><br><br>
    Notes: <textarea name="notes"></textarea><br><br>
    <input type="submit" name="submit" value="Add Appointment">
</form>