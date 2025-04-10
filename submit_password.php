
<?php
session_start(); // Always start the session at the beginning of the script
include 'dbms.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize input to prevent XSS
    $email = htmlspecialchars($email);
    $password = htmlspecialchars($password);

    // Use prepared statements to prevent SQL injection
    $sql = "SELECT user_id, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // Bind the email as a string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Check if the password matches (assuming the password is hashed in the database)
        if ($password === $user['password']) { // Replace with `password_verify($password, $user['password'])` if hashing is used
            // Store user_id in session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $email; // Optional: Store email for additional use

            // Redirect to search page
            header("Location: search.php");
            exit();
        } else {
            // Incorrect password
            header("Location: wrong.php");
            echo '<script>alert("Wrong password. Please try again.");</script>';
        }
    } else {
        // User not found
        header("Location: wrong.php");
        echo '<script>alert("User not found. Please check your email or password.");</script>';
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>


