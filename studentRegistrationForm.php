
<?php
require 'database/db.php';
session_start(); 

if (!empty($_SESSION['student_id'])) {
    $student_id = $_SESSION['student_id'];

    $query = "
        SELECT student.*, loginCredentials.email 
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


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User</title>
    <link rel="stylesheet" href="css/studentProfile.css">
    <link rel="icon" href="image/bsitlogo.png">
</head>
<body>
    <div class="top-buttons">
        <button class="settings-btn">⚙️</button>
        <a href="loginForm.php">
        <button class="logout-btn">🔄</button></a>
    </div>

    <div class="id-card">
        <div class="profile-container">
            <?php
    $res = mysqli_query($conn, "SELECT image FROM student");
    while($row = mysqli_fetch_assoc($res)) {
        $imagePath = "images-data/" . $row['image'];
    ?>
        <img src="<?php echo $imagePath; ?>" alt="Profile" class="profile-image" id="profileImage">
    <?php } ?>
        </div>
        <p class="id-number">ID Number:  <?php echo $student['id'] ?></p>
        <div class="card-body">
            <div class="left-section info-box">
                <p><strong>Name:</strong> <?php echo $student['firstname'] ?></p>
                <p><strong>Last Name:</strong>  <?php echo $student['lastname'] ?></p>
                <p><strong>Age:</strong>  <?php echo $student['age'] ?></p>
                <p><strong>Gender:</strong>  <?php echo $student['gender'] ?></p>
            </div>
            <div class="separator"></div>
            <div class="right-section info-box">
                <p><strong>Address:</strong>  <?php echo $student['address'] ?></p>
                <p><strong>Email:</strong> <?php echo $student['email'] ?></p>
                <p><strong>Contact:</strong>  <?php echo $student['phone'] ?></p>
            </div>
        </div>
    </div>
</body>
</html>