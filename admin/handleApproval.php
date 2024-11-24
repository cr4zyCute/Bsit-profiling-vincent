<?php
include_once '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $approvalId = $_POST['approval_id'];
    $action = $_POST['action'];

    if ($action === 'approve' && isset($_FILES['approval_picture'])) {
        $uploadDir = __DIR__ . '/../uploads/approval_pictures/';
        $webPath = 'uploads/approval_pictures/';

        // Ensure the upload directory exists
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileExtension = strtolower(pathinfo($_FILES['approval_picture']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($fileExtension, $allowedExtensions)) {
            die("Invalid file type. Only JPG, PNG, and GIF are allowed.");
        }

        // Generate a unique file name
        $fileName = uniqid() . '.' . $fileExtension;
        $uploadPath = $uploadDir . $fileName;
        $dbFilePath = $webPath . $fileName;

        // Move uploaded file
        if (move_uploaded_file($_FILES['approval_picture']['tmp_name'], $uploadPath)) {
            $stmt = $conn->prepare("
                UPDATE approvals 
                SET status = 'approved', picture = ? 
                WHERE id = ?
            ");
            $stmt->bind_param('si', $dbFilePath, $approvalId);

            if ($stmt->execute()) {
                echo "Approval processed successfully with picture.";
            } else {
                echo "Failed to update approval in database: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Failed to move uploaded file. Check directory permissions.";
        }
    } elseif ($action === 'reject') {
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
