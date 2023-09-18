<?php
// Include your database connection and configuration here
require_once 'config.php';

try {
    // Check if film_id is provided in the query string
    if (isset($_GET['film_id'])) {
        $film_id = (int)$_GET['film_id'];

        // Query to retrieve film details by film_id
        $film_sql = "SELECT f.film_id, f.title, f.description, f.release_year, f.rental_rate, GROUP_CONCAT(c.name) AS categories
                     FROM film AS f
                     INNER JOIN film_category AS fc ON f.film_id = fc.film_id
                     INNER JOIN category AS c ON fc.category_id = c.category_id
                     WHERE f.film_id = :film_id
                     GROUP BY f.film_id";
        $film_stmt = $pdo->prepare($film_sql);
        $film_stmt->bindParam(':film_id', $film_id, PDO::PARAM_INT);
        $film_stmt->execute();

        // Fetch the film details, including categories
        $film = $film_stmt->fetch(PDO::FETCH_ASSOC);

        // Query to retrieve actors for the film
        $actors_sql = "SELECT a.actor_id, a.first_name, a.last_name
                       FROM actor AS a
                       INNER JOIN film_actor AS fa ON a.actor_id = fa.actor_id
                       WHERE fa.film_id = :film_id";
        $actors_stmt = $pdo->prepare($actors_sql);
        $actors_stmt->bindParam(':film_id', $film_id, PDO::PARAM_INT);
        $actors_stmt->execute();

        // Fetch the list of actors
        $actors = $actors_stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if the film exists
        if ($film) {
            // Display film details
            include('menu.php');
            echo "<p><strong>Film Info</strong></p>";
            echo "<h1>{$film['title']}</h1>";
            echo "<p><strong>Description:</strong> {$film['description']}</p>";
            echo "<p><strong>Release Year:</strong> {$film['release_year']}</p>";
            echo "<p><strong>Rental Rate:</strong> {$film['rental_rate']}</p>";

            // Display film categories
            if (!empty($film['categories'])) {
                $categories = explode(',', $film['categories']);
                echo "<p><strong>Categories:</strong> " . implode(', ', $categories) . "</p>";
            } else {
                echo "<p>No categories found for this film.</p>";
            }

            // Display the list of actors
            if (!empty($actors)) {
                echo "<h2>Actors:</h2>";
                echo "<ul>";
                foreach ($actors as $actor) {
                    echo "<li><a href=\"actor_info.php?actor_id={$actor['actor_id']}\">{$actor['first_name']} {$actor['last_name']}</a></li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No actors found for this film.</p>";
            }
        } else {
            echo "<p>Film not found.</p>";
        }
    } else {
        echo "<p>Invalid film ID.</p>";
    }
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
?>
