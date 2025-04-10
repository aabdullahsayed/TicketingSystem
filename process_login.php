<?php
include 'dbms.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Sanitize input to prevent XSS
    $email = htmlspecialchars($email);
    $password = htmlspecialchars($password);


    // Hash the password for security
    //$password_hashed = md5($password); // Consider using a stronger hashing algorithm in production
	
    // Check credentials in the database
    $sql = "SELECT * FROM admin WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Successfully authenticated
        echo "Login successful!";
        // Redirect to another page or perform further actions
		header("Location:admin.php");
		exit();
    } else {
        // Invalid credentials
      //  echo "Invalid email or password.";
	  //echo '<script>alert("Wrong password or email. Please try again.");</script>';
	  header("Location:admin_login.php");
	   echo '<script>alert("Wrong password or email. Please try again.");</script>';
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Error</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      color: #333;
    }
    .error-message {
      background: #ffdddd;
      padding: 20px;
      border: 1px solid #ff5e5e;
      border-radius: 8px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="error-message">
    <h3>Login Error</h3>
    <p><?php echo isset($error_message) ? $error_message : "Unknown error occurred."; ?></p>
    <a href="admin_login.php" style="color: #6a11cb; text-decoration: none;">Go back to login</a>
  </div>
</body>
</html>
