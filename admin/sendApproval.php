<?php
session_start();
require '../database/db.php';

// Ensure the student is logged in
if (!isset($_SESSION['student_id'])) {
    echo "Student not logged in.";
    exit();
}

$student_id = $_SESSION['student_id'];

// Check if the student has already submitted an approval request
$checkQuery = "SELECT * FROM approvals WHERE student_id = $student_id AND status = 'pending'";
$result = mysqli_query($conn, $checkQuery);

if (mysqli_num_rows($result) > 0) {
    echo "Approval request already submitted.";
    exit();
}

// Insert a new approval request
$insertQuery = "INSERT INTO approvals (student_id) VALUES ($student_id)";
if (mysqli_query($conn, $insertQuery)) {
    echo "Approval request sent successfully.";
} else {
    echo "Error submitting approval: " . mysqli_error($conn);
}
?>
