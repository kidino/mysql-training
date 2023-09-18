<?php
// Include your database connection and configuration here
require_once 'config.php';

try {
    // Check if actor_id is provided in the query string
    if (isset($_GET['actor_id'])) {
        $actor_id = (int)$_GET['actor_id'];

        // Query to retrieve actor details by actor_id
        $actor_sql = "SELECT actor_id, first_name, last_name
                      FROM actor
                      WHERE actor_id = :actor_id";
        $actor_stmt = $pdo->prepare($actor_sql);
        $actor_stmt->bindParam(':actor_id', $actor_id, PDO::PARAM_INT);
        $actor_stmt->execute();

        // Fetch the actor details
        $actor = $actor_stmt->fetch(PDO::FETCH_ASSOC);

        // Query to retrieve films in which the actor is involved
        $films_sql = "SELECT f.film_id, f.title
                      FROM film AS f
                      INNER JOIN film_actor AS fa ON f.film_id = fa.film_id
                      WHERE fa.actor_id = :actor_id";
        $films_stmt = $pdo->prepare($films_sql);
        $films_stmt->bindParam(':actor_id', $actor_id, PDO::PARAM_INT);
        $films_stmt->execute();

        // Fetch the list of films
        $films = $films_stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if the actor exists
        if ($actor) {
            // Display actor details
            include('menu.php');
            echo "<p><strong>Actor Info</strong></p>";
            echo "<h1>{$actor['first_name']} {$actor['last_name']}</h1>";

            // Display the list of films the actor is involved in
            if (!empty($films)) {
                echo "<h2>Films Involved:</h2>";
                echo "<ul>";
                foreach ($films as $film) {
                    echo "<li><a href=\"film_info.php?film_id={$film['film_id']}\">{$film['title']}</a></li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No films found for this actor.</p>";
            }
        } else {
            echo "<p>Actor not found.</p>";
        }
    } else {
        echo "<p>Invalid actor ID.</p>";
    }
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
?>
