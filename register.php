<?php
session_start();
require_once 'config.php'; // Include the database configuration file

function registerUser($conn, $username, $email, $password) {
    // Check if the email or username already exists
    $query = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
    $query->bind_param('ss', $email, $username);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        // Email or username already exists
        return "Email or username already exists.";
    }

    // Insert new user into the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $query = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $query->bind_param('sss', $username, $email, $hashed_password);

    if ($query->execute()) {
        // Automatically sign in the user
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        header("Location: website.html");
        exit();
    } else {
        return "Registration failed, please try again.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Register the user
        $error_message = registerUser($conn, $username, $email, $password);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #18181b;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #1f1f23;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        .container h2 {
            margin-bottom: 20px;
        }
        .container form {
            display: flex;
            flex-direction: column;
        }
        .container form input {
            padding: 10px;
            margin-bottom: 10px;
            border: none;
            border-radius: 4px;
        }
        .container form button {
            padding: 10px;
            background-color: #9147ff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .container form button:hover {
            background-color: #772ce8;
        }
        .error-message {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <?php if (!empty($error_message)) { echo '<p class="error-message">' . $error_message . '</p>'; } ?>
        <form method="POST" action="register.php">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>

