<?php
include('../database/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['student_id'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $imageQuery = "";

    if (!empty($_FILES['profileImage']['name'])) {
        $imageName = basename($_FILES['profileImage']['name']);
        $imagePath = '../images-data/' . $imageName;

        if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $imagePath)) {
            $imageQuery = ", image = '$imagePath'";
        }
    }

    $updateQuery = "UPDATE student SET 
                    firstname = '$firstname', 
                    middlename = '$middlename', 
                    lastname = '$lastname', 
                    age = '$age', 
                    gender = '$gender', 
                    phone = '$phone', 
                    address = '$address' 
                    $imageQuery 
                    WHERE id = $id";

    if (mysqli_query($conn, $updateQuery)) {
        header("Location: admin.php");
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div id="edit-student" class="section-content" style="display: none;">
    <h2>Edit Student</h2>
    <form method="POST" action="studentUpdateForm.php" enctype="multipart/form-data">
        <input type="hidden" id="edit-student-id" name="student_id">
        
        <label for="edit-firstname">First Name:</label>
        <input type="text" id="edit-firstname" name="firstname" required>
        
        <label for="edit-middlename">Middle Name:</label>
        <input type="text" id="edit-middlename" name="middlename">
        
        <label for="edit-lastname">Last Name:</label>
        <input type="text" id="edit-lastname" name="lastname" required>
        
        <label for="edit-age">Age:</label>
        <input type="number" id="edit-age" name="age" required>
        
        <label for="edit-gender">Gender:</label>
        <select id="edit-gender" name="gender">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
        
        <label for="edit-phone">Contact:</label>
        <input type="text" id="edit-phone" name="phone" required>
        
        <label for="edit-address">Address:</label>
        <textarea id="edit-address" name="address" required></textarea>
        
        <label for="edit-profileImage">Profile Image:</label>
        <input type="file" id="edit-profileImage" name="profileImage">
        
        <button type="submit" name="update">Save Changes</button>
    </form>
</div>

</body>
</html>