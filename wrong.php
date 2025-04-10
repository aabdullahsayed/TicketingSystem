<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Buy Ticket</title>
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
  text-align: center;
  background-color: white;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  padding: 20px;
  width: 300px;
}

.container h3 {
  margin-bottom: 20px;
  font-weight: lighter;
}

form {
  text-align: left;
}

form label {
  display: block;
  margin-bottom: 8px;
  font-weight: lighter;
}

form input[type="text"], form input[type="password"] {
  width: 100%;
  padding: 8px;
  margin-bottom: 16px;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-family: inherit;
}

form input[type="submit"] {
  width: 100%;
  padding: 10px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 16px;
}

form input[type="submit"]:hover {
  background-color: #45a049;
}

h4 {
  font-weight: lighter;
  margin-top: 16px;
}

h4 a {
  color: #007BFF;
  text-decoration: none;
}

h4 a:hover {
  text-decoration: underline;
}

.admin-login-btn {
  position: fixed;
  top: 20px;
  right: 20px;
  padding: 12px 25px;
  background: linear-gradient(45deg, #ff5f6d, #ffc3a0);
  color: white;
  border: none;
  border-radius: 30px;
  font-size: 18px;
  font-weight: 600;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.admin-login-btn:hover {
  background: linear-gradient(45deg, #ff2a68, #ff9f86);
  transform: scale(1.05);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
}

.admin-login-btn:active {
  transform: scale(1.02);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.error-message {
  background-color: red;
  color: white;
  padding: 10px;
  margin-top: 10px;
  border-radius: 4px;
}
</style>
</head>
<link rel="icon" type="image/png" href="/ticket/432312.png">
<body>

<div class="container">

  <h3>Sign in to continue</h3>

  <div class="error-message">Wrong password or email<br> try again</div>

  <form action="/ticket/submit_password.php" method="POST">
    <label for="email">Email</label>
    <input type="text" id="email" name="email">
    
    <label for="password">Password</label>
    <input type="password" id="password" name="password">
    
    <input type="submit" value="Login">
  </form>
  <h4>Don't have an account? <a href="/ticket/sign_up.php">Sign Up</a></h4>
  <button class="admin-login-btn" onclick="window.location.href='/ticket/admin_login.php'">Admin Login</button>

</div>
</body>
</html>
