<?php
// Include your database connection file
include_once '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $approvalId = $_POST['approval_id'];
    $action = $_POST['action'];

    if ($action === 'approve' && isset($_FILES['approval_picture'])) {
        $uploadDir = 'uploads/approval_pictures/';
        $uploadFile = $uploadDir . basename($_FILES['approval_picture']['name']);

        // Ensure the upload directory exists
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Move the uploaded file to the desired location
        if (move_uploaded_file($_FILES['approval_picture']['tmp_name'], $uploadFile)) {
            // Update the database with the approval and the picture path
            $stmt = $conn->prepare("
                UPDATE approvals 
                SET status = 'approved', picture = ? 
                WHERE id = ?
            ");
            $stmt->bind_param('si', $uploadFile, $approvalId);

            if ($stmt->execute()) {
                echo "Approval processed successfully with picture.";
            } else {
                echo "Failed to update approval: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Failed to upload picture.";
        }
    } elseif ($action === 'reject') {
        // Handle rejection case
        $stmt = $conn->prepare("
            UPDATE approvals 
            SET status = 'rejected' 
            WHERE id = ?
        ");
        $stmt->bind_param('i', $approvalId);

        if ($stmt->execute()) {
            echo "Approval rejected successfully.";
        } else {
            echo "Failed to reject approval: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
