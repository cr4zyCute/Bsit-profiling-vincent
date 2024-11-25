<?php
include('../database/db.php');

$student = null;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "
        SELECT 
            student.*, 
            loginCredentials.email, 
            loginCredentials.password 
        FROM student 
        JOIN loginCredentials ON student.id = loginCredentials.student_id 
        WHERE student.id = $id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $student = mysqli_fetch_assoc($result);
    } else {
        echo "Student not found!";
        exit;
    }
} else {
    echo "Invalid student ID!";
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $middlename = mysqli_real_escape_string($conn, $_POST['middlename']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $imageQueryPart = ""; 
    if (!empty($_FILES['profileImage']['name'])) {
        $imageName = basename($_FILES['profileImage']['name']);
        $imagePath = '../images-data/' . $imageName;

        if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $imagePath)) {
            $imageQueryPart = ", student.image = '$imageName'";
        } else {
            echo "<script>alert('Failed to upload image.');</script>";
        }
    }

    $updateQuery = "
        UPDATE student 
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
            loginCredentials.password = '$password'
            $imageQueryPart
        WHERE student.id = '$id'
    ";

    if (mysqli_query($conn, $updateQuery)) {
        header("Location: ?id=$id&update=success");
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
    <title>Edit Student</title>
     <link rel="stylesheet" href="../css/adminaddStudent.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>
    <div class="back">
    <a href="admin.php">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
</div>

   <div class="type">
    <p>Updating Student</p>
</div>


   <form action="?id=<?php echo $student['id']; ?>" method="post" enctype="multipart/form-data">
    <div class="container">
        <div class="form-section">
            <div class="form-row">
                <div class="form-group">
                    <label for="firstname">Firstname:</label>
                  <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($student['firstname']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="middlename">Middlename:</label>
                   <input type="text" id="middlename" name="middlename" value="<?php echo htmlspecialchars($student['middlename']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="lastname">Lastname:</label>
                    <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($student['lastname']); ?>" required>

                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="age">Age:</label>
                    <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($student['age']); ?>" required>

                </div>
                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <input type="text" id="gender" name="gender" value="<?php echo htmlspecialchars($student['gender']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="phone">Contact Number:</label>
                    <input type="number" id="phone" name="phone" value="<?php echo htmlspecialchars($student['phone']); ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group full-width">
                    <label for="address">Address:</label>
                    <input type="address" id="address" name="address" value="<?php echo htmlspecialchars($student['address']); ?>" required>
                </div>
            </div>
        </div>

      <div class="profile-section">
<div class="profile-pic" id="" >
   <?php if (!empty($student['image'])) { ?>
      <img 
         src="<?php 
            $imagePath = '../images-data/' . $student['image']; 
            echo file_exists($imagePath) ? htmlspecialchars($imagePath) : '../' . htmlspecialchars($student['image']); 
         ?>" 
         class="profile-image" 
         id="profileDisplay" 
         alt="Profile"
      >
   <?php } else { ?>
      <img src="../images-data/default-profile.png" class="profile-image" profileDisplay alt="Default Profile">
   <?php } ?>
   <label for="profileImage" class="edit-btn">
      <i class="fa-solid fa-pen"></i>
   </label>
   <input type="file" id="profileImage" name="profileImage" accept="image/*" style="display: none;">
</div>
    <div class="login-section">
        <div class="form-group">
            <label for="email">Email:</label>
           <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>">
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
                    <div style="position: relative; display: inline-block;">
                        <input type="password" id="password" name="password" 
                            value="<?php echo htmlspecialchars($student['password']); ?>" required 
                            style="padding-right: 30px;">
                        <i class="fa-solid fa-eye-slash" id="togglePassword" 
                            style="position: absolute; right: 5px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
                    </div>
        </div>
        <button type="submit" name="submit" class="register-btn">Update</button>
    </div>
</div>

    </div>
</form>

    <section class="modal-section">
    <span class="overlay"></span>
    <div class="modal-box">
        <i class="fa-regular fa-circle-check"></i>
        <h2>Success</h2>
        <h3>You have successfully updated the student!.</h3>
        <div class="buttons">
            <a href="admin.php">
            <button class="close-btn">OK, Close</button>
            </a>
        </div>
    </div>
</section>

<script>

document.getElementById('profileImage').addEventListener('change', previewImage);

function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('profileDisplay').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}


         const passwordInput = document.getElementById('password');
    const togglePasswordIcon = document.getElementById('togglePassword');

    togglePasswordIcon.addEventListener('click', () => {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        togglePasswordIcon.classList.toggle('fa-eye');
        togglePasswordIcon.classList.toggle('fa-eye-slash');
    });

    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const section = document.querySelector(".modal-section"),
              overlay = document.querySelector(".overlay"),
              closeBtn = document.querySelector(".close-btn");

        if (urlParams.get('update') === 'success') {
            section.classList.add("active");
        }

        overlay.addEventListener("click", () => section.classList.remove("active"));
        closeBtn.addEventListener("click", () => section.classList.remove("active"));
        window.history.replaceState({}, document.title, window.location.pathname);
    });
</script>

</body>
</html>
