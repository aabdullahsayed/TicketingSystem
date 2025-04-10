<?php
session_start(); // Start the session at the beginning of the script
include('dbms.php'); // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve email and password from the POST request
    $email = trim($_POST['email']); // Trim whitespace from email
    $password = $_POST['password']; // No trim for password to preserve formatting

    // Prepare the query to fetch user details
    $query = "SELECT password FROM new_users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email); // Bind email to the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify the provided password against the hashed password in the database
        if (password_verify($password, $user['password'])) {
            // Store user_id in the session
            $_SESSION['user_id'] = $user['user_id'];
            
            // Redirect to the dashboard or homepage
            header("Location: index.php");
            exit;
        } else {
            // Invalid password
            $error_message = "Invalid email or password.";
        }
    } else {
        // User not found
        $error_message = "User not found.";
    }

    $stmt->close(); // Close the prepared statement
}
$conn->close(); // Close the database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f5f5f5;
        }
        .login-container {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background: #45a049;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>

        <!-- Display error message if login fails -->
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <!-- Login form -->
        <form method="POST" action="">
            <input type="text" name="email" placeholder="Enter your email" required>
            <input type="password" name="password" placeholder="Enter your password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
