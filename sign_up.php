<?php
session_start(); 
// Include database connection file
include 'dbms.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $first_name = htmlspecialchars($_POST['fname']);
    $last_name = htmlspecialchars($_POST['lname']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
	$City = ($_POST['city']);
	$Country = ($_POST['country']);
	

    // Hash the password for security
    //$password_hashed = md5($password); // Consider using a stronger hashing algorithm like bcrypt in production

    // SQL query to insert user data into the database
    $sql = "INSERT INTO new_users (first_name, last_name, email, password, city, country) VALUES ('$first_name', '$last_name', '$email', '$password', '$City', '$Country')";


    if ($conn->query($sql) === TRUE) {
       header("Location: index.html"); 
       
		$sql_insert_user_credentials = "INSERT INTO users (email, password) VALUES ('$email', '$password')";
       
	   if ($conn->query($sql_insert_user_credentials) === TRUE)
		   { 
	   
	   header("Location: index.html"); 
       $_SESSION['message'] = "Registration Successful! Please log in.";
	   exit(); }

		
     else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
	
	}
    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Create an Account</title>
	<link rel="icon" type="image/png" href="/ticket/432312.png">
   <style>
   body {
  background-image: url('back.png');
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-position: bottom left;
  font-family: "Lucida Console", "Courier New", monospace;
  margin: 0;
  padding: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}
    .container {
        background-color: white;
        padding: 30px 40px;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        width: 350px;
        text-align: center;
        animation: fadeIn 0.8s ease-in-out;
    }
    .container h3 {
        font-size: 20px;
        margin-bottom: 20px;
        font-weight: normal;
        color: #5F9EA0;
    }
    form {
        display: flex;
        flex-direction: column;
    }
    form label {
        text-align: left;
        font-size: 14px;
        color: #666;
        margin-bottom: 5px;
    }
    form input[type="text"], form input[type="email"], form input[type="password"] {
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        width: 100%;
        box-sizing: border-box;
        font-size: 14px;
        transition: box-shadow 0.3s ease, border-color 0.3s ease;
    }
    form input[type="text"]:focus, form input[type="email"]:focus, form input[type="password"]:focus {
        border-color: #4CAF50;
        box-shadow: 0 0 8px rgba(76, 175, 80, 0.3);
        outline: none;
    }
    form input[type="submit"] {
        background: linear-gradient(135deg, #74ebd5, #acb6e5);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px 20px;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
        transition: background 0.3s ease, transform 0.2s ease;
    }
    form input[type="submit"]:hover {
        background: linear-gradient(135deg, #45a049, #5daa5d);
        transform: translateY(-2px);
    }
    form input[type="submit"]:active {
        transform: translateY(1px);
    }
    h4 {
        margin-top: 15px;
        font-size: 14px;
        color: #555;
    }
    h4 a {
        color: #4CAF50;
        text-decoration: none;
        font-weight: bold;
    }
    h4 a:hover {
        text-decoration: underline;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

</head>
<body>
    <div class="container">
        <h3><b>Create an account<br></b></h3>
        <form action="sign_up.php" method="POST">
            <label for="fname">First Name</label>
            <input type="text" id="fname" name="fname" required>
            <label for="lname">Last Name</label>
            <input type="text" id="lname" name="lname" required>
            <label for="city">City</label>
            <input type="text" id="city" name="city">
            <label for="country">Country</label>
            <input type="text" id="country" name="country">
            <label for="email">Enter Email Address</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Enter Password</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Sign Up">
        </form>
    </div>
</body>
</html>
