<?php
include "db_connect.php";

$sql = "SELECT 
            a.appointment_id,
            p.first_name AS patient_first,
            p.last_name AS patient_last,
            d.first_name AS doctor_first,
            d.last_name AS doctor_last,
            a.appointment_date,
            a.status,
            a.notes
        FROM appointments a
        JOIN patients p ON a.patient_id = p.patient_id
        JOIN doctors d ON a.doctor_id = d.doctor_id";

$result = mysqli_query($conn, $sql);
?>

<h2>Appointments List</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Patient</th>
        <th>Doctor</th>
        <th>Date</th>
        <th>Status</th>
        <th>Notes</th>
    </tr>

<?php
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['appointment_id']}</td>
                <td>{$row['patient_first']} {$row['patient_last']}</td>
                <td>{$row['doctor_first']} {$row['doctor_last']}</td>
                <td>{$row['appointment_date']}</td>
                <td>{$row['status']}</td>
                <td>{$row['notes']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='6'>No records found</td></tr>";
}

mysqli_close($conn);
?>
</table>