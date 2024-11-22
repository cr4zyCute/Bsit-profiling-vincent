<?php
session_start();
include('../database/db.php'); 

if (!isset($_SESSION['admin_email'])) {

    header('Location: adminlogin.php');
    exit();
}
$query = "SELECT student.*, logincredentials.email 
          FROM student 
          LEFT JOIN logincredentials 
          ON student.id = logincredentials.student_id"; 
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

    $deleteQuery = "DELETE FROM student WHERE id = ?";
    $stmt = mysqli_prepare($conn, $deleteQuery);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $student_id);
        if (mysqli_stmt_execute($stmt)) {
             $_SESSION['delete_success'] = true;
            header("Location: admin.php");
            exit();
        } else {
            echo "Error deleting student: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Failed to prepare the delete query.";
    }
}

if (isset($_SESSION['delete_success']) && $_SESSION['delete_success'] === true) {
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelector('.modal-section.success').style.display = 'flex';
            });
          </script>";
    unset($_SESSION['delete_success']);
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
                <h3>on going</h3>
                <p>on going</p>
            </div>
            <div class="card">
                <h3>on going</h3>
                <p>on going </p>
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
                <th>Age</th>
                <th>Gender</th>
                <th>Contact</th>
                <th>Address</th>
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
                            <th>Approval</th>
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
            <div class="search-container">
    <input type="text" id="search-input-student-section" placeholder="Search students in this section...">
    <button id="search-btn-student-section"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
    
</div>
            
                    <?php while ($row = mysqli_fetch_assoc($result1)) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                          <td>
                                <?php if (!empty($row['image'])) { ?>
                                    <img src="<?php echo file_exists('../images-data/' . $row['image']) ? '../images-data/' . htmlspecialchars($row['image']) : htmlspecialchars($row['image']); ?>" style="width:120px; height:120px;">
                                    
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
                                                <li><a href="adminUpdateStudent.php?id=<?php echo $row['id']; ?>" class="edit-btn">
                                                    <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                                <button type="button" onclick="openModal(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                                   <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                </a></li>
                                </form>


                                <a href="#modal-section">
                                <form method="POST" action="admin.php" onsubmit="return confirmDelete(event);">
                                    <input type="hidden" name="student_id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button class="deletebtn" type="submit">
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

<section class="modal-section success" style="display: none;">
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

    $('#search-input-student-section').on('keyup', function () {
        var query = $(this).val();
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
    });


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
