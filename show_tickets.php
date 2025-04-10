<?php
session_start();
include('dbms.php'); // Include database connection

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p>Please log in to view your booked tickets.</p>";
    echo "<p><a href='login.php'>Log in</a></p>";
    exit;
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Fetch booked tickets
$query = "
    SELECT 
        u.first_name, u.last_name, 
        b.bus_name, 
        t.date, 
        t.seat_number, 
        r.source_location AS `from`, 
        r.destination_location AS `to`
    FROM ticket t
    JOIN new_users u ON t.user_id = u.id
    JOIN bus b ON t.bus_id = b.bus_id
    JOIN route r ON b.route_id = r.route_id
    WHERE t.user_id = ?
    ORDER BY t.date DESC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id); // Bind the user_id
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booked Tickets</title>
    <style>
        /* General Styles */
body {
    font-family: 'Georgia', serif; /* Classic serif font for an old-fashioned touch */
    margin: 0;
    padding: 0;
    background-color: #f4f4f9; /* Subtle off-white background */
    color: #333; /* Dark gray text for readability */
}

.container {
    width: 70%; /* Slightly smaller width for a more vintage layout */
    margin: 20px auto;
    background: #fff;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    font-family: 'Georgia', serif;
}

h1 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 28px; /* Larger font size for a more classic feel */
    font-weight: bold;
    color: #444;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

table th, table td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: center;
    font-size: 16px;
}

table th {
    background-color: #3e4a5d; /* Muted dark blue for a vintage, classy feel */
    color: white;
    font-family: 'Georgia', serif;
}

table td {
    background-color: #f9f9f9; /* Subtle background for table data cells */
}

table tr:nth-child(even) {
    background-color: #f2f2f2; /* Alternate row color for a more traditional look */
}

table tr:hover {
    background-color: #e6e6e6; /* Slightly darker row on hover */
}

.no-tickets {
    text-align: center;
    font-size: 18px;
    color: #555;
    font-family: 'Georgia', serif;
    margin-top: 20px;
}

/* Back Button Styles */
.back-btn {
    display: inline-block;
    margin: 20px auto;
    padding: 10px 20px;
    background-color: #5bc0de; /* Soft blue for a modern touch */
    color: white;
    text-decoration: none;
    border-radius: 25px;
    text-align: center;
    font-family: 'Georgia', serif;
    font-weight: bold;
    letter-spacing: 1px;
    text-transform: uppercase;
    transition: background-color 0.3s;
}

.back-btn:hover {
    background-color: #31b0d5; /* Slightly darker blue on hover */
}

@media (max-width: 768px) {
    .container {
        width: 90%; /* Full width on smaller screens for better readability */
    }

    h1 {
        font-size: 24px; /* Adjust title size for smaller screens */
    }

    table th, table td {
        padding: 8px; /* Reduce padding for smaller screens */
        font-size: 14px; /* Smaller font size for better fit */
    }

    .back-btn {
        font-size: 14px; /* Adjust font size for smaller screens */
        padding: 8px 16px;
    }
}

    </style>
	<link rel="icon" type="image/png" href="/ticket/432312.png">
</head>
<body>
    <div class="container">
        <h1>Your Booked Tickets</h1>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Bus Name</th>
                        <th>Date</th>
                        <th>Seat Number</th>
                        <th>From</th>
                        <th>To</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($ticket = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($ticket['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($ticket['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($ticket['bus_name']); ?></td>
                            <td><?php echo htmlspecialchars($ticket['date']); ?></td>
                            <td><?php echo htmlspecialchars($ticket['seat_number']); ?></td>
                            <td><?php echo htmlspecialchars($ticket['from']); ?></td>
                            <td><?php echo htmlspecialchars($ticket['to']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-tickets">You have not booked any tickets yet.</p>
        <?php endif; ?>
        <a href="admin.php" class="back-btn">Back to Home</a>
    </div>
</body>
</html>
