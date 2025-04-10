<?php
session_start();
include('dbms.php'); // Include database connection

// Check if admin is logged in
// if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
//     header('Location: admin_login.php');
//     exit;
// }

// Handle Deletions
if (isset($_GET['delete_route'])) {
    $route_id = htmlspecialchars($_GET['delete_route']);
    $conn->query("DELETE FROM route WHERE route_id = '$route_id'");
}

if (isset($_GET['delete_bus'])) {
    $bus_id = htmlspecialchars($_GET['delete_bus']);
    $conn->query("DELETE FROM bus WHERE bus_id = '$bus_id'");
}

if (isset($_GET['delete_schedule'])) {
    $schedule_id = htmlspecialchars($_GET['delete_schedule']);
    $conn->query("DELETE FROM schedule WHERE schedule_id = '$schedule_id'");
}

if (isset($_GET['delete_ticket'])) {
    $ticket_id = htmlspecialchars($_GET['delete_ticket']);
    $conn->query("DELETE FROM tickets WHERE ticket_id = '$ticket_id'");
}

// Fetch required data for tables
$routes = $conn->query("SELECT route_id, CONCAT(source_location, ' - ', destination_location) AS route FROM route");
$buses = $conn->query("SELECT bus_id, CONCAT('Bus ', bus_id, ' (Seats: ', available_seats, ')') AS bus FROM bus");
$schedules = $conn->query("SELECT * FROM schedule");
$tickets = $conn->query("SELECT ticket_id, CONCAT('Ticket ', ticket_id) AS ticket FROM ticket");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Schedule - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <<style>
/* General Styles */
body {
    font-family: 'Georgia', serif; /* Classic serif font for an old-fashioned look */
    background-color: #f4f4f4; /* Soft gray background */
    color: #333; /* Dark gray text for readability */
}

.container {
    margin-top: 50px;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
}

h1 {
    color: #3e3e3e; /* Dark gray for the title */
    font-weight: 600;
    font-size: 32px;
    margin-bottom: 20px;
    text-align: center;
    font-family: 'Georgia', serif; /* Classic serif font for the title */
}

/* Buttons */
.btn {
    font-weight: 600;
    border-radius: 5px;
    padding: 10px 20px;
    font-size: 16px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.btn-danger {
    background-color: #b03a2e; /* Muted red for a vintage feel */
    color: white;
    border: none;
}

.btn-primary {
    background-color: #5c6bc0; /* Soft muted blue for classic feel */
    border: none;
}

.btn-primary:hover {
    background-color: #3f51b5; /* Slightly darker blue on hover */
}

.btn-danger:hover {
    background-color: #c0392b; /* Darker red for hover effect */
}

/* Form Styles */
form {
    background-color: #fff;
    border-radius: 8px;
    padding: 30px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
    font-family: 'Georgia', serif;
    border: 1px solid #ddd; /* Light gray border */
}

.form-group label {
    font-size: 16px;
    font-weight: 500;
    color: #555;
    font-family: 'Georgia', serif;
}

.form-control {
    border-radius: 5px;
    font-size: 15px;
    padding: 10px;
    background-color: #f1f1f1;
    border: 1px solid #ccc;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    background-color: #fff;
    border-color: #5c6bc0; /* Soft blue focus */
    outline: none;
}

/* Section Titles */
h2 {
    font-size: 22px;
    font-weight: 600;
    color: #444;
    margin-bottom: 20px;
    border-bottom: 2px solid #ddd;
    padding-bottom: 10px;
    font-family: 'Georgia', serif;
}

/* Header Styles */
header {
    background-color: #2c3e50; /* Muted dark blue for vintage yet modern touch */
    color: white;
    padding: 20px 0;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-bottom: 3px solid #aaa;
}

.header-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 20px;
}

h1 {
    margin: 0;
    font-size: 28px;
    font-weight: bold;
    font-family: 'Georgia', serif;
}

.btn-booked-tickets {
    background-color: #8e8e8e; /* Soft gray */
    color: #333;
    border: 2px solid #8e8e8e;
    padding: 12px 24px;
    border-radius: 25px;
    cursor: pointer;
    font-size: 16px;
    text-decoration: none;
    transition: background-color 0.3s, color 0.3s;
    font-family: 'Georgia', serif;
}

.btn-booked-tickets:hover {
    background-color: #7f7f7f;
    color: white;
}

h2 {
    font-size: 22px;
    font-weight: 600;
    color: #444;
    margin-bottom: 20px;
    border-bottom: 2px solid #ddd;
    padding-bottom: 10px;
    font-family: 'Georgia', serif;
}

/* Vintage Typography for Buttons and Titles */
button, .btn, .btn-booked-tickets {
    font-family: 'Georgia', serif; /* Ensuring all buttons have a vintage font */
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Responsive Layout */
@media (max-width: 768px) {
    .container {
        margin-top: 30px;
    }

    form {
        padding: 20px;
    }
}

/* Style for Edit Schedule button */
.btn-edit-schedule {
    background-color: #8e8e8e; /* Soft gray for vintage touch */
    color: #333;
    border: 2px solid #8e8e8e;
    padding: 12px 24px;
    border-radius: 25px;
    cursor: pointer;
    font-size: 16px;
    text-decoration: none;
    transition: background-color 0.3s, color 0.3s;
    font-family: 'Georgia', serif;
    margin-left: 15px; /* Space between buttons */
}

.btn-edit-schedule:hover {
    background-color: #7f7f7f;
    color: white;
}

/* Table Styles */
.table {
    width: 100%;
    margin-top: 20px;
    border-collapse: collapse;
}

.table th, .table td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: left;
}

.table th {
    background-color: #f4f4f4;
    color: #555;
    font-weight: bold;
}

.table-striped tbody tr:nth-child(odd) {
    background-color: #f9f9f9;
}

.table-striped tbody tr:nth-child(even) {
    background-color: #fff;
}

.table .btn-danger {
    background-color: #b03a2e;
    color: white;
    padding: 6px 12px;
    text-decoration: none;
    border-radius: 5px;
}

.table .btn-danger:hover {
    background-color: #c0392b;
}

</style>
<link rel="icon" type="image/png" href="/ticket/432312.png">
</head>
<body>
    <header>
        <div class="header-container">
            <a class="btn-booked-tickets" href="show_tickets.php">View Booked Tickets</a>
            <a class="btn-edit-schedule" href="admin.php">Back to Dashboard</a>
        </div>
    </header>

    <div class="container mt-5">
        <h1 class="mb-4">Edit/Delete Records</h1>
        <a href="index.html" class="btn btn-danger mb-4">Logout</a>

        <!-- Routes Table -->
        <h2>Manage Routes</h2>
        <table class="table table-striped mb-5">
            <thead>
                <tr>
                    <th>Route</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($route = $routes->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $route['route']; ?></td>
                        <td>
                            <a href="edit_schedule.php?delete_route=<?php echo $route['route_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this route?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Buses Table -->
        <h2>Manage Buses</h2>
        <table class="table table-striped mb-5">
            <thead>
                <tr>
                    <th>Bus</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($bus = $buses->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $bus['bus']; ?></td>
                        <td>
                            <a href="edit_schedule.php?delete_bus=<?php echo $bus['bus_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this bus?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Schedules Table -->
        <h2>Manage Schedules</h2>
        <table class="table table-striped mb-5">
            <thead>
                <tr>
                    <th>Schedule Details</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($schedule = $schedules->fetch_assoc()): ?>
                    <tr>
                        <td>
                            Route: <?php echo $schedule['route_id']; ?><br>
                            Bus: <?php echo $schedule['bus_id']; ?><br>
                            From: <?php echo $schedule['going_from']; ?><br>
                            To: <?php echo $schedule['going_to']; ?><br>
                            Date: <?php echo $schedule['date']; ?><br>
                            Departure: <?php echo $schedule['departure_time']; ?><br>
                            Arrival: <?php echo $schedule['arrival_time']; ?><br>
                        </td>
                        <td>
    <!-- <a href="edit_schedule.php?delete_schedule=<?php echo $schedule['schedule_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this schedule?')">Delete</a> -->
</td>

                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Tickets Table -->
        <h2>Manage Tickets</h2>
        <table class="table table-striped mb-5">
            <thead>
                <tr>
                    <th>Ticket</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($ticket = $tickets->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $ticket['ticket']; ?></td>
                        <td>
                            <a href="edit_schedule.php?delete_ticket=<?php echo $ticket['ticket_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this ticket?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
