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

    $file_name = '';
 if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] == 0) {
    $file_name = $_FILES['profilePicture']['name'];
    $tempname = $_FILES['profilePicture']['tmp_name'];
    $folder = 'images-data/' . $file_name;

    if (!move_uploaded_file($tempname, $folder)) {
        echo "Failed to upload image.";
        exit();
    }
}

    $sql = "INSERT INTO student (firstname, middlename, lastname, age, gender, phone, address, image) 
            VALUES ('$firstname', '$middlename', '$lastname', '$age', '$gender', '$phone', '$address', '$file_name')";

    if (mysqli_query($conn, $sql)) {
        $student_id = mysqli_insert_id($conn);

        $credentials_sql = "INSERT INTO loginCredentials (student_id, email, password) 
                            VALUES ('$student_id', '$email', '$password')";

        if (mysqli_query($conn, $credentials_sql)) {
            echo "Student added successfully!";
            header("Location: studentRegistrationForm.php?id=$id&update=success");
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
    <link rel="stylesheet" href="css/studentRegistrationForm.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
    <div class="back">
    <a href="index.php">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
</div>
    <div class="type">
        <p>Please Fill up the Following</p>
    </div>

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
    <img id="profileImage" src="./images/blank-profile-picture-973460_1280.png" alt="Profile Picture">
    <label for="profilePicture">Profile Picture:</label>
    <input type="file" id="profilePicture" name="profilePicture" accept="image/*" required>
    <button class="edit-btn" type="button" id="editButton"><i class="fa-solid fa-pen"></i></button>
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
 <section class="modal-section">
    <span class="overlay"></span>
    <div class="modal-box">
        <i class="fa-regular fa-circle-check"></i>
        <h2>Success</h2>
        <h3>You have successfully Register!.</h3>
        <div class="buttons">
            <a href="index.php">
            <button class="close-btn">OK</button>
            </a>
        </div>
    </div>
</section>
<script>
   // Get references to elements
    const profileInput = document.getElementById('profilePicture');
    const profileImage = document.getElementById('profileImage');
    const editButton = document.getElementById('editButton');

    // Event listener for the "Edit" button
    editButton.addEventListener('click', function() {
        profileInput.click(); // Simulate a click on the file input
    });

    // Event listener for file selection
    profileInput.addEventListener('change', function(event) {
        const file = event.target.files[0]; // Get the selected file
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                profileImage.src = e.target.result; // Update the image source
            };
            reader.readAsDataURL(file); // Read the file as a Data URL
        }
    });
     document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const section = document.querySelector(".modal-section"),
              overlay = document.querySelector(".overlay"),
              closeBtn = document.querySelector(".close-btn");

        // Show the modal if update is successful
        if (urlParams.get('update') === 'success') {
            section.classList.add("active");
        }

        // Close the modal when clicking overlay or close button
        overlay.addEventListener("click", () => section.classList.remove("active"));
        closeBtn.addEventListener("click", () => section.classList.remove("active"));

        // Optionally, remove the 'update=success' parameter from the URL
        window.history.replaceState({}, document.title, window.location.pathname);
    });
</script>
</body>
</html>
