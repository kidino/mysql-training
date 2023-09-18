<?php
// Database configuration
require_once 'config.php';

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (!empty($username) && !empty($password)) {
        // Prepare an SQL statement to fetch user data from the database
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->execute([$username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['loggedin'] = true;
            $msg = urlencode("Login successful. Welcome $user[username].");
            header('Location: actor_list.php?message='.$msg);
            // You can redirect the user to a dashboard or another page here.
        } else {
            $msg = urlencode("Login failed. Invalid username or password.");
            header('Location: login.php?error='.$msg);
        }
    } else {
        $msg = urlencode("Please fill out both username and password fields.");
        header('Location: login.php?error='.$msg);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth">
    <h1>User Login</h1>

    <?php if ( isset($_GET['error']) ) : ?>
        <p class="alert"><?php echo $_GET['error']?></p>
    <?php endif; ?>
        
    <?php if ( isset($_GET['message']) ) : ?>
        <p class="message"><?php echo $_GET['message']?></p>
    <?php endif; ?>

    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br><br>
        
        <label for="password">Password:</label>
        <input type="password" name="password" required><br><br>
        
        <input type="submit" name="submit" value="Login">
    </form>
</body>
</html>
