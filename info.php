<?php
// Include database connection file
include 'dbms.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize user input
    $from = htmlspecialchars($_POST['from']);
    $to = htmlspecialchars($_POST['to']);
    $date = htmlspecialchars($_POST['date']);

    // Prepare the SQL query to fetch buses based on user input
    $sql = "SELECT * FROM route WHERE source_location = ? AND destination_location = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $from, $to); // Bind parameters to avoid SQL injection
    $stmt->execute();
    $result = $stmt->get_result();

    // Display results
    if ($result->num_rows > 0) {
        echo "<h2>Available Buses</h2>";
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>Bus Name</th><th>Departure</th><th>Arrival</th><th>Fare</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['bus_id']}</td>
                    <td>{$row['departure_time']}</td>
                    <td>{$row['arrival_time']}</td>
                    <td>{$row['fare']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No buses found for the selected route.</p>";
    }

    // Close connection
    $stmt->close();
    $conn->close();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Travel Planner</title>
  <style>
    body {
 background-image: url('tourist.jpg');
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-position: center top;
 
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      background-color: #f7f7f7;
    }
    .travel-form {
      display: flex;
      gap: 10px;
      border: 1px solid #e0e0e0;
      padding: 10px;
      border-radius: 8px;
      background: white;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .travel-field {
      display: flex;
      flex-direction: column;
    }
    .travel-field label {
      font-size: 12px;
      color: #a0a0a0;
      margin-bottom: 4px;
    }
    .travel-field select, .travel-field input {
      border: 1px solid #d0d0d0;
      border-radius: 4px;
      padding: 8px;
      font-size: 14px;
      width: 200px;
    }
    .arrow {
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      color: #ff4d4f;
    }
    select:focus {
      outline: 2px solid #ff4d4f;
    }
	.submit-button {
      align-self: flex-end;
      padding: 10px 20px;
      font-size: 14px;
      color: white;
      background-color: #ff4d4f;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
	header {
      position: absolute;
      top: 0;
      width: 100%;
      padding: 10px 20px;
      background: rgba(255, 255, 255, 0.9);
      display: flex;
      justify-content: flex-end;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .profile-button {
      display: inline-block; 
	  padding: 15px 30px;
	  background-color: #FF6347 /* Modern blue color */ 
	  color: white; 
	  border-radius: 25px; 
	  font-family: serif;
	  font-weight: bold; 
	  text-align: center; 
	  text-decoration: none; 
	  transition: background-color 0.3s, 
	  box-shadow 0.3s, transform 0.3s; 
	  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */ 
	  cursor: pointer;
    }

    .profile-button:hover {
      background-color: #d9363e;
    }

   
  </style>
</head>
<header>
    <a href="index.html" class="profile-button">
      <span class="profile-icon"></span>
      Log out
    </a>
  </header>

<body>
  <form class="travel-form" action="info.php" method="POST">
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
      <input type="date" id="date" />
    </div>
	 <button type="submit" class="submit-button">Search Bus</button>
  </form>
</body>
</html>

