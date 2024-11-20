<?php

include '../database/db.php';


if (isset($_POST["student_id"])) {
    $id = $_POST["student_id"];
} else {
    die("Error: student_id not provided.");
}

if (isset($_POST["submit"])) {

    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($_FILES['profileImage']['name'])) {
        $imageName = basename($_FILES['profileImage']['name']);
        $imagePath = 'images-data/' . $imageName;

        if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $imagePath)) {
            
            $imageQueryPart = ", student.image = '$imagePath'";
        } else {
            echo "Failed to upload image.";
            exit();
        }
    } else {
        $imageQueryPart = ""; 
    }

    $sql = "UPDATE student 
            JOIN loginCredentials ON student.id = loginCredentials.student_id
            SET 
                student.firstname = '$firstname',
                student.middlename = '$middlename',
                student.lastname = '$lastname',
                student.age = '$age',
                student.gender = '$gender',
                student.phone = '$phone',
                student.address = '$address',
                loginCredentials.email = '$email',
                loginCredentials.password = '$password',
                 $imageQueryPart
            WHERE student.id = '$id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: admin.php?msg=Data updated successfully");
        exit();
    } else {
        echo "Failed: " . mysqli_error($conn);
    }
}

$sql = "SELECT student.firstname, student.middlename, student.lastname, student.age, student.gender, student.phone, student.address, student.image, loginCredentials.email, loginCredentials.password
        FROM student
        JOIN loginCredentials ON student.id = loginCredentials.student_id
        WHERE student.id = $id LIMIT 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/adminstudentupdate.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Edit Student Information</title>
</head>
<body>
       <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="#dashboard" onclick="showSection('dashboard')">Dashboard</a></li>
            <li><a href="#students" onclick="showSection('students')">Students</a></li>
            <li><a href="#settings" onclick="showSection('settings')">Settings</a></li>
            <li><a href="./admin/adminlogout.php" onclick="showSection('settings')">Log out</a></li>
        </ul>
    </div>
       <div class="studentUpdate">
    <span class="close-btn" onclick="closeEditModal()"><i class="fa-solid fa-x"></i></span>
       <form method="POST" enctype="multipart/form-data">

    <div class="profile-container">
        <?php if (!empty($row['image']) && file_exists('../' . htmlspecialchars($row['image']))) { ?>
 
            <img src="../<?php echo htmlspecialchars($row['image']); ?>" class="profile-image" id="profileDisplay" alt="Profile">
        <?php } else { ?>
            <div class="profile-image">picture</div>
        <?php } ?>
        <div class="edit-btn" onclick="document.getElementById('profileImageUpload').click()"><i class="fa-solid fa-pen-to-square"></i></div>
        <input type="file" id="profileImageUpload" name="profileImage" accept="image/*" onchange="previewImage(event)" hidden>
    </div>
    
    <div class="info">
        <div class="left">
            <label>First Name:</label>
            <input type="text" name="firstname" value="<?php echo htmlspecialchars($row['firstname'] ?? ''); ?>">

            <label>Middle Name:</label>
            <input type="text" name="middlename" value="<?php echo htmlspecialchars($row['middlename'] ?? ''); ?>">
            
            <label>Last Name:</label>
            <input type="text" name="lastname" value="<?php echo htmlspecialchars($row['lastname'] ?? ''); ?>">

           
        </div>
        
        <div class="separator"></div>
        
        <div class="right">
            <label>Age:</label>
              <input type="text" name="age" value="<?php echo htmlspecialchars($row['age'] ?? ''); ?>">
            <label for="gender">Gender:</label>
                <select name="gender" id="gender">
                    <option value="" disabled>Select Gender</option>
                    <option value="male" <?php echo ($row['gender'] ?? '') === 'male' ? 'selected' : ''; ?>>Male</option>
                    <option value="female" <?php echo ($row['gender'] ?? '') === 'female' ? 'selected' : ''; ?>>Female</option>
                </select>
                <br>

            <label>Contact:</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($row['phone'] ?? ''); ?>">
        </div>
    </div>
    <div class="addresses">
        <label>Address:</label><br>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($row['phone'] ?? ''); ?>">
    </div>
    <div class="email-password">
        <div>
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($row['email'] ?? ''); ?>">
        </div>
     <div style="position: relative;">
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" value="<?php echo htmlspecialchars($row['password'] ?? ''); ?>">
    <button type="button" onclick="togglePassword()" style="position: absolute; right: 2px; top: 75%; transform: translateY(-50%); background: none; border: none; cursor: pointer;">
        <i id="toggleIcon" class="fa-solid fa-eye"></i>
    </button>
    </div>
    </div>
    
    <button type="submit" name="submit">Save Changes</button>
        </form>
</div>

      <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profileDisplay').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
       function togglePassword() {
        var passwordField = document.getElementById("password");
        var toggleIcon = document.getElementById("toggleIcon");

        if (passwordField.type === "password") {
            passwordField.type = "text";
            toggleIcon.classList.remove("fa-eye");
            toggleIcon.classList.add("fa-eye-slash");
        } else {
            passwordField.type = "password";
            toggleIcon.classList.remove("fa-eye-slash");
            toggleIcon.classList.add("fa-eye");
        }
    }

    </script>
</body>
</html>