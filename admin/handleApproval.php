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
                echo "";
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
        <p>Approved successfully.</p>
        <a href="admin.php">
        <button type="submit">OK</button>
        </a>
    </div>
</body>
</html>
