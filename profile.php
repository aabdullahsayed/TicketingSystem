<?php
session_start();
include('dbms.php'); // Database connection

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p>Please log in to view your profile.</p>";
    echo "<p><a href='login.php'>Log in</a></p>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$query_user = "SELECT first_name, last_name, email, city, country FROM new_users WHERE id = ?";
$stmt_user = $conn->prepare($query_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();

// Fetch user's tickets
$query_tickets = "
    SELECT 
        b.bus_name, 
        t.date, 
        t.seat_number, 
        r.source_location AS `from`, 
        r.destination_location AS `to`,
        s.departure_time,
        s.arrival_time
    FROM ticket t
    JOIN bus b ON t.bus_id = b.bus_id
    JOIN route r ON t.route_id = r.route_id
    JOIN schedule s ON s.bus_id = t.bus_id AND s.route_id = t.route_id
    WHERE t.user_id = ?
    ORDER BY t.date DESC
";
$stmt_tickets = $conn->prepare($query_tickets);
$stmt_tickets->bind_param("i", $user_id);
$stmt_tickets->execute();
$result_tickets = $stmt_tickets->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/png" href="/ticket/432312.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
     body {
    font-family: 'Georgia', serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4; /* Light gray background for a vintage, neutral look */
    overflow-x: hidden;
    color: #333; /* Dark gray text color for readability */
}

/* Sidebar styling */
.sidebar {
    height: 100%;
    width: 0;
    position: fixed;
    top: 0;
    left: 0;
    background-color: #2e2e2e; /* Dark gray for simplicity */
    overflow-x: hidden;
    transition: 0.5s;
    padding-top: 60px;
    color: #fff;
    z-index: 9999;
    font-size: 18px;
}

.sidebar h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #fff;
    font-family: 'Georgia', serif;
    font-size: 24px;
}

.sidebar p {
    padding: 10px 20px;
    color: #ddd; /* Light gray text for subtle contrast */
    font-size: 16px;
    font-family: 'Georgia', serif;
}

.sidebar a {
    padding: 10px 15px;
    text-decoration: none;
    font-size: 18px;
    color: #ddd; /* Light gray links */
    display: block;
    transition: 0.3s;
}

.sidebar a:hover {
    background-color: #555; /* Darker gray on hover */
    color: #fff;
}

.sidebar .closebtn {
    position: absolute;
    top: 10px;
    right: 25px;
    font-size: 36px;
    margin-left: 50px;
    color: #fff;
}

/* Open button */
.openbtn {
    font-size: 20px;
    cursor: pointer;
    background-color: #2e2e2e; /* Dark gray background for simplicity */
    color: white;
    padding: 10px 15px;
    border: none;
    position: fixed;
    top: 20px;
    left: 20px;
    z-index: 1000;
    border-radius: 5px;
    transition: 0.3s;
}

.openbtn:hover {
    background-color: #555; /* Darker gray on hover */
}

/* Tickets Table */
.tickets-container {
    margin: 20px auto;
    width: 90%;
    max-width: 800px;
    background: #fff;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    font-family: 'Georgia', serif;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

table th, table td {
    border: 1px solid #ccc; /* Light gray border for a softer look */
    padding: 12px;
    text-align: center;
    font-family: 'Georgia', serif;
}

table th {
    background-color: #2e2e2e; /* Dark gray header for simplicity */
    color: #fff;
    font-size: 18px;
}

table tr:nth-child(even) {
    background-color: #f9f9f9; /* Very light gray for even rows */
}

table tr:hover {
    background-color: #f1f1f1; /* Light gray hover effect */
}

h2 {
    color: #333; /* Dark gray for headings */
    text-align: center;
    margin-bottom: 20px;
    font-size: 26px;
    font-family: 'Georgia', serif;
}

/* Vintage Typography and Buttons */
button {
    background-color: #2e2e2e; /* Dark gray button */
    color: white;
    padding: 10px 20px;
    border: none;
    font-family: 'Georgia', serif;
    font-size: 18px;
    cursor: pointer;
    border-radius: 5px;
    transition: 0.3s;
}

button:hover {
    background-color: #555; /* Darker gray on hover */
}


</style>
</head>
<body>
    <!-- Sidebar -->
    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <h2>User Profile</h2>
        <p><strong>First Name:</strong> <?php echo htmlspecialchars($user['first_name']); ?></p>
        <p><strong>Last Name:</strong> <?php echo htmlspecialchars($user['last_name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>City:</strong> <?php echo htmlspecialchars($user['city']); ?></p>
        <p><strong>Country:</strong> <?php echo htmlspecialchars($user['country']); ?></p>
    </div>

    <!-- Open Sidebar Button -->
    <button class="openbtn" onclick="openNav()">☰ View Profile</button>

    <!-- Tickets Section -->
    <div class="tickets-container">
        <h2>Booked Tickets</h2>
        <?php if ($result_tickets->num_rows > 0): ?>
            <table>
    <thead>
        <tr>
            <th>Bus Name</th>
            <th>Date</th>
            <th>Seat Number</th>
            <th>From</th>
            <th>To</th>
            <th>Departure Time</th>
            <th>Arrival Time</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($ticket = $result_tickets->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($ticket['bus_name']); ?></td>
                <td><?php echo htmlspecialchars($ticket['date']); ?></td>
                <td><?php echo htmlspecialchars($ticket['seat_number']); ?></td>
                <td><?php echo htmlspecialchars($ticket['from']); ?></td>
                <td><?php echo htmlspecialchars($ticket['to']); ?></td>
                <td><?php echo htmlspecialchars($ticket['departure_time']); ?></td>
                <td><?php echo htmlspecialchars($ticket['arrival_time']); ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
        <?php else: ?>
            <p>No tickets booked yet.</p>
        <?php endif; ?>
    </div>

    <!-- JavaScript for Sidebar -->
    <script>
        function openNav() {
            document.getElementById("mySidebar").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("mySidebar").style.width = "0";
        }
    </script>
</body>
</html>
