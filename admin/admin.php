
<?php

use PSpell\Dictionary;

session_start();
include('../database/db.php'); // Remove any trailing spaces or incorrect path


// Fetch student data
$query = "SELECT student.*, logincredentials.email 
          FROM student 
          LEFT JOIN logincredentials 
          ON student.id = logincredentials.student_id"; // Adjust column names if necessary
$result = mysqli_query($conn, $query);
$result1 = mysqli_query($conn, $query);


// Check for query success
if (!$result) {
    die("Error fetching data: " . mysqli_error($conn));
}
 if (!empty($_FILES['profileImage']['name'])) {
        $imageName = basename($_FILES['profileImage']['name']);
        $imagePath = 'images-data/' . $imageName;

        if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $imagePath)) {
            
            $imageQueryPart = ", student.image = '$imagePath'";
        } else {
            echo "Failed to upload image.";
            exit();
        }
    } else {
        $imageQueryPart = ""; 
    }

$count_query = "SELECT COUNT(*) AS student_count FROM student";
$count_result = mysqli_query($conn, $count_query);

if ($count_result) {
    $count_data = mysqli_fetch_assoc($count_result);
    $student_count = $count_data['student_count'];
} else {
    $student_count = 0; // Default to 0 if the query fails
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="../css/admindashboard.css">

</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="javascript:void(0);" data-section="dashboard" onclick="showSection('dashboard')">Dashboard</a></li>
                <li><a href="javascript:void(0);" data-section="dashboard" onclick="showSection('notification')">Notification</a></li>
                <li><a href="javascript:void(0);" data-section="student" onclick="showSection('student')">Students</a></li>
                <li><a href="javascript:void(0);" data-section="setting" onclick="showSection('setting')">Settings</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div id="dashboard" class="section-content">
                <h2>Dashboard</h2>
                  <div class="cards">
           <div class="card">
            <h3><?php echo $student_count; ?></h3>
            <p>Student</p>
        </div>

            <div class="card">
                <h3>on going</h3>
                <p>on going</p>
            </div>
            <div class="card">
                <h3>on going</h3>
                <p>on going </p>
            </div>
        </div>

        <!-- Tables -->
        <div class="tables">
            <div class="table-container">
                <center>
                    <h2>Student List</h2>
                </center>
    <table>
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Contact</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['age']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>No students found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

        </div>
            </div>
               <div id="notification" class="section-content" style="display: none;">
                <h2>notification Content</h2>
                notification
                <div class="table-container">
                <h5>Recent Contact Requests</h5>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" class="text-center">No data available</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            </div>

            <div id="student" class="section-content" style="display: none;">
                <h2>Student List</h2>
                <p>Manage Your students</p>

        <table>
        <thead>
            <tr>
                <th>Student Profile</th>
                <th>Student ID</th>
                <th>Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Contact</th>
                <th>Address</th>
                <th>Email</th>
                <th>Edit</th>
            </tr>
        </thead>
       <tbody>
    <?php
    if ($result && mysqli_num_rows($result1) > 0) {
        while ($row = mysqli_fetch_assoc($result1)) {
            echo "<tr>";
            
            // Display profile container with image or fallback
            echo "<td>";
            echo "<div class='profile-container'>";
            if (!empty($row['image']) && file_exists('../' . $row['image'])) {
                echo "<img src='../" . htmlspecialchars($row['image']) . "' style='width:120px; height:120px;' alt='Profile' class='profile-image' id='profileDisplay'>";
            } else {
                echo "<span>No Image</span>";
            }
            echo "<input type='file' id='profileImageUpload' name='profileImage' accept='image/*' onchange='previewImage(event)' hidden>";
            echo "</div>";
            echo "</td>";

            // Display other table columns
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname']) . "</td>";
            echo "<td>" . htmlspecialchars($row['age']) . "</td>";
            echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
            echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
            echo "<td>" . htmlspecialchars($row['address']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='9' class='text-center'>No students found</td></tr>";
    }
    ?>
</tbody>


    </table>

            </div>

            <div id="setting" class="section-content" style="display: none;">
                <h2>Settings Content</h2>
                <p>This is the content for managing settings.</p>
            </div>
        </main>
    </div>
    <script>
        function showSection(section) {
            const sections = document.querySelectorAll(".section-content");
            const links = document.querySelectorAll(".sidebar ul li a");
            sections.forEach(sec => sec.style.display = "none");
            links.forEach(link => link.classList.remove("active"));
            document.getElementById(section).style.display = "block";
            const activeLink = document.querySelector(`.sidebar ul li a[data-section="${section}"]`);
            if (activeLink) activeLink.classList.add("active");
        }
        window.onload = function () {
            showSection('dashboard'); 
        }
    </script>
</body>
</html>
