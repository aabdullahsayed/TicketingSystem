<?php
session_start();
include('dbms.php'); // Include your database connection file

// Initialize variables
$bus_id = $date = $success = $error = "";

// Check if form data is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bus_id = htmlspecialchars($_POST['bus_id']);
    $date = htmlspecialchars($_POST['date']);

    // Validate inputs
    if (!empty($bus_id) && !empty($date)) {
        // Fetch bus and seat availability details
        $stmt = $conn->prepare("
            SELECT b.bus_id, b.available_seats, r.source_location, r.destination_location, s.departure_time
            FROM bus b
            JOIN route r ON b.route_id = r.route_id
            JOIN schedule s ON b.bus_id = s.bus_id
            WHERE b.bus_id = ? AND s.date = ?
        ");
        $stmt->bind_param('is', $bus_id, $date);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $bus = $result->fetch_assoc();

            // Check if there are seats available
            if ($bus['available_seats'] > 0) {
                // Reduce seat count
                $new_seats = $bus['available_seats'] - 1;
                $updateStmt = $conn->prepare("UPDATE bus SET available_seats = ? WHERE bus_id = ?");
                $updateStmt->bind_param('ii', $new_seats, $bus_id);
                $updateStmt->execute();

                // Add ticket record (Example: Assuming a `ticket` table exists)
                $insertStmt = $conn->prepare("
                    INSERT INTO ticket (bus_id, date, source, destination, departure_time)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $insertStmt->bind_param(
                    'issss',
                    $bus_id,
                    $date,
                    $bus['source_location'],
                    $bus['destination_location'],
                    $bus['departure_time']
                );
                $insertStmt->execute();

                $success = "Your ticket has been successfully booked!";
            } else {
                $error = "No seats are available for the selected bus.";
            }
        } else {
            $error = "Invalid bus details or journey date.";
        }
    } else {
        $error = "Invalid request. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
       body {
    font-family: 'Roboto', sans-serif;
    background: linear-gradient(to right, #ece9e6, #ffffff);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
}

.container {
    max-width: 500px;
    background: #fff;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s;
}

.container:hover {
    transform: translateY(-5px);
}

h2 {
    color: #333;
    font-size: 24px;
    margin-bottom: 20px;
}

.success, .error {
    font-size: 18px;
    margin-top: 20px;
    padding: 10px;
    border-radius: 5px;
}

.success {
    color: #4CAF50;
    background: #e8f5e9;
}

.error {
    color: #FF4D4F;
    background: #ffebee;
}

.back-button {
    margin-top: 20px;
    padding: 12px 20px;
    font-size: 14px;
    color: white;
    background-color: #007BFF;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s, transform 0.3s;
}

.back-button:hover {
    background-color: #0056b3;
    transform: translateY(-2px);
}

    </style>
</head>
<body>
<div class="container">
    <h2>Buy Ticket</h2>
    <?php if (!empty($success)): ?>
        <p class="success"><?php echo $success; ?></p>
        <a href="search.php" class="back-button">Back to Search</a>
    <?php elseif (!empty($error)): ?>
        <p class="error"><?php echo $error; ?></p>
        <a href="search.php" class="back-button">Back to Search</a>
    <?php else: ?>
        <p>Please do not refresh this page. If you encounter an issue, return to the search page and try again.</p>
    <?php endif; ?>
</div>
</body>
</html>
