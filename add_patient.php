<?php
include "db_connect.php";

if (isset($_POST['submit'])) {

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $birth_date = $_POST['birth_date'];
    $gender = $_POST['gender'];
    $contact_number = $_POST['contact_number'];

    $sql = "INSERT INTO patients (first_name, last_name, birth_date, gender, contact_number)
            VALUES ('$first_name', '$last_name', '$birth_date', '$gender', '$contact_number')";

    if (mysqli_query($conn, $sql)) {
        echo "Patient added successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>

<h2>Add Patient</h2>
<form method="POST">
    First Name: <input type="text" name="first_name" required><br><br>
    Last Name: <input type="text" name="last_name" required><br><br>
    Birth Date: <input type="date" name="birth_date" required><br><br>
    Gender: 
    <select name="gender" required>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
    </select><br><br>
    Contact Number: <input type="text" name="contact_number" required><br><br>
    <input type="submit" name="submit" value="Add Patient">
</form>