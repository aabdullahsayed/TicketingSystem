<?php
// Assuming you have a database connection (dbms.php)
include('dbms.php');

// Check if bus_id is passed via GET request
if (!isset($_GET['bus_id'])) {
    echo "Bus not selected!";
    exit;
}

$bus_id = $_GET['bus_id'];

// Fetch bus details (example: bus_name, available seats, etc.)
$query = "SELECT bus_name, available_seats FROM bus WHERE bus_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $bus_id);
$stmt->execute();
$result = $stmt->get_result();
$bus = $result->fetch_assoc();

// If bus is not found
if (!$bus) {
    echo "Bus not found!";
    exit;
}

// Number of available seats (for simplicity, assume it's 50 seats in total)
$available_seats = 50;
$seats_per_side = 25;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Seats - <?php echo htmlspecialchars($bus['bus_name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f7f7f7;
            padding: 30px;
        }
        .bus-seats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .bus-side {
            width: 48%;
            background-color: #f0f0f0;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .seat-row {
            display: flex;
            justify-content: space-around;
            margin-bottom: 10px;
        }
        .seat {
            width: 30px;
            height: 30px;
            border-radius: 5px;
            background-color: #85C1AE;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .seat.selected {
            background-color: #FF6347; /* Red for selected seats */
        }
        .seat:hover {
            background-color: #A9DFBF;
        }
        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Select Your Seats - <?php echo htmlspecialchars($bus['bus_name']); ?></h2>
    <p><strong>Available Seats: </strong><?php echo $available_seats; ?> Seats</p>

    <form action="process_ticket.php" method="POST">
        <!-- Bus and Seat Details -->
        <input type="hidden" name="bus_id" value="<?php echo htmlspecialchars($bus_id); ?>">
        <input type="hidden" name="bus_name" value="<?php echo htmlspecialchars($bus['bus_name']); ?>">

        <div class="bus-seats">
            <!-- Left Side of Bus -->
            <div class="bus-side">
                <h4>Left Side</h4>
                <div class="seat-row">
                    <?php for ($i = 1; $i <= 25; $i++): ?>
                        <div class="seat">
                            <select name="seat_left_<?php echo $i; ?>" class="form-control">
                                <option value="">Choose</option>
                                <option value="A<?php echo $i; ?>">A<?php echo $i; ?></option>
                                <option value="B<?php echo $i; ?>">B<?php echo $i; ?></option>
                            </select>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Right Side of Bus -->
            <div class="bus-side">
                <h4>Right Side</h4>
                <div class="seat-row">
                    <?php for ($i = 1; $i <= 25; $i++): ?>
                        <div class="seat">
                            <select name="seat_right_<?php echo $i; ?>" class="form-control">
                                <option value="">Choose</option>
                                <option value="C<?php echo $i; ?>">C<?php echo $i; ?></option>
                                <option value="D<?php echo $i; ?>">D<?php echo $i; ?></option>
                            </select>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>

        <!-- Total Price -->
        <div class="form-group">
            <label for="total_price">Total Price:</label>
            <input type="text" id="total_price" name="total_price" value="<?php echo htmlspecialchars($bus['price']); ?>" readonly class="form-control">
        </div>

        <button type="submit" class="btn btn-success w-100">Book Tickets</button>
    </form>
</div>

</body>
</html>
