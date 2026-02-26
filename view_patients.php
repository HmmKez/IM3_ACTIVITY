<?php
include "db_connect.php";

$sql = "SELECT * FROM patients";
$result = mysqli_query($conn, $sql);
?>

<h2>Patients List</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Birth Date</th>
        <th>Gender</th>
        <th>Contact Number</th>
    </tr>

<?php
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['patient_id']}</td>
                <td>{$row['first_name']}</td>
                <td>{$row['last_name']}</td>
                <td>{$row['birth_date']}</td>
                <td>{$row['gender']}</td>
                <td>{$row['contact_number']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='6'>No records found</td></tr>";
}

mysqli_close($conn);
?>
</table>