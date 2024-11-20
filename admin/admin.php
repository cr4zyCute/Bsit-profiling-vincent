<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Admin Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        /* Sidebar styling */
      .sidebar {
            background-color: #343a40;
            color: white;
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            padding-top: 20px;
         /* Hidden by default */
            transition: transform 0.3s ease-in-out;

        }

        .sidebar.active {
            transform: translateX(0); /* Visible when active */
        }

        .sidebar h4 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 15px 20px;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #495057;
        }


        /* Main content */
        .content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
            transition: margin-left 0.3s;
        }

        .content.shrink {
            margin-left: 0;
            width: 100%;
        }

        .welcome {
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .card {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 1rem;
            color: #6c757d;
        }

        /* Tables */
        .tables {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .table-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .table-container h5 {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f1f1f1;
        }

        table tr:hover {
            background-color: #f9f9f9;
        }

        /* Hamburger menu */
        .hamburger {
            display: none;
            cursor: pointer;
            font-size: 1.5rem;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
            color: #343a40;
        }

        /* Show hamburger menu on smaller screens */
        @media (max-width: 768px) {
            .hamburger {
                display: block;
            }

             .sidebar {
        transform: translateX(-100%); /* Hidden on smaller screens */
    }

    .sidebar.active {
        transform: translateX(0); /* Visible when active */
    }
        }
    </style>
</head>
<body>
    <!-- Hamburger Menu -->
    <div class="hamburger" id="hamburger">&#9776;</div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h4>Admin</h4>
        <a href="#dashboard">Dashboard</a>
        <a href="#orders">Orders</a>
        <a href="#teachers">Teachers</a>
        <a href="#students">Students</a>
        <a href="#reports">Reports</a>
        <a href="#messages">Messages</a>
        <a href="#settings">Settings</a>
    </div>

    <!-- Main Content -->
    <div class="content" id="content">
        <div class="welcome">Welcome, Admin!</div>

        <!-- Cards -->
        <div class="cards">
            <div class="card">
                <h3>2</h3>
                <p>Courses and Bundles</p>
            </div>
            <div class="card">
                <h3>2</h3>
                <p>Students</p>
            </div>
            <div class="card">
                <h3>1</h3>
                <p>Teachers</p>
            </div>
        </div>

        <!-- Tables -->
        <div class="tables">
            <div class="table-container">
                <h5>Recent Orders</h5>
                <table>
                    <thead>
                        <tr>
                            <th>Ordered By</th>
                            <th>Amount</th>
                            <th>Time</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Student User</td>
                            <td>₦0</td>
                            <td>1 hour ago</td>
                            <td><a href="#">View</a></td>
                        </tr>
                        <tr>
                            <td>Student User</td>
                            <td>₦10,000</td>
                            <td>1 hour ago</td>
                            <td><a href="#">View</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>

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
    </div>
        <script>
      const hamburger = document.getElementById('hamburger');
const sidebar = document.getElementById('sidebar');

hamburger.addEventListener('click', () => {
    sidebar.classList.toggle('active');
});

    </script>
</body>
</html>
