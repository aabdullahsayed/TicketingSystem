<?php

include('dbms.php'); // Include database connection file

$searchResults = [];
$error = "";

// Handle search request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $from = htmlspecialchars($_POST['from']);
    $to = htmlspecialchars($_POST['to']);
    $date = htmlspecialchars($_POST['date']);

    if (!empty($from) && !empty($to) && !empty($date)) {
        // Prepare and execute search query
        $stmt = $conn->prepare("
    SELECT 
    b.bus_id, 
    b.bus_name, 
    b.total_seats, 
    b.available_seats, 
    b.status, 
    r.source_location, 
    r.destination_location, 
    s.date, 
    s.departure_time, 
    s.arrival_time, 
    s.duration,
    s.ticket_price
FROM bus b
JOIN route r ON b.route_id = r.route_id
JOIN schedule s ON b.bus_id = s.bus_id
WHERE r.source_location = ? AND r.destination_location = ? AND s.date = ? AND b.status = 'active';


        ");
        $stmt->bind_param('sss', $from, $to, $date);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $searchResults = $result->fetch_all(MYSQLI_ASSOC);
        } else {
            $error = "No buses available for the selected route and date.";
        }
    } else {
        $error = "Please provide all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Travel Planner</title>
  <style>
   /* Body background with a vintage touch */
body {
    background-image: url('tourist.jpg');
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: center top;
    font-family: 'Georgia', serif; /* Using serif for a more vintage look */
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    background-color: #f8f9fa; /* Light gray background for softer look */
}

/* Travel form container */
.travel-form {
    display: flex;
    gap: 20px; /* Evenly spaced fields for a clean appearance */
    border: 2px solid #b0a28f; /* Softer vintage brown border */
    padding: 20px;
    border-radius: 10px;
    background: #f4f1e4; /* Parchment-like background for a classic vibe */
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15); /* Deeper shadow for vintage elegance */
}

/* Field styling */
.travel-field {
    display: flex;
    flex-direction: column;
}

/* Label styling */
.travel-field label {
    font-size: 15px;
    color: #5e4b3d; /* Soft brown for vintage text color */
    font-family: 'Georgia', serif; /* Classic font */
    margin-bottom: 8px; /* Slightly more space for readability */
}

/* Input/Select styling */
.travel-field select, .travel-field input {
    border: 2px solid #b0a28f;
    border-radius: 8px; /* Subtle rounded edges */
    padding: 12px;
    font-size: 16px;
    font-family: 'Georgia', serif;
    width: 240px; /* Slightly wider for a relaxed design */
    background-color: #fff; /* Crisp white background */
}

/* Arrow icon styling */
.arrow {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: #b04e50; /* Muted red for a vintage accent */
}

/* Focus effect on select */
select:focus, input:focus {
    outline: 2px solid #b04e50; /* Muted red outline for focus */
    box-shadow: 0 0 5px rgba(176, 78, 80, 0.5); /* Subtle glow */
}

/* Submit button with classic design */
.submit-button {
    align-self: flex-end;
    padding: 12px 25px;
    font-size: 16px;
    font-family: 'Georgia', serif;
    color: white;
    background-color: #b04e50; /* Vintage muted red */
    border: 2px solid #8e3e42; /* Darker red border */
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2); /* Soft shadow for classic touch */
}

.submit-button:hover {
    background-color: #8e3e42; /* Darker muted red */
    border-color: #6c3034; /* Even darker border on hover */
    transform: scale(1.02); /* Slight hover effect */
}

/* Header with a soft vintage feel */
header {
    position: absolute;
    top: 0;
    width: 100%;
    padding: 12px 20px;
    background: rgba(244, 241, 228, 0.9); /* Parchment-style semi-transparent background */
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    font-family: 'Georgia', serif;
}

/* Styling the left corner button */
.left-corner {
    position: fixed;
    top: 15px;
    left: 15px;
    background-color: #b04e50; /* Muted red for classic button */
    color: white;
    padding: 10px 20px;
    font-size: 14px;
    font-family: 'Georgia', serif;
    text-decoration: none;
    border: 2px solid #8e3e42; /* Darker border for elegance */
    border-radius: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.left-corner:hover {
    background-color: #8e3e42;
    border-color: #6c3034;
    transform: scale(1.05); /* Slight hover effect */
}

/* Results container with soft vintage design */
/* Floating Results Container */
.results-container {
    position: fixed; /* Fixed position to make it float */
    bottom: 20px; /* 20px from the bottom of the viewport */
    left: 50%; /* Center horizontally */
    transform: translateX(-50%); /* Align perfectly at the center */
    max-width: 1200px;
    width: 90%; /* Make sure it's responsive */
    padding: 30px;
    background: #f4f1e4; /* Same parchment-like background */
    border-radius: 12px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15); /* Deeper shadow for elegance */
    text-align: center;
    font-family: 'Georgia', serif;
    z-index: 10; /* Ensure it stays on top */
}

/* The rest of your CSS remains the same for other elements like travel form */


/* Table Styling with soft vintage touch */
.results-table {
    width: 80%;
    max-width: 800px;
    margin: 20px auto;
    font-size: 14px;
    font-family: 'Georgia', serif;
    background-color: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

/* Table Header */
.results-table thead {
    background-color: #5e4b3d; /* Soft dark brown */
    color: white;
    text-align: center;
}

/* Table Cell Styling */
.results-table th, .results-table td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

/* Zebra Striping for Rows */
.results-table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

/* Hover effect for rows */
.results-table tbody tr:hover {
    background-color: #f1f1f1;
    transition: background-color 0.3s ease;
}

/* Buy Ticket Button with vintage-inspired colors */
.buy-button {
    background-color: #4CAF50; /* Muted green for vintage look */
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-family: 'Georgia', serif;
    transition: background-color 0.3s ease, transform 0.3s ease;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
}

.buy-button:hover {
    background-color: #3e8e41;
    transform: scale(1.05);
}

/* Background Styling */
body {
    margin: 0;
    padding: 0;
    background-color: #ffffff;
    font-family: 'Georgia', serif;
    background-image: url('bus_2.jpg');
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: center top;
}





   
  </style>
  <link rel="icon" type="image/png" href="/ticket/432312.png">
</head>


<header>
  <a href="index.html" class="btn classic-btn">
    <span class="glyphicon glyphicon-log-out"></span> Log out
  </a>
  <div>
    <a href="profile.php" class="btn classic-btn">Profile</a>
  </div>
</header>


<body>
  <form class="travel-form" action="search.php" method="POST">
    <div class="travel-field">
      <label for="from">Going From</label>
      <select id="from" name="from">
        <option value="" disabled selected>Select a District</option>
        <option value="Bagerhat">Bagerhat</option>
        <option value="Bandarban">Bandarban</option>
        <option value="Barguna">Barguna</option>
        <option value="Barisal">Barisal</option>
        <option value="Bhola">Bhola</option>
        <option value="Bogra">Bogra</option>
        <option value="Brahmanbaria">Brahmanbaria</option>
        <option value="Chandpur">Chandpur</option>
        <option value="Chittagong">Chittagong</option>
        <option value="Chuadanga">Chuadanga</option>
        <option value="Comilla">Comilla</option>
        <option value="Cox's Bazar">Cox's Bazar</option>
        <option value="Dhaka">Dhaka</option>
        <option value="Dinajpur">Dinajpur</option>
        <option value="Faridpur">Faridpur</option>
        <option value="Feni">Feni</option>
        <option value="Gaibandha">Gaibandha</option>
        <option value="Gazipur">Gazipur</option>
        <option value="Gopalganj">Gopalganj</option>
        <option value="Habiganj">Habiganj</option>
        <option value="Jamalpur">Jamalpur</option>
        <option value="Jessore">Jessore</option>
        <option value="Jhalokati">Jhalokati</option>
        <option value="Jhenaidah">Jhenaidah</option>
        <option value="Joypurhat">Joypurhat</option>
        <option value="Khagrachari">Khagrachari</option>
        <option value="Khulna">Khulna</option>
        <option value="Kishoreganj">Kishoreganj</option>
        <option value="Kurigram">Kurigram</option>
        <option value="Kushtia">Kushtia</option>
        <option value="Lakshmipur">Lakshmipur</option>
        <option value="Lalmonirhat">Lalmonirhat</option>
        <option value="Madaripur">Madaripur</option>
        <option value="Magura">Magura</option>
        <option value="Manikganj">Manikganj</option>
        <option value="Meherpur">Meherpur</option>
        <option value="Moulvibazar">Moulvibazar</option>
        <option value="Munshiganj">Munshiganj</option>
        <option value="Mymensingh">Mymensingh</option>
        <option value="Naogaon">Naogaon</option>
        <option value="Narail">Narail</option>
        <option value="Narayanganj">Narayanganj</option>
        <option value="Narsingdi">Narsingdi</option>
        <option value="Natore">Natore</option>
        <option value="Netrokona">Netrokona</option>
        <option value="Nilphamari">Nilphamari</option>
        <option value="Noakhali">Noakhali</option>
        <option value="Pabna">Pabna</option>
        <option value="Panchagarh">Panchagarh</option>
        <option value="Patuakhali">Patuakhali</option>
        <option value="Pirojpur">Pirojpur</option>
        <option value="Rajbari">Rajbari</option>
        <option value="Rajshahi">Rajshahi</option>
        <option value="Rangamati">Rangamati</option>
        <option value="Rangpur">Rangpur</option>
        <option value="Satkhira">Satkhira</option>
        <option value="Shariatpur">Shariatpur</option>
        <option value="Sherpur">Sherpur</option>
        <option value="Sirajganj">Sirajganj</option>
        <option value="Sunamganj">Sunamganj</option>
        <option value="Sylhet">Sylhet</option>
        <option value="Tangail">Tangail</option>
        <option value="Thakurgaon">Thakurgaon</option>
      </select>
    </div>
    <div class="arrow">↔</div>
    <div class="travel-field">
      <label for="to">Going To</label>
      <select id="to" name="to">
        <option value="" disabled selected>Select a District</option>
        <!-- Reuse the same district options -->
        <option value="Bagerhat">Bagerhat</option>
        <option value="Bandarban">Bandarban</option>
        <option value="Barguna">Barguna</option>
        <option value="Barisal">Barisal</option>
        <option value="Bhola">Bhola</option>
        <option value="Bogra">Bogra</option>
        <option value="Brahmanbaria">Brahmanbaria</option>
        <option value="Chandpur">Chandpur</option>
        <option value="Chittagong">Chittagong</option>
        <option value="Chuadanga">Chuadanga</option>
        <option value="Comilla">Comilla</option>
        <option value="Cox's Bazar">Cox's Bazar</option>
        <option value="Dhaka">Dhaka</option>
        <option value="Dinajpur">Dinajpur</option>
        <option value="Faridpur">Faridpur</option>
        <option value="Feni">Feni</option>
        <option value="Gaibandha">Gaibandha</option>
        <option value="Gazipur">Gazipur</option>
        <option value="Gopalganj">Gopalganj</option>
        <option value="Habiganj">Habiganj</option>
        <option value="Jamalpur">Jamalpur</option>
        <option value="Jessore">Jessore</option>
        <option value="Jhalokati">Jhalokati</option>
        <option value="Jhenaidah">Jhenaidah</option>
        <option value="Joypurhat">Joypurhat</option>
        <option value="Khagrachari">Khagrachari</option>
        <option value="Khulna">Khulna</option>
        <option value="Kishoreganj">Kishoreganj</option>
        <option value="Kurigram">Kurigram</option>
        <option value="Kushtia">Kushtia</option>
        <option value="Lakshmipur">Lakshmipur</option>
        <option value="Lalmonirhat">Lalmonirhat</option>
        <option value="Madaripur">Madaripur</option>
        <option value="Magura">Magura</option>
        <option value="Manikganj">Manikganj</option>
        <option value="Meherpur">Meherpur</option>
        <option value="Moulvibazar">Moulvibazar</option>
        <option value="Munshiganj">Munshiganj</option>
        <option value="Mymensingh">Mymensingh</option>
        <option value="Naogaon">Naogaon</option>
        <option value="Narail">Narail</option>
        <option value="Narayanganj">Narayanganj</option>
        <option value="Narsingdi">Narsingdi</option>
        <option value="Natore">Natore</option>
        <option value="Netrokona">Netrokona</option>
        <option value="Nilphamari">Nilphamari</option>
        <option value="Noakhali">Noakhali</option>
        <option value="Pabna">Pabna</option>
        <option value="Panchagarh">Panchagarh</option>
        <option value="Patuakhali">Patuakhali</option>
        <option value="Pirojpur">Pirojpur</option>
        <option value="Rajbari">Rajbari</option>
        <option value="Rajshahi">Rajshahi</option>
        <option value="Rangamati">Rangamati</option>
        <option value="Rangpur">Rangpur</option>
        <option value="Satkhira">Satkhira</option>
        <option value="Shariatpur">Shariatpur</option>
        <option value="Sherpur">Sherpur</option>
        <option value="Sirajganj">Sirajganj</option>
        <option value="Sunamganj">Sunamganj</option>
        <option value="Sylhet">Sylhet</option>
        <option value="Tangail">Tangail</option>
        <option value="Thakurgaon">Thakurgaon</option>
      </select>
    </div>
    <div class="travel-field">
      <label for="date">Journey Date</label>
      <input type="date" id="date" name="date" class="form-control" required>

    </div>
	<br>
	

	 <button type="submit" class="submit-button">Search Bus</button>
  </form>
  <?php if (!empty($error)): ?>
    <div class="alert alert-danger mt-4 text-center"><?php echo $error; ?></div>
<?php endif; ?>

<?php if (!empty($searchResults)): ?>
    <div class="results-container">
        <h3 class="text-center mb-4">Available Buses</h3>
        <table class="results-table">
            <!-- Table Header -->
            <thead>
                <tr>
                    <th>Bus Name</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Date</th>
                    <th>Departure</th>
                    <th>Arrival</th>
                    <th>Available Seats</th>
                    <th>Duration</th>
					<th>Ticket Price</th>
                </tr>
            </thead>
            <!-- Table Body -->
            <tbody>
                <?php foreach ($searchResults as $bus): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($bus['bus_name']); ?></td>
                        <td><?php echo htmlspecialchars($bus['source_location']); ?></td>
                        <td><?php echo htmlspecialchars($bus['destination_location']); ?></td>
                        <td><?php echo htmlspecialchars($bus['date']); ?></td>
                        <td><?php echo htmlspecialchars($bus['departure_time']); ?></td>
                        <td><?php echo htmlspecialchars($bus['arrival_time']); ?></td>
                        <td><?php echo htmlspecialchars($bus['available_seats']); ?></td>
                        <td><?php echo htmlspecialchars($bus['duration']); ?></td>
						<td><?php echo htmlspecialchars($bus['ticket_price']); ?></td>
						<td>
                            <form action="select_bus.php" method="POST">
                                <input type="hidden" name="bus_id" value="<?php echo htmlspecialchars($bus['bus_id']); ?>">
                                <input type="hidden" name="date" value="<?php echo htmlspecialchars($bus['date']); ?>">
                                <button type="submit" class="buy-button">Select Bus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>


</body>
</html>

