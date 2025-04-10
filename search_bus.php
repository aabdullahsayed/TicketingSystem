<?php
// Include database connection file
include 'dbms.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize user input
    $from = htmlspecialchars($_POST['from']);
    $to = htmlspecialchars($_POST['to']);
   // $date = htmlspecialchars($_POST['date']);

    // Prepare the SQL query to fetch buses based on user input
    $sql = "SELECT * FROM buses WHERE from_location = '$from' AND to_location = '$to' ";
    $stmt = $conn->prepare($sql);
   // $stmt->bind_param("ss", $from, $to); // Bind parameters to avoid SQL injection
    $stmt->execute();
    $result = $stmt->get_result();

    // Display results
    if ($result->num_rows > 0) {
        echo "<h2>Available Buses</h2>";
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>Bus Name</th><th>Departure</th><th>Arrival</th><th>Fare</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['bus_name']}</td>
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
