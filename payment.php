<?php
session_start();
include('dbms.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p>Please log in to proceed with the payment.</p>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch ticket details
if (!isset($_GET['ticket_id'])) {
    echo "<p>Error: No ticket selected. Please try again.</p>";
    exit;
}

$ticket_id = $_GET['ticket_id'];
$query = "SELECT t.ticket_id, t.ticket_price, t.bus_id, t.seat_number, t.date, r.source_location, r.destination_location, b.bus_name 
          FROM ticket t
          JOIN route r ON t.route_id = r.route_id
          JOIN bus b ON t.bus_id = b.bus_id
          WHERE t.ticket_id = ? AND t.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $ticket_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$ticket = $result->fetch_assoc();

if (!$ticket) {
    echo "<p>Ticket not found or you are not authorized to view this ticket.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Gateway</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .payment-container {
            position: relative;
            width: 100%;
            max-width: 400px;
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            animation: slideIn 0.4s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateY(100%);
            }
            to {
                transform: translateY(0);
            }
        }

        .payment-header {
            font-size: 22px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #333;
        }

        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }

        input[readonly] {
            background: #f9f9f9;
            color: #555;
        }

        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: linear-gradient(135deg, #5d7bf5, #9565e3);
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
        }

        .ticket-info {
            font-size: 14px;
            margin-bottom: 10px;
            line-height: 1.5;
            color: #444;
        }
    </style>
</head>
<body>

<div class="payment-container">
    <button class="close-btn" onclick="window.history.back();">&times;</button>
    <div class="payment-header">Complete Your Payment</div>
    
    <!-- Ticket Details -->
    <div class="ticket-info">
        <p><strong>Bus Name:</strong> <?php echo htmlspecialchars($ticket['bus_name']); ?></p>
        <p><strong>Date:</strong> <?php echo htmlspecialchars($ticket['date']); ?></p>
        <p><strong>From:</strong> <?php echo htmlspecialchars($ticket['source_location']); ?></p>
        <p><strong>To:</strong> <?php echo htmlspecialchars($ticket['destination_location']); ?></p>
        <p><strong>Seat Number:</strong> <?php echo htmlspecialchars($ticket['seat_number']); ?></p>
        <p><strong>Ticket Price:</strong> <?php echo htmlspecialchars($ticket['ticket_price']); ?> BDT</p>
    </div>

    <!-- Payment Form -->
    <form action="do_payment.php" method="POST">
        <div class="form-group">
            <label for="bank">Choose Mobile Banking:</label>
            <select id="bank" name="bank" required>
                <option value="">-- Select an option --</option>
                <option value="bkash">bKash</option>
                <option value="nagad">Nagad</option>
                <option value="rocket">Rocket</option>
                <option value="upay">Upay</option>
            </select>
        </div>

        <div class="form-group">
            <label for="phone">Mobile Number:</label>
            <input type="tel" id="phone" name="phone" placeholder="e.g., 01XXXXXXXXX" required>
        </div>

        <!-- Hidden Fields -->
        <input type="hidden" name="ticket_id" value="<?php echo htmlspecialchars($ticket['ticket_id']); ?>">
        <input type="hidden" name="amount" value="<?php echo htmlspecialchars($ticket['ticket_price']); ?>">
        <input type="hidden" name="bus_id" value="<?php echo htmlspecialchars($ticket['bus_id']); ?>">
        <input type="hidden" name="seat_number" value="<?php echo htmlspecialchars($ticket['seat_number']); ?>">

        <!-- Submit Button -->
        <button type="submit">Pay Now</button>
    </form>
</div>

</body>
</html>
