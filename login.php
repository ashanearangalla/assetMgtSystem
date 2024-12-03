<?php
session_start();
$host = 'localhost'; // Database host
$username = 'root'; // Database username
$password = ''; // Database password
$database = 'snipeit_database'; // Database name

// Create a connection to the MySQL database
$conn = new mysqli($host, $username, $password, $database);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// PHP Validation for login (server-side validation)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];  // Using email as username
    $password = $_POST['password'];
    $errors = [];

    // Check if email (username) is empty
    if (empty($username)) {
        $errors[] = "Email is required.";
    }

    // Check if password is empty
    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    // Proceed only if no validation errors
    if (empty($errors)) {
        // Verify email and password from the database
        $query = "SELECT * FROM user WHERE email = ? LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            // Check if login is enabled
            if ($user['loginEnabled'] == 1) {
                // Verify the password (assuming passwords are stored hashed)
                if (password_verify($password, $user['password'])) {
                    // Successful login, set session variables
                    $_SESSION["user"] = [
                        "userID" => htmlspecialchars($user["userID"]),
                        "fname" => htmlspecialchars($user["fname"]),
                        "lname" => htmlspecialchars($user["lname"]),
                        "role" => htmlspecialchars($user["role"]),
                        "email" => htmlspecialchars($user["email"]),
                        "image" => htmlspecialchars($user["image"]),
                        "departmentID" => htmlspecialchars($user["departmentID"]),
                        "statusID" => htmlspecialchars($user["statusID"]),
                        "locationID" => htmlspecialchars($user["locationID"]),
                    ];

                    // Redirect to dashboard
                    header('Location: dashboard.php');
                    exit;
                } else {
                    // Incorrect password
                    $errors[] = "Incorrect password.";
                }
            } else {
                // User is disabled (loginEnabled == 0)
                $errors[] = "Your account is disabled. Please contact the administrator.";
            }
        } else {
            // Incorrect email
            $errors[] = "Email does not exist.";
        }
    }

    // If there are errors, store them in the session to display
    if (!empty($errors)) {
        $_SESSION["errors_login"] = $errors;
        header('Location: login.php'); // Redirect back to login page
        exit;
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="./assets/css/loginstyle.css">
   
</head>
<body>
    <div class="login-container">
        <h1>Snipe IT - Assets Management System</h1>
        <h2>Login</h2>
        <form id="login-form" action="login.php" method="POST">
            <input type="text" id="username" name="username" placeholder="Email">
            <input type="password" id="password" name="password" placeholder="Password">
            <div class="error" id="error">
                <?php
                if (isset($_SESSION["errors_login"])) {
                    $errors = $_SESSION["errors_login"];
                    foreach ($errors as $error) {
                        echo '<p>' . $error . '</p>';
                    }
                    unset($_SESSION["errors_login"]); // Clear errors after displaying
                }
                ?>
            </div>
            <button type="submit">LOGIN</button>
        </form>
    </div>

    <div class="text-container">
        <p>Copyright © 2024 | <a href="https://snipeitapp.com/" target="_blank">SnipeIT</a> All Rights Reserved.</p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('login-form');
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');
            const errorDiv = document.getElementById('error');

            form.addEventListener('submit', function (event) {
                let valid = true;
                let errorMessage = '';

                // Clear previous error messages
                errorDiv.innerHTML = '';

                // Validate username and password
                if (usernameInput.value.trim() === '' && passwordInput.value.trim() === '') {
                    errorMessage = 'Enter both username and password.';
                    valid = false;
                } else if (usernameInput.value.trim() === '') {
                    errorMessage = 'Username is required.';
                    valid = false;
                } else if (passwordInput.value.trim() === '') {
                    errorMessage = 'Password is required.';
                    valid = false;
                }

                // Display error message and prevent form submission if validation fails
                if (!valid) {
                    errorDiv.innerHTML = errorMessage;
                    event.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
