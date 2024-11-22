<?php
include('../database/db.php');

if (isset($_GET['query'])) {
    $search = mysqli_real_escape_string($conn, $_GET['query']);
    $query = "
       SELECT student.*, logincredentials.email 
        FROM student 
        LEFT JOIN logincredentials ON student.id = logincredentials.student_id
        WHERE CONCAT(firstname, ' ', middlename, ' ', lastname) LIKE '%$search%' 
           OR CONCAT(firstname, ' ', lastname) LIKE '%$search%' 
           OR firstname LIKE '%$search%'
           OR middlename LIKE '%$search%'
           OR lastname LIKE '%$search%'
           OR email LIKE '%$search%'
           OR phone LIKE '%$search%'
           OR address LIKE '%$search%'";    

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['id']) . "</td>
                    <td>" . htmlspecialchars($row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname']) . "</td>
                    <td>" . htmlspecialchars($row['age']) . "</td>
                    <td>" . htmlspecialchars($row['gender']) . "</td>
                    <td>" . htmlspecialchars($row['phone']) . "</td>
                    <td>" . htmlspecialchars($row['address']) . "</td>
                   
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='7' class='text-center'>No results found</td></tr>";
    }
} else {
    echo "<tr><td colspan='7' class='text-center'>Invalid query</td></tr>";
}
?>
