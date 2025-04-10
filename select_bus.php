<?php
// Include the database connection file
include('dbms.php');  // Ensure this is the correct path to your dbms.php file

// Assume $bus_id is passed from the previous page
$bus_id = $_POST['bus_id'];
$date = $_POST['date'];

// Fetch bus details and seat availability using mysqli
$sql = "SELECT b.bus_name, r.source_location, r.destination_location, r.distance, r.estimated_duration, b.total_seats, b.available_seats 
        FROM bus b
        JOIN route r ON b.route_id = r.route_id
        WHERE b.bus_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bus_id);  // Bind the $bus_id as an integer
$stmt->execute();
$result = $stmt->get_result();  // Get the result set
$bus = $result->fetch_assoc();  // Fetch the row as an associative array

// Generate seat layout based on total_seats and available_seats
$seats = [];
for ($i = 1; $i <= $bus['total_seats']; $i++) {
    $seats[] = ['seat_number' => $i, 'status' => ($i <= $bus['available_seats'] ? 'available' : 'booked')];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" type="image/png" href="/ticket/432312.png">
    <title>Choose a Seat</title>
    <style>
       body {
    font-family: 'Georgia', serif;
    background-color: #eae6da; /* Light beige background for a vintage feel */
    color: #333;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    background-image: url('vintage_background.jpg'); /* Optional vintage background */
    background-size: cover;
    background-attachment: fixed;
}

.container {
    background: #f2e1b3; /* Classic soft cream color */
    padding: 20px;
    box-shadow: 0 4px 25px rgba(0, 0, 0, 0.2); /* Darker shadow for vintage depth */
    border-radius: 15px; /* Rounded corners for a more classic look */
    width: 80%;
    max-width: 600px;
    font-family: 'Georgia', serif;
    border: 4px solid #6a4e23; /* Dark brown border for the classic bus outline */
    overflow-y: auto;
    max-height: 90vh;
    background-image: url('old_texture.jpg'); /* Optional old texture (wood, metal) */
    background-size: cover;
    background-position: center;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 26px;
    color: #6a4e23; /* Dark brown text for headings */
    font-weight: 700;
}

.seat-layout {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    margin-bottom: 20px;
    gap: 15px;
}

.seat-layout label, .seat-layout span {
    flex: 0 0 30%;
    margin-bottom: 15px;
    font-size: 16px;
    font-family: 'Georgia', serif;
    color: #6a4e23;
    text-align: center;
    padding: 10px;
    border: 2px solid #6a4e23; /* Dark brown borders for each seat */
    border-radius: 8px;
    background-color: #fff5e1; /* Light cream background for seats */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Shadow for depth */
}

.seat-layout .seat {
    background-color: #8b5e3c; /* Vintage brown color for available seats */
    color: white; /* Text color */
}

.seat-layout .occupied {
    background-color: #d9b3b3; /* Muted red color for occupied seats */
    color: #6c3e2f; /* Text color for occupied */
    cursor: not-allowed;
}

button {
    width: 100%;
    padding: 12px;
    background-color: #6a4e23; /* Dark brown button to match vintage theme */
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 18px;
    font-family: 'Georgia', serif;
    transition: background-color 0.3s ease, transform 0.2s ease;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
}

button:hover {
    background-color: #4b3a22; /* Darker brown for hover effect */
    transform: scale(1.05); /* Slight hover effect */
}

/* Responsive Design */
@media (max-width: 768px) {
    .seat-layout {
        justify-content: center;
        gap: 10px;
    }

    .seat-layout label, .seat-layout span {
        flex: 0 0 45%;
    }

    button {
        padding: 14px;
        font-size: 16px;
    }
}


    </style>
</head>
<body>
    <div class="container">
	
        <form action="do_payment.php" method="POST">
            <h2>Choose a Seat for Bus: <?php echo htmlspecialchars($bus['bus_name']); ?></h2>
            <div class="seat-layout">
                <?php foreach ($seats as $seat): ?>
                    <?php if ($seat['status'] == 'available'): ?>
                        <label>
                            <input type="radio" name="selected_seat" value="<?php echo $seat['seat_number']; ?>"> Seat <?php echo $seat['seat_number']; ?>
                        </label>
                    <?php else: ?>
                        <span>Seat <?php echo $seat['seat_number']; ?> - Booked</span>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <input type="hidden" name="bus_id" value="<?php echo $bus_id; ?>">
            <input type="hidden" name="date" value="<?php echo $date; ?>">
            <button type="submit">Proceed to Payment</button>
        </form>
    </div>
</body>
</html>
