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
    $sql = "SELECT id, password FROM admin WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // Bind the email as a string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        // Check if the password matches (assuming the password is hashed in the database)
        if ($password === $admin['password']) { // Replace with `password_verify($password, $admin['password'])` if hashing is used
            // Store admin id in session
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['email'] = $email; // Optional: Store email for additional use

            // Redirect to admin page
            header("Location: admin.php");
            exit();
        } else {
            // Incorrect password
            echo '<script>alert("Wrong password. Please try again.");</script>';
        }
    } else {
        // Admin not found
        echo '<script>alert("Admin not found. Please check your email or password.");</script>';
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login</title>
  <style>
/* General Reset */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  background-image: url('back.png');
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-position: bottom left;
  font-family: "Georgia", serif;
  margin: 0;
  padding: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  overflow: hidden; /* Prevent scrolling during load */
  opacity: 0; /* Start with the body hidden */
  animation: fadeIn 0.5s ease-in forwards; /* Shorter fade-in duration */
}

@keyframes fadeIn {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}

.login-container {
  background: transparent;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
  width: 100%;
  max-width: 380px;
  text-align: center;
  color: #2f4f4f;
  border: 1px solid rgba(79, 121, 66, 0.4);
  opacity: 0; /* Start hidden */
  animation: fadeInContent 0.5s ease-in-out forwards 0.5s; /* Faster fade-in */
}

@keyframes fadeInContent {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}

form {
  display: flex;
  flex-direction: column;
}

.form-group {
  margin-bottom: 12px;
}

.form-group label {
  font-size: 14px;
  color: #556b2f;
  margin-bottom: 5px;
  text-align: left;
}

.form-group input {
  width: 100%;
  padding: 10px;
  border: 1px solid #8fbc8f;
  border-radius: 5px;
  font-size: 14px;
  background-color: #ffffff;
  transition: all 0.2s ease;
}

.form-group input:focus {
  border-color: #4f7942;
  box-shadow: 0 0 5px rgba(79, 121, 66, 0.2);
  outline: none;
}

.btn {
  background: #6b8e23;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  font-weight: 600;
  text-transform: uppercase;
  transition: background 0.2s ease;
}

.btn:hover {
  background: #556b2f;
}

.forgot-password {
  margin-top: 8px;
  font-size: 14px;
  color: #4f7942;
  text-decoration: none;
}

.forgot-password:hover {
  color: #3b5323;
}

/* Loading Spinner */
.loader {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  border: 4px solid #f3f3f3; /* Light gray border */
  border-top: 4px solid #6b8e23; /* Green spinner */
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin 0.6s linear infinite; /* Faster spinner */
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}





  </style>
  <link rel="icon" type="image/png" href="/ticket/432312.png">
</head>
<body>
  <div class="login-container">
    <h2>Admin Login</h2>
    <form action="admin_login.php" method="POST">
      <div class="form-group">
        <label for="email">Email</label>
        <input type="text" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit" class="btn">Login</button>
    </form>
    <a href="#" class="forgot-password">Forgot Password?</a>
  </div>
</body>
</html>

