<?php
// Include your database connection and configuration here
require_once 'config.php';

// Define a function to register a new user
function registerUser($pdo, $username, $password) {
    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare an SQL statement to insert user data into the database
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");

    // Execute the statement
    if ($stmt->execute([$username, $hashedPassword])) {
        $msg = urlencode('Registration was successful. You may proceed to login.');
        header('Location: login.php?message='.$msg);
    } else {
        $msg = urlencode($stmt->error);
        header('Location: register.php?error='.$msg);
    }
}

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (!empty($username) && !empty($password)) {
        registerUser($pdo, $username, $password);
    } else {
        $msg = urlencode('Please complete all fields in the registration form.');
        header('Location: register.php?error='.$msg);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth">
    <h1>User Registration</h1>
    <?php if ( isset($_GET['error']) ) : ?>
        <p class="alert"><?php echo $_GET['error']?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br><br>
        
        <label for="password">Password:</label>
        <input type="password" name="password" required><br><br>
        
        <input type="submit" name="submit" value="Register">
    </form>
</body>
</html>
