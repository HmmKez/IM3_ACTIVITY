<?php
include "db_connect.php";

if (isset($_POST['submit'])) {

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $specialization = $_POST['specialization'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];

    $sql = "INSERT INTO doctors (first_name, last_name, specialization, email, contact_number)
            VALUES ('$first_name', '$last_name', '$specialization', '$email', '$contact_number')";

    if (mysqli_query($conn, $sql)) {
        echo "Doctor added successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>

<h2>Add Doctor</h2>
<form method="POST">
    First Name: <input type="text" name="first_name" required><br><br>
    Last Name: <input type="text" name="last_name" required><br><br>
    Specialization: <input type="text" name="specialization" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    Contact Number: <input type="text" name="contact_number" required><br><br>
    <input type="submit" name="submit" value="Add Doctor">
</form>