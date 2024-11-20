<?php
include 'database/db.php';

session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $middlename = mysqli_real_escape_string($conn, $_POST['middlename']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Image upload handling
    $file_name = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $file_name = $_FILES['image']['name'];
        $tempname = $_FILES['image']['tmp_name'];
        $folder = 'images-data/' . $file_name;
        
        if (!move_uploaded_file($tempname, $folder)) {
            echo "Failed to upload image.";
            exit();
        }
    }

    // Insert into student table
    $sql = "INSERT INTO student (firstname, middlename, lastname, age, gender, phone, address, image) 
            VALUES ('$firstname', '$middlename', '$lastname', '$age', '$gender', '$phone', '$address', '$file_name')";

    if (mysqli_query($conn, $sql)) {
        $student_id = mysqli_insert_id($conn);

        // Insert into loginCredentials table
        $credentials_sql = "INSERT INTO loginCredentials (student_id, email, password) 
                            VALUES ('$student_id', '$email', '$password')";

        if (mysqli_query($conn, $credentials_sql)) {
            echo "Student added successfully!";
            header("Location: studentRegistrationForm.php");
            exit();
        } else {
            echo "Error in loginCredentials: " . mysqli_error($conn);
        }
    } else {
        echo "Error in student table: " . mysqli_error($conn);
    }

    mysqli_close($conn);
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

   <form action="" method="post" enctype="multipart/form-data">
    <div class="container">
        <div class="form-section">
            <div class="form-row">
                <div class="form-group">
                    <label for="firstname">Firstname:</label>
                    <input type="text" id="firstname" name="firstname" required>
                </div>
                <div class="form-group">
                    <label for="middlename">Middlename:</label>
                    <input type="text" id="middlename" name="middlename" required>
                </div>
                <div class="form-group">
                    <label for="lastname">Lastname:</label>
                    <input type="text" id="lastname" name="lastname" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="age">Age:</label>
                    <input type="number" id="age" name="age" required>
                </div>
                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="phone">Contact Number:</label>
                    <input type="text" id="phone" name="phone" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group full-width">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" required>
                </div>
            </div>
        </div>

        <div class="profile-section">
            <div class="profile-pic">
                <label for="profilePicture">Profile Picture:</label>
                <input type="file" id="profilePicture" name="profilePicture" accept="image/*" required>
            </div>
            <div class="login-section">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" name="submit" class="register-btn">Register</button>
            </div>
        </div>
    </div>
</form>

</body>
</html>
