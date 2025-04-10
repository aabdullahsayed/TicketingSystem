<?php
session_start();
include('dbms.php'); // Include database connection

// Check if admin is logged in
//if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
  //  header('Location: admin_login.php');
    //exit;
//}//

// Function to insert into the database
function insertData($query, $params) {
    global $conn;
    $stmt = $conn->prepare($query);
    $stmt->bind_param(...$params);
    return $stmt->execute();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_route'])) {
        $source = htmlspecialchars($_POST['source_location']);
        $destination = htmlspecialchars($_POST['destination_location']);
        $distance = htmlspecialchars($_POST['distance']);
        $duration = htmlspecialchars($_POST['estimated_duration']);

        insertData(
            "INSERT INTO route (source_location, destination_location, distance, estimated_duration) VALUES (?, ?, ?, ?)",
            ['ssds', $source, $destination, $distance, $duration]
        );
    }

    if (isset($_POST['add_bus'])) {
        $route_id = htmlspecialchars($_POST['route_id']);
        $total_seats = htmlspecialchars($_POST['total_seats']);
        $available_seats = htmlspecialchars($_POST['available_seats']);
        $status = htmlspecialchars($_POST['status']);

        insertData(
            "INSERT INTO bus (route_id, total_seats, available_seats, status) VALUES (?, ?, ?, ?)",
            ['iids', $route_id, $total_seats, $available_seats, $status]
        );
    }

    if (isset($_POST['add_schedule'])) {
        $route_id = htmlspecialchars($_POST['route_id']);
        $bus_id = htmlspecialchars($_POST['bus_id']);
        $going_from = htmlspecialchars($_POST['going_from']);
        $going_to = htmlspecialchars($_POST['going_to']);
        $date = htmlspecialchars($_POST['date']);
        $departure_time = htmlspecialchars($_POST['departure_time']);
        $arrival_time = htmlspecialchars($_POST['arrival_time']);
        $duration = htmlspecialchars($_POST['duration']);
		$price = htmlspecialchars($_POST['ticket_price']);

        insertData(
            "INSERT INTO schedule (route_id, bus_id, going_from, going_to, date, departure_time, arrival_time, duration, ticket_price ) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?)",
            ['iissssssd', $route_id, $bus_id, $going_from, $going_to, $date, $departure_time, $arrival_time, $duration, $price]
        );
    }

    header('Location: admin.php'); // Refresh the page after submission
    exit;
}

// Fetch required data for dropdowns
$routes = $conn->query("SELECT route_id, CONCAT(source_location, ' - ', destination_location) AS route FROM route");
$buses = $conn->query("SELECT bus_id, CONCAT('Bus ', bus_id, ' (Seats: ', available_seats, ')') AS bus FROM bus");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
   /* General Styles */
body {
    font-family: 'Georgia', serif; /* Classic serif font for an old-fashioned look */
    background-color: #f9f9f9; /* Soft light gray for a vintage feel */
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
    background-color: #b03a2e; /* Muted red for a more classic look */
    color: white;
    border: none;
}

.btn-primary {
    background-color: #5c6bc0; /* Soft muted blue for a classic feel */
    border: none;
}

.btn-primary:hover {
    background-color: #3f51b5; /* Slightly darker blue on hover */
}

/* Form Styles */
form {
    background-color: #fff;
    border-radius: 8px;
    padding: 30px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
    font-family: 'Georgia', serif;
    border: 1px solid #ddd; /* Light gray border for subtlety */
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

/* Logout Button */
.btn-logout {
    background-color: #b03a2e;
    color: white;
    padding: 10px 15px;
    font-size: 16px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
    border: none;
}

.btn-logout:hover {
    background-color: #c0392b; /* Slightly darker red on hover */
}

/* Header Styles */
header {
    background-color: #2c3e50; /* Muted dark blue for a vintage, yet modern touch */
    color: white;
    padding: 20px 0;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-bottom: 3px solid #aaa; /* Subtle border to add depth */
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
    background-color: #8e8e8e; /* Soft gray for classic touch */
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
    background-color: #7f7f7f; /* Slightly darker gray on hover */
    color: white;
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
    background-color: #8e8e8e; /* Soft gray for classic touch */
    color: #333;
    border: 2px solid #8e8e8e;
    padding: 12px 24px;
    border-radius: 25px;
    cursor: pointer;
    font-size: 16px;
    text-decoration: none;
    transition: background-color 0.3s, color 0.3s;
    font-family: 'Georgia', serif;
    margin-left: 15px; /* Space between the buttons */
}

.btn-edit-schedule:hover {
    background-color: #7f7f7f; /* Slightly darker gray on hover */
    color: white;
}

/* Ensures buttons in header are spaced and aligned properly */
.header-container a {
    font-family: 'Georgia', serif; /* Ensuring the links have a vintage font */
    text-transform: uppercase;
    letter-spacing: 1px;
}



    </style>
	
</head>
<link rel="icon" type="image/png" href="/ticket/432312.png">
<body>
<header>
    <div class="header-container">
        <a class="btn-booked-tickets" href="show_tickets.php">View Booked Tickets</a>
        <a class="btn-edit-schedule" href="edit_schedule.php">Edit Schedule</a>
    </div>
</header>

    <div class="container mt-5">
        <h1 class="mb-4">Admin Dashboard</h1>
        <a href="index.html" class="btn btn-danger mb-4">Logout</a>

        <!-- Add Route Form -->
        <h2>Add Route</h2>
        <form method="post" class="mb-5">
            <input type="hidden" name="add_route">
            <div class="mb-3">
                <label for="source_location" class="form-label">Source Location</label>
                <input type="text" id="source_location" name="source_location" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="destination_location" class="form-label">Destination Location</label>
                <input type="text" id="destination_location" name="destination_location" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="distance" class="form-label">Distance (in km)</label>
                <input type="number" id="distance" name="distance" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="estimated_duration" class="form-label">Estimated Duration (HH:MM:SS)</label>
                <input type="text" id="estimated_duration" name="estimated_duration" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Route</button>
        </form>

        <!-- Add Bus Form -->
        <h2>Add Bus</h2>
        <form method="post" class="mb-5">
            <input type="hidden" name="add_bus">
            <div class="mb-3">
                <label for="route_id" class="form-label">Route</label>
                <select id="route_id" name="route_id" class="form-select" required>
                    <?php while ($route = $routes->fetch_assoc()): ?>
                        <option value="<?php echo $route['route_id']; ?>"><?php echo $route['route']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="total_seats" class="form-label">Total Seats</label>
                <input type="number" id="total_seats" name="total_seats" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="available_seats" class="form-label">Available Seats</label>
                <input type="number" id="available_seats" name="available_seats" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-select" required>
                    <option value="active">Active</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Bus</button>
        </form>

        <!-- Add Schedule Form -->
        <h2>Add Schedule</h2>
        <form method="post">
            <input type="hidden" name="add_schedule">
            <div class="mb-3">
                <label for="route_id_schedule" class="form-label">Route</label>
                <select id="route_id_schedule" name="route_id" class="form-select" required>
                    <?php $routes->data_seek(0); while ($route = $routes->fetch_assoc()): ?>
                        <option value="<?php echo $route['route_id']; ?>"><?php echo $route['route']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="bus_id" class="form-label">Bus</label>
                <select id="bus_id" name="bus_id" class="form-select" required>
                    <?php while ($bus = $buses->fetch_assoc()): ?>
                        <option value="<?php echo $bus['bus_id']; ?>"><?php echo $bus['bus']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="going_from" class="form-label">Going From</label>
                <input type="text" id="going_from" name="going_from" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="going_to" class="form-label">Going To</label>
                <input type="text" id="going_to" name="going_to" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" id="date" name="date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="departure_time" class="form-label">Departure Time</label>
                <input type="time" id="departure_time" name="departure_time" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="arrival_time" class="form-label">Arrival Time</label>
                <input type="time" id="arrival_time" name="arrival_time" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="duration" class="form-label">Duration (HH:MM:SS)</label>
                <input type="text" id="duration" name="duration" class="form-control" required>
            </div>
			<div class="mb-3">
    <label for="ticket_price" class="form-label">Ticket Price</label>
    <input type="number" id="ticket_price" name="ticket_price" class="form-control" required>
</div>
            <button type="submit" class="btn btn-primary">Add Schedule</button>
        </form>
    </div>
</body>
</html>
