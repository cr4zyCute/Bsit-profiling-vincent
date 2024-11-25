<?php
session_start();
require '../database/db.php';

if (!isset($_SESSION['student_id'])) {
    echo "Student not logged in.";
    exit();
}

$student_id = $_SESSION['student_id'];

$checkQuery = "SELECT * FROM approvals WHERE student_id = $student_id AND status = 'pending'";
$result = mysqli_query($conn, $checkQuery);

if (mysqli_num_rows($result) > 0) {
   $message = "Approval request sent successfully.";
    exit();
}

$insertQuery = "INSERT INTO approvals (student_id) VALUES ($student_id)";
if (mysqli_query($conn, $insertQuery)) {
    echo "";
} else {
    echo "Error submitting approval: " . mysqli_error($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approval Status</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: #fff;
        }
        .message-container {
            background: #fff;
            color: #333;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 90%;
            max-width: 400px;
        }
        .message-container h2 {
            margin: 0 0 20px;
            font-size: 24px;
        }
        .message-container p {
            font-size: 16px;
            margin-bottom: 30px;
        }
        .message-container button {
            background: #2575fc;
            color: #fff;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .message-container button:hover {
            background: #1a5bb8;
        }
    </style>
</head>
<body>
    <div class="message-container">

         <i class="fa-regular fa-circle-check" style="font-size: 50px; color:green;"></i>
        <h2>Success</h2>
        <p>Approval request sent successfully.</p>
        <a href="../student.php">
        <button type="submit">OK</button>
        </a>
    </div>
</body>
</html>