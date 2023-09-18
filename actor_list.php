<?php
// Include your database connection and configuration here
require_once 'config.php';
require_once 'protected.php';
?>
<html><head><title>Actor List</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<p><a href="logout.php">LOGOUT</a></p>
<?php
try {
    // Define an SQL query to retrieve actor names
    $sql = "SELECT actor_id, first_name, last_name FROM actor";

    // Create a PDO statement with the SQL query
    $stmt = $pdo->prepare($sql);

    // Execute the query
    $stmt->execute();

    // Fetch all rows as an associative array
    $actors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if there are actors
    if (!empty($actors)) {
        echo "<h1>List of Actors</h1>";
        echo "<table>";
        echo "<tr><th>ID</th><th>First Name</th><th>Last Name</th></tr>";

        // Iterate through the results and display each actor's name
        foreach ($actors as $actor) {
            echo "<tr>";
            echo "<td>{$actor['actor_id']}</td>";
            echo "<td>{$actor['first_name']}</td>";
            echo "<td>{$actor['last_name']}</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No actors found.</p>";
    }
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
?>
</body>
</html>