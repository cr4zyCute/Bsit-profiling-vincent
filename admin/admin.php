<?php
session_start();
include('../database/db.php'); 

if (!isset($_SESSION['admin_email'])) {

    header('Location: adminlogin.php');
    exit();
}
$query = "
    SELECT student.*, 
           logincredentials.email, 
           approvals.status 
    FROM student
    LEFT JOIN logincredentials 
    ON student.id = logincredentials.student_id
    LEFT JOIN approvals 
    ON student.id = approvals.student_id";
          
$result = mysqli_query($conn, $query);
$result1 = mysqli_query($conn, $query);

if (!$result) {
    die("Error fetching data: " . mysqli_error($conn));
}
 if (!empty($_FILES['profileImage']['name'])) {
        $imageName = basename($_FILES['profileImage']['name']);
        $imagePath = '../images-data/' . $imageName;

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
    $student_count = 0;
}
$count_queryPending = "SELECT COUNT(*) AS pending_count FROM approvals WHERE status = 'pending'";
$count_resultPending = $conn->query($count_queryPending);

$pending_count = 0; 
if ($count_resultPending && $count_resultPending->num_rows > 0) {
    $row = $count_resultPending->fetch_assoc();
    $pending_count = $row['pending_count'];
} else {
  
    echo "Error fetching pending count: " . $conn->error;
}

$count_queryApprove = "SELECT COUNT(*) AS approved_count FROM approvals WHERE status = 'approved'";
$count_resultApprove = $conn->query($count_queryApprove);

$approved_count = 0; // Default value
if ($count_resultApprove && $count_resultApprove->num_rows > 0) {
    $row = $count_resultApprove->fetch_assoc();
    $approved_count = $row['approved_count'];
} else {
    // Output an error if the query fails
    echo "Error fetching approved count: " . $conn->error;
}



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
        header("Location: admin.php");
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $student_id = $_POST['student_id'];

    mysqli_begin_transaction($conn);

    try {
        $deleteApprovalsQuery = "DELETE FROM approvals WHERE student_id = ?";
        $stmtApprovals = mysqli_prepare($conn, $deleteApprovalsQuery);
        mysqli_stmt_bind_param($stmtApprovals, "i", $student_id);
        mysqli_stmt_execute($stmtApprovals);
        mysqli_stmt_close($stmtApprovals);

        $deleteStudentQuery = "DELETE FROM student WHERE id = ?";
        $stmtStudent = mysqli_prepare($conn, $deleteStudentQuery);
        mysqli_stmt_bind_param($stmtStudent, "i", $student_id);
        mysqli_stmt_execute($stmtStudent);
        mysqli_stmt_close($stmtStudent);

        mysqli_commit($conn);

        $_SESSION['delete_success'] = true; // Set success flag
        header("Location: admin.php");
        exit();
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "Error deleting student: " . $e->getMessage();
    }
}

// Check for delete success and pass data to modal
$showSuccessModal = false;
if (isset($_SESSION['delete_success']) && $_SESSION['delete_success'] === true) {
    $showSuccessModal = true;
    unset($_SESSION['delete_success']); // Clear session variable
}   


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add-student'])) {
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $middlename = mysqli_real_escape_string($conn, $_POST['middlename']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $imagePath = '';

    if (!empty($_FILES['profileImage']['name'])) {
        $imageName = basename($_FILES['profileImage']['name']);
        $imagePath = '../images-data/' . $imageName;

        if (!move_uploaded_file($_FILES['profileImage']['tmp_name'], $imagePath)) {
            echo "Failed to upload image.";
            exit();
        }
    }

    $insertStudentQuery = "INSERT INTO student (firstname, middlename, lastname, age, gender, phone, address, image)
                           VALUES ('$firstname', '$middlename', '$lastname', '$age', '$gender', '$phone', '$address', '$imagePath')";

    if (mysqli_query($conn, $insertStudentQuery)) {
        $student_id = mysqli_insert_id($conn); 
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $insertCredentialsQuery = "INSERT INTO logincredentials (student_id, email, password)
                                   VALUES ('$student_id', '$email', '$hashedPassword')";

        if (mysqli_query($conn, $insertCredentialsQuery)) {
            header("Location: admin.php"); 
            exit();
        } else {
            echo "Error inserting login credentials: " . mysqli_error($conn);
        }
    } else {
        echo "Error inserting student: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="icon" href="../images/bsitlogo.png">
    <link rel="stylesheet" href="../css/admindashboard.css">
     <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="javascript:void(0);" data-section="dashboard" onclick="showSection('dashboard')">Dashboard</a></li>
                <li><a href="javascript:void(0);" data-section="dashboard" onclick="showSection('notification')">Notification</a></li>
                <li><a href="javascript:void(0);" data-section="student" onclick="showSection('student')">Students</a></li>
                <li><a href="javascript:void(0);" data-section="setting" onclick="showSection('setting')">Settings</a></li>
                <li><a href="adminlogout.php">Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
       
            <div id="dashboard" class="section-content">
                <h2>Dashboard</h2>
                  <div class="cards">
           <div class="card">
            <h3><?php echo $student_count; ?></h3>
            <p>Student</p>
        </div>

            <div class="card">
            <h3><?php echo $pending_count; ?></h3>
            <p>Pending Approval</p>
        </div>

            <div class="card">
               <h3><?php echo $approved_count; ?></h3>
                <p>Enrolled</p>
            </div>
        </div>
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
                <th>Email</th>
                <th>Status</th>
               
            </tr>
        </thead>
        <tbody id="student-table-body" >
            <?php
            $sql = "SELECT * FROM student";
                $query = mysqli_query($conn,$sql);
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname']) . "</td>";
                     echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status'] ?? 'No Status') . "</td>"; // Show status or "No Status"
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
    <h2>Approval Requests</h2>
    <div class="table-container">
        <h5>Recent Approval Requests</h5>
        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Request Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    <?php
    $approvalQuery = "
        SELECT approvals.id AS approval_id, approvals.status, approvals.created_at, 
            student.firstname, student.middlename, student.lastname, student.id AS student_id, 
            logincredentials.email 
        FROM approvals 
        JOIN student ON approvals.student_id = student.id 
        JOIN logincredentials ON student.id = logincredentials.student_id
        ORDER BY approvals.created_at DESC";
    $approvalResult = mysqli_query($conn, $approvalQuery);

    if ($approvalResult && mysqli_num_rows($approvalResult) > 0) {
        while ($row = mysqli_fetch_assoc($approvalResult)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
            echo "<td>" . ucfirst(htmlspecialchars($row['status'])) . "</td>";
            echo "<td>";
            if (strtolower($row['status']) === 'pending') {
                echo "<form method='POST' action='handleApproval.php' enctype='multipart/form-data' style='display:inline-block;'>
                        <input type='hidden' name='approval_id' value='" . htmlspecialchars($row['approval_id']) . "'>
                        <input type='file' name='approval_picture' accept='image/*' required>
                        <button type='submit' name='action' value='approve'>Approve</button>
                    </form>
                    <form method='POST' action='handleApproval.php' style='display:inline-block;'>
                        <input type='hidden' name='approval_id' value='" . htmlspecialchars($row['approval_id']) . "'>
                        <button type='submit' name='action' value='reject'>Reject</button>
                    </form>";
            } elseif (strtolower($row['status']) === 'approved') {
                echo '<span style="color: green; font-weight: bold;"><i class="fa-solid fa-check"></i></span>';
            } elseif (strtolower($row['status']) === 'rejected') {
                 echo '<span style="color: red; font-weight: bold;"><i class="fa-solid fa-xmark"></i></span>';
            }

            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6' class='text-center'>No approval requests found</td></tr>";
    }
    ?>


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
                <th>Student ID</th>
                <th>Student Profile</th>
                <th>Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Contact</th>
                <th>Address</th>
                <th>Email</th>
                <th>Edit</th>
            </tr>
        </thead>
         <tbody id="student-table-body-student-section">
            <div class="add-student-button">
                <a href="adminaddStudent.php">
                    <button>Add New Student</button>
                </a>
            </div>

            <div class="search-container">
                <input type="text" id="search-input-student-section" placeholder="Search students">
                <button id="clear-btn-student-section" style="display: none;">✖</button>
                <button id="search-btn-student-section"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
            </div>
            
                    <?php while ($row = mysqli_fetch_assoc($result1)) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                          <td>
                                <?php if (!empty($row['image'])) { ?>
                            <img src="<?php $imagePath = '../images-data/' . $row['image'];echo file_exists($imagePath) ? htmlspecialchars($imagePath) : '../' . htmlspecialchars($row['image']); ?>" class="profile-image" id="profileDisplay" alt="Profile" style="width:120px; height:120px;">                                     
                                <?php } ?>
                            </td>
                            <td><?php echo $row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname']; ?></td>
                            <td><?php echo $row['age']; ?></td>
                            <td><?php echo $row['gender']; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td><?php echo $row['address']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td>
                           
                            <form method="POST" action="adminUpdateStudent.php">
                                    <a href="adminUpdateStudent.php?id=<?php echo $row['id']; ?>" class="edit-btn">
                                    <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                        <button type="button" onclick="openModal(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        </a>
                                </form>
                                <a href="#modal-section" class="deletebtn">
                                <form method="POST" action="admin.php" onsubmit="return confirmDelete(event);">
                                    <input  type="hidden" name="student_id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button  type="submit">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </a>

                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
    </table>
   

            </div>

            <div id="setting" class="section-content" style="display: none;">
                <h2>Settings Content</h2>
                <p>This is the content for managing settings.</p>
            </div>

        </main>
    </div>

    <section id="modal-section add-student" class="modal-section" style="display: none;">
    <span class="overlay" onclick="closeModal();"></span>
    <div class="modal-box">
      <div id="add-student" class="section-content" style="display: none;">
    <h2>Add New Student</h2>
    <form action="admin.php" method="POST" enctype="multipart/form-data">
        <label for="firstname">First Name:</label>
        <input type="text" id="firstname" name="firstname" required>
        
        <label for="middlename">Middle Name:</label>
        <input type="text" id="middlename" name="middlename">
        
        <label for="lastname">Last Name:</label>
        <input type="text" id="lastname" name="lastname" required>
        
        <label for="age">Age:</label>
        <input type="number" id="age" name="age" required>
        
        <label for="gender">Gender:</label>
        <select id="gender" name="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
        
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" required>
        
        <label for="address">Address:</label>
        <textarea id="address" name="address" required></textarea>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        
        <label for="profileImage">Profile Image:</label>
        <input type="file" id="profileImage" name="profileImage">
        
        <button type="submit" name="add-student">Add Student</button>
    </form>
</div>
    </div>
</section>

<section id="modal-section" class="modal-section" style="display: none;">
    <span class="overlay" onclick="closeModal();"></span>
    <div class="modal-box">
      <i class="fa-solid fa-circle-exclamation" style="font-size: 50px; color:red;" ></i>
        <h2>Notice</h2>
        <h3>Are you sure you want to delete this student?</h3>
        <div class="buttons">
            <button class="close-btn" onclick="confirmDeletion();">OK</button>
            <button class="close-btn" onclick="closeModal();">Cancel</button>
        </div>
    </div>
</section>

<section class="modal-section success" style="display: none;" data-show="<?= $showSuccessModal ? 'true' : 'false'; ?>">
    <span class="overlay" onclick="closeModal();"></span>
    <div class="modal-box">
        <i class="fa-regular fa-circle-check" style="font-size: 50px; color:green;"></i>
        <h2>Success</h2>
        <h3>Deleted Successfully!</h3>
        <div class="buttons">
            <a href="admin.php">
                <button class="close-btn" onclick="closeModal();">OK</button>
            </a>
        </div>
    </div>
</section>

    
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


document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-input-student-section');
    const clearButton = document.getElementById('clear-btn-student-section');
    const tableBody = document.getElementById('student-table-body-student-section');
    const originalTableContent = tableBody.innerHTML;
    searchInput.addEventListener('input', () => {
        clearButton.style.display = searchInput.value ? 'inline' : 'none';
        if (!searchInput.value.trim()) {
            tableBody.innerHTML = originalTableContent; 
        }
    });
 clearButton.addEventListener('click', () => {
        searchInput.value = ''; 
        clearButton.style.display = 'none'; 
        tableBody.innerHTML = originalTableContent;
        searchInput.focus(); 
    });
});

$('#search-input-student-section').on('keyup', function () {
    var query = $(this).val().trim();

    if (query) {
        $.ajax({
            url: 'searchStudent.php',
            method: 'GET',
            data: { query: query },
            success: function (response) {
                $('#student-table-body-student-section').html(response);
            },
            error: function () {
                console.error('Error fetching search results.');
            }
        });
    } else {
        const tableBody = document.getElementById('student-table-body-student-section');
        tableBody.innerHTML = originalTableContent;
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const successModal = document.querySelector(".modal-section.success");
    if (successModal && successModal.dataset.show === "true") {
        successModal.style.display = "flex";
    }
});

function closeModal() {
    const successModal = document.querySelector(".modal-section.success");
    if (successModal) {
        successModal.style.display = "none";
    }
}



        function closeModal() {
    const modals = document.querySelectorAll('.modal-section');
    modals.forEach(modal => modal.style.display = 'none');
    formToSubmit = null;
}

        
    let formToSubmit = null;

    function confirmDelete(event) {
        event.preventDefault();
        formToSubmit = event.target;
        document.getElementById('modal-section').style.display = 'flex';
        return false;
    }

    function confirmDeletion() {
        if (formToSubmit) {
            formToSubmit.submit();
        }
        closeModal();
    }

    function closeModal() {
        document.getElementById('modal-section').style.display = 'none';
        formToSubmit = null;
    }
        function openModal(student) {
        showSection('edit-student');
        document.getElementById('edit-student-id').value = student.id;
        document.getElementById('edit-firstname').value = student.firstname;
        document.getElementById('edit-middlename').value = student.middlename || "";
        document.getElementById('edit-lastname').value = student.lastname;
        document.getElementById('edit-age').value = student.age;
        document.getElementById('edit-gender').value = student.gender;
        document.getElementById('edit-phone').value = student.phone;
        document.getElementById('edit-address').value = student.address;
    }
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
