<?php
include "./database/db.php";

if (isset($_POST['submit'])) {

    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $middlename = mysqli_real_escape_string($conn, $_POST['middlename']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $uploadDir = 'uploads/';
    $imageName = $_FILES['profilePicture']['name'];
    $imageTmpName = $_FILES['profilePicture']['tmp_name'];
    $imagePath = $uploadDir . basename($imageName);

    $select = "SELECT * FROM loginCredentials WHERE email = '$email'";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
        $error[] = 'User already exists!';
    } else {

        if (move_uploaded_file($imageTmpName, $imagePath)) {
        
            $insert_student = "INSERT INTO student (firstname, lastname, middlename, age, gender, address, phone, image) 
                VALUES ('$firstname', '$lastname', '$middlename', '$age', '$gender', '$address', '$phone', '$imagePath')";

            if (mysqli_query($conn, $insert_student)) {
                // Get the student ID
                $student_id = mysqli_insert_id($conn);

                // Hash the password and insert into 'loginCredentials'
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $insert_credentials = "INSERT INTO loginCredentials (student_id, email, password) 
                    VALUES ('$student_id', '$email', '$hashedPassword')";

                if (mysqli_query($conn, $insert_credentials)) {
                    header('Location: studentRegistrationForm.php');
                    exit();
                } else {
                    echo 'Error inserting credentials: ' . mysqli_error($conn);
                }
            } else {
                echo 'Error inserting student: ' . mysqli_error($conn);
            }
        } else {
            echo 'Failed to upload profile picture!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="icon" href="./images/bsitlogo.png">
    <link rel="stylesheet" href="styles.css">
</head>
<form>
    <div class="container">
      <form action="studentRegistrationForm.php" method="post" enctype="multipart/form-data">
        <div class="form-section">
            <div class="form-row">
                <div class="form-group">
                    <label for="firstname">Firstname:</label>
                    <input type="text" id="firstname" name="firstname">
                </div>
                <div class="form-group">
                    <label for="middlename">Middlename:</label>
                    <input type="text" id="middlename" name="middlename">
                </div>
                <div class="form-group">
                    <label for="lastname">Lastname:</label>
                    <input type="text" id="lastname" name="lastname">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="age">Age:</label>
                    <input type="number" id="age" name="age">
                </div>
                <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>

                <div class="form-group">
                    <label for="phone">Contact Number:</label>
                    <input type="text" id="contact" name="phone">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group full-width">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address">
                </div>
            </div>
        </div>

        <div class="profile-section">
            <div class="profile-pic">
                <label for="profile-image">Profile Picture:</label>
                <input type="file" id="profile-image" name="profile-image" accept="image/*">
            </div>
            <div class="login-section">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email">
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password">
                </div>
                <button class="register-btn">Register</button>
            </div>
        </div>
    </div>
    </form>
</body>
</html>
