<?php
include('../database/db.php');

// Fetch the student data based on the provided ID
$student = null;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM student WHERE id = $id";
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

// Update logic
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
        header("Location: adminUpdateStudent.php?id=$id&update=success");
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
    <title>Edit Student</title>
    <link rel="stylesheet" href="../css/adminUpdateStudent.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>
    <div class="back">
    <a href="admin.php">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
</div>


   <form action="?id=<?php echo $student['id']; ?>" method="post" enctype="multipart/form-data">

        <div class="container">
            <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
            
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
                        <select id="gender" name="gender" required>
                            <option value="Male" <?php echo $student['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo $student['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                            <option value="Others" <?php echo $student['gender'] === 'Others' ? 'selected' : ''; ?>>Others</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="phone">Contact Number:</label>
                        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($student['phone']); ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($student['address']); ?>" required>
                    </div>
                </div>
            </div>

            <div class="profile-section">
                <div class="profile-pic">
                    <label for="profileImage">Profile Picture:</label>
                    <input type="file" id="profileImage" name="profileImage" accept="image/*">
                    <?php if (!empty($student['image'])) { ?>
                        <img src="<?php echo $student['image']; ?>" alt="Profile Picture" style="max-width: 100px; margin-top: 10px;">
                    <?php } ?>
                </div>
            </div>
            
            <button type="submit" name="update" class="update-btn">Update</button>
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
