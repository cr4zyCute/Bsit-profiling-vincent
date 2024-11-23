<?php
require 'database/db.php';
session_start(); 

if (!empty($_SESSION['student_id'])) {
    $student_id = $_SESSION['student_id'];

    $query = "
        SELECT student.*, loginCredentials.email, loginCredentials.password
        FROM student 
        JOIN loginCredentials ON student.id = loginCredentials.student_id 
        WHERE student.id = '$student_id'
    ";

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $student = mysqli_fetch_assoc($result);
    } else {
        echo "Student profile not found."; 
        exit();
    }
} else {
    header("Location: loginForm.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $imageQueryPart = ""; // Initialize empty by default

    if (!empty($_FILES['profileImage']['name'])) {
        $imageName = basename($_FILES['profileImage']['name']);
        $imagePath = 'images-data/' . $imageName;

       if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $imagePath)) {
    $imageQueryPart = ", student.image = '$imagePath'";
} else {
    echo "Failed to upload image.";
    var_dump($_FILES); 
    exit();
}

    }

    $updateQuery = "
        UPDATE student 
        JOIN loginCredentials ON student.id = loginCredentials.student_id
        SET 
            student.firstname = '$firstname',
            student.lastname = '$lastname',
            student.age = '$age',
            student.gender = '$gender',
            student.phone = '$phone',
            student.address = '$address',
            loginCredentials.email = '$email'
            $imageQueryPart
        WHERE student.id = '$student_id'
    ";

    if (mysqli_query($conn, $updateQuery)) {
        header("Location: student.php");
        exit();
    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User - Student Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/studenProfile.css">
    <link rel="icon" href="./images/bsitlogo.png">
</head>
<body>
    <header>
    <div class="top-buttons">
        <button class="email-btn">Send Approval<i class="fa-regular fa-paper-plane"></i></button>
        <button onclick="openEditModal()" class="settings-btn"><i class="fa-solid fa-pen-to-square"></i></button>
        <a href="logout.php">
            <button class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i></button>
        </a>
    </div>
    </header>
    
    <div class="id-card">
        <div class="profile-container">
     <div class="profile-container">
    <?php
    $imagePath = 'images-data/' . htmlspecialchars($student['image']); // Adjust the path to match your structure
    if (!empty($student['image']) && file_exists($imagePath)) {
        echo '<img src="' . $imagePath . '?v=' . time() . '" style="width:120px; height:120px;" alt="Profile Image">';
    } else {
        echo '<img src="images-data/default-image.png" style="width:120px; height:120px;" alt="Default Image">';
    }
    ?>
</div>



        </div>
        <p class="id-number">ID Number: <?php echo $student['id'] ?></p>
        <div class="card-body">
            <div class="left-section info-box">
                <p><strong>Name:</strong> <?php echo $student['firstname'] ?></p>
                <p><strong>Last Name:</strong> <?php echo $student['lastname'] ?></p>
                <p><strong>Age:</strong> <?php echo $student['age'] ?></p>
                <p><strong>Gender:</strong> <?php echo $student['gender'] ?></p>
            </div>
            <div class="separator"></div>
            <div class="right-section info-box">
                <p><strong>Address:</strong> <?php echo $student['address'] ?></p>
                <p><strong>Email:</strong> <?php echo $student['email'] ?></p>
                <p><strong>Contact:</strong> <?php echo $student['phone'] ?></p>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="editModal">
        <form method="POST" enctype="multipart/form-data">
            <div class="profile-card">
                <span class="close-btn" onclick="closeEditModal()">X</span>
                <div class="profile-picture">
  <?php
    $imagePath = 'images-data/' . htmlspecialchars($student['image']); 
    if (!empty($student['image']) && file_exists($imagePath)) {
        echo '<img src="' . $imagePath . '?v=' . time() . '" style="width:120px; height:120px;" alt="Profile Image">';
    } else {
        echo '<img src="images-data/default-image.png" style="width:120px; height:120px;" alt="Default Image" id="profileDisplay" >';
    }
    ?>                    <input type="file" id="profileImageUpload" name="profileImage" accept="image/*" onchange="previewImage(event)" hidden>
                    <div class="edit-btn" onclick="document.getElementById('profileImageUpload').click()">Edit</div>
                </div>
                
                <div class="info">
                    <div class="left">
                        <label for="firstname">First Name:</label>
                        <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($student['firstname']); ?>" required>

                        <label for="lastname">Last Name:</label>
                        <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($student['lastname']); ?>" required>
                        
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($student['address']); ?>" required>
                    </div>
                    <div class="divider"></div>
                    <div class="right">
                        <label for="age">Age:</label>
                        <input type="text" id="age" name="age" value="<?php echo htmlspecialchars($student['age']); ?>" required>

                        <label for="gender">Gender:</label>
                        <input type="text" id="gender" name="gender" value="<?php echo htmlspecialchars($student['gender']); ?>" required>

                        <label for="phone">Contact:</label>
                        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($student['phone']); ?>" required>
                    </div>
                </div>

                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>">
                    <label for="password">Password:</label>
                    <input type="text" id="password" name="password" value="<?php echo htmlspecialchars($student['password']); ?>">
                </div>

                <button type="submit" class="save-btn">Save Changes</button>
            </div>
        </form>
    </div>

    <script src="js/editprofile.js"></script>
    
    <script>
        function openEditModal() {
    document.getElementById('editModal').classList.add('active');
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
}

        function previewImage(event) {
            const file = event.target.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profileDisplay').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    </script>
</body>
</html>