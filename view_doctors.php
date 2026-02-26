<?php
include "db_connect.php";

$sql = "SELECT * FROM doctors";
$result = mysqli_query($conn, $sql);
?>

<h2>Doctors List</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Specialization</th>
        <th>Email</th>
        <th>Contact Number</th>
    </tr>

<?php
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['doctor_id']}</td>
                <td>{$row['first_name']}</td>
                <td>{$row['last_name']}</td>
                <td>{$row['specialization']}</td>
                <td>{$row['email']}</td>
                <td>{$row['contact_number']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='6'>No records found</td></tr>";
}

mysqli_close($conn);
?>
</table>