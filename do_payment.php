<?php 
session_start(); 
include('dbms.php'); 

// Database connection
if (!isset($_SESSION['user_id'])) { 
    echo "<p>You must log in to complete the payment.</p>"; 
    echo "<p><a href='login.php'>Log in</a></p>"; 
    exit; 

} 
$user_id = $_SESSION['user_id']; // Retrieve user_id from session

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_seat'], $_POST['bus_id'], $_POST['date'])) { 
    $selected_seat = $_POST['selected_seat']; 
    $bus_id = $_POST['bus_id']; 
    $date = $_POST['date']; 
    $conn->begin_transaction(); 

    try { 
        // Fetch route_id
        $query_route = "SELECT route_id, bus_name FROM bus WHERE bus_id = ?";
        $stmt = $conn->prepare($query_route);
        $stmt->bind_param("i", $bus_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $bus = $result->fetch_assoc();
        
        if (!$bus) {
            throw new Exception("Bus not found.");
        }

        $route_id = $bus['route_id'];
        $bus_name = $bus['bus_name'];

        // Fetch route details (From and To locations)
        $query_route_details = "SELECT source_location, destination_location FROM route WHERE route_id = ?";
        $stmt = $conn->prepare($query_route_details);
        $stmt->bind_param("i", $route_id);
        $stmt->execute();
        $route_details = $stmt->get_result()->fetch_assoc();
        
        if (!$route_details) {
            throw new Exception("Route details not found.");
        }

        $source_location = $route_details['source_location'];
        $destination_location = $route_details['destination_location'];
		

        // Insert ticket
        $query_insert_ticket = "INSERT INTO ticket (bus_id, route_id, seat_number, date, user_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query_insert_ticket);
        $stmt->bind_param("iiisi", $bus_id, $route_id, $selected_seat, $date, $user_id);
        $stmt->execute();

        // Update available seats
        $query_update_seats = "UPDATE bus SET available_seats = available_seats - 1 WHERE bus_id = ? AND available_seats > 0";
        $stmt = $conn->prepare($query_update_seats);
        $stmt->bind_param("i", $bus_id);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception("No available seats to update.");
        }

        $conn->commit();

        // Display payment success and ticket information
        echo "
        <div class='container'>
            <div class='ticket'>
                <h2>Payment Successful!</h2>
                <p>Your ticket has been successfully booked.</p>
                <div class='ticket-info'>
                    <h3>Ticket Information</h3>
                    <p><strong>Bus Name:</strong> $bus_name</p>
                    <p><strong>From:</strong> $source_location</p>
                    <p><strong>To:</strong> $destination_location</p>
                    <p><strong>Seat Number:</strong> $selected_seat</p>
                    <p><strong>Travel Date:</strong> $date</p>
                </div>
                <a href='profile.php' class='btn'>View Profile</a> 
                <a href='search.php' class='btn'>Go Home</a>
            </div>
        </div>";

    } catch (Exception $e) {
        $conn->rollback();
        echo "
        <div class='container'>
            <div class='error'>
                <p>Error: " . $e->getMessage() . "</p>
                <p><a href='select.php'>Go Back</a></p>
            </div>
        </div>";
    }

} else {
    echo "<p>Invalid request. Please try again.</p>";
    echo "<p><a href='search.php'>Search for Buses</a></p>";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/png" href="/ticket/432312.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #f2f2f2;
            color: #333;
            padding: 20px;
            margin: 0;
            box-sizing: border-box;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .ticket-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            padding: 20px;
            background-color: #f5f5f5;
            margin: 0 auto;
        }

        .ticket {
            width: 600px;
            padding: 30px;
            background-color: #fff;
            border: 5px solid #222;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            font-size: 16px;
            line-height: 1.5;
            position: relative;
            text-align: center;
            font-family: 'Courier New', Courier, monospace;
            background-color: #f5f5f5;
        }

        .ticket h2 {
            font-size: 2.5rem;
            color: #333;
            font-weight: bold;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .ticket-info {
            padding: 15px;
            margin-top: 20px;
            background-color: #eaeaea;
            border-radius: 8px;
            border: 1px dashed #bbb;
            text-align: left;
        }

        .ticket-info h3 {
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .ticket-info p {
            margin: 8px 0;
            font-size: 1.1rem;
        }

        .ticket-info p strong {
            font-weight: bold;
        }

        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 2px solid #333;
            transition: background-color 0.3s ease;
            text-align: center;
        }

        .btn:hover {
            background-color: #444;
            color: #fff;
        }

        .error {
            color: red;
            text-align: center;
        }

        .error a {
            color: #007bff;
        }
    </style>
</head>
<body>
</body>
</html>
