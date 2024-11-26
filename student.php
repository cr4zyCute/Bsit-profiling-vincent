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
        $imageQueryPart = ""; 
        $imageName = basename($_FILES['profileImage']['name']);
        $password = $_POST['password'];
        $imagePath = 'images-data/' . $imageName;

$imageQueryPart = ""; 
if (!empty($_FILES['profileImage']['name'])) {
    $imageName = basename($_FILES['profileImage']['name']);
    $imagePath = 'images-data/' . $imageName;

    if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $imagePath)) {
        $imageQueryPart = ", student.image = '$imageName'";
    } else {
        echo "Failed to upload image.";
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
        loginCredentials.email = '$email',
        loginCredentials.password = '$password'
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
    
    $approvalQuery = "SELECT picture FROM approvals WHERE student_id = '$student_id' ORDER BY created_at DESC LIMIT 1";
    $approvalResult = mysqli_query($conn, $approvalQuery);

    $approvalPicture = null;

    if ($approvalResult && mysqli_num_rows($approvalResult) > 0) {
        $approvalData = mysqli_fetch_assoc($approvalResult);
        $approvalPicture = $approvalData['picture'];
    }
    if (!isset($_SESSION['student_id'])) {
        header('Location: login.php'); 
        exit();
    }
    $student_id = $_SESSION['student_id'];
    $statusQuery = "SELECT status FROM approvals WHERE student_id = $student_id ORDER BY created_at DESC LIMIT 1";
    $statusResult = mysqli_query($conn, $statusQuery);
    $approvalStatus = null;

        if ($statusResult && mysqli_num_rows($statusResult) > 0) {
            $statusRow = mysqli_fetch_assoc($statusResult);
            $approvalStatus = $statusRow['status'];
        
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
            <button onclick="openImageModal()" class="view-btn">Schedule</button>
                <div>
                    <!-- <form action="./admin/sendApproval.php" method="POST">
                        <button class="email-btn"type="submit" name="sendApproval" >Send Approval<i class="fa-regular fa-paper-plane"></i></button>
                                </form> -->
                    <div class="approval-section">
                        <?php
                            if ($approvalStatus === 'pending') {
                                echo "<button disabled>Waiting for Approval</button>";
                            } elseif ($approvalStatus === 'approved') {
                                echo "<p id='approved' >Your request has been approved.</p>";
                            } else {
                                echo "
                                <form action='./admin/sendApproval.php' method='POST'>
                                    <button class='email-btn'type='submit' name='sendApproval' >Send Approval<i class='fa-regular fa-paper-plane'></i></button>
                                </form>
                                ";
                            }
                        ?>
                    </div>  
                </div>
            <button onclick="openEditModal()" class="settings-btn"><i class="fa-solid fa-pen-to-square"></i></button>
                <a href="logout.php">
                    <button class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i></button>
                </a>
        </div>
    </header>
    <div class="modal-overlay" id="viewImageModal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeImageModal()">X</span>
                <div class="image-container">
                    <?php
                        $approvalQuery = "SELECT picture FROM approvals WHERE student_id = '$student_id' ORDER BY created_at DESC LIMIT 1";
                        $approvalResult = mysqli_query($conn, $approvalQuery);

                        if ($approvalResult && mysqli_num_rows($approvalResult) > 0) {
                            $approvalData = mysqli_fetch_assoc($approvalResult);
                            $approvalPicture = $approvalData['picture']; 

                            echo '<p>' . (file_exists($approvalPicture) ? 'You are now Enrolled' : '') . '</p>';
                    
                            if (!empty($approvalPicture) && file_exists($approvalPicture)) {
                    
                                $webPath = str_replace('../', '', $approvalPicture);
                                echo '<img src="' . htmlspecialchars($webPath) . '?v=' . time() . '" style="width:650px; height:600px;" alt="Admin Sent Image">';
                            } else {
                                echo '<p>No Schedule yet! Please Wait For your Approval</p>';
                            }
                        } else {
                             echo '<p>No Schedule yet! Please Wait For your Approval</p>';
                        }
                    ?>
                </div>
        </div>
    </div>
    <div class="id-card">
        <div class="profile-container">
     <div class="profile-container">
        <?php
        $imagePath = 'images-data/' . htmlspecialchars($student['image']); 
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
                    <div class="profile-picture" >
                        <?php
                            $imagePath = 'images-data/' . htmlspecialchars($student['image']); 
                                if (!empty($student['image']) && file_exists($imagePath)) {
                                    echo '<img src="' . $imagePath . '?v=' . time() . '" style="width:120px; height:120px;" alt="Profile Image" id="profileDisplay" >';
                                } else {
                                    echo '<img src="images-data/default-image.png" style="width:120px; height:120px;" alt="Default Image" id="profileDisplay" >';
                                }
                            ?>                    
                            <input type="file" id="profileImageUpload" name="profileImage" accept="image/*" onchange="previewImage(event)" hidden>
                        <div class="edit-btn" onclick="document.getElementById('profileImageUpload').click()"><i class="fa-solid fa-pen"></i></div>
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

                      <div>
                    <label for="password">Password:</label>
                    <div style="position: relative; display: inline-block;">
                        <input type="password" id="password" name="password" 
                            value="<?php echo htmlspecialchars($student['password']); ?>" required 
                            style="padding-right: 30px;">
                        <i class="fa-solid fa-eye-slash" id="togglePassword" 
                            style="position: absolute; right: 5px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
                    </div>
                </div>
                </div>

                <button type="submit" class="save-btn">Save Changes</button>
            </div>
        </form>
    </div>

  

<footer>
    <p>&copy; 2024 BSIT. All rights reserved.</p>
        <div class="footer-links">
            <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a>
        </div>
</footer>

    <script src="js/editprofile.js"></script>
    <script>

    document.addEventListener('DOMContentLoaded', () => {
            const errorMessage = document.getElementById('approved');
            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.style.display = 'none';
                }, 5000); 
            }
        });

    const passwordInput = document.getElementById('password');
    const togglePasswordIcon = document.getElementById('togglePassword');
    togglePasswordIcon.addEventListener('click', () => {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        togglePasswordIcon.classList.toggle('fa-eye');
        togglePasswordIcon.classList.toggle('fa-eye-slash');
    });

        function openImageModal() {
            document.getElementById('viewImageModal').classList.add('active');
        }

        function closeImageModal() {
            document.getElementById('viewImageModal').classList.remove('active');
        }


        function openEditModal() {
             document.getElementById('editModal').classList.add('active');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('active');
        }
        function closeModal() {
            document.querySelector('.modal-section.success').style.display = 'none';
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