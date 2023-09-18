<?php
// Include your database connection and configuration here
require_once 'config.php';

try {
    // Pagination settings
    $records_per_page = 20;
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $start = ($current_page - 1) * $records_per_page;

    // Query to retrieve actors with pagination
    $actors_sql = "SELECT actor_id, first_name, last_name
                  FROM actor
                  LIMIT :start, :per_page";
    $actors_stmt = $pdo->prepare($actors_sql);
    $actors_stmt->bindParam(':start', $start, PDO::PARAM_INT);
    $actors_stmt->bindParam(':per_page', $records_per_page, PDO::PARAM_INT);
    $actors_stmt->execute();

    // Fetch the list of actors
    $actors = $actors_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Query to count total actors
    $count_sql = "SELECT COUNT(*) FROM actor";
    $count_stmt = $pdo->prepare($count_sql);
    $count_stmt->execute();
    $total_actors = $count_stmt->fetchColumn();

    // Display the list of actors
    include('menu.php');
    echo "<h1>Actor List</h1>";
    echo "<ul>";
    foreach ($actors as $actor) {
        $actor_id = $actor['actor_id'];
        $actor_name = "{$actor['first_name']} {$actor['last_name']}";
        echo "<li><a href='actor_info.php?actor_id={$actor_id}'>{$actor_name}</a></li>";
    }
    echo "</ul>";

    // Pagination links
    $total_pages = ceil($total_actors / $records_per_page);
    if ($total_pages > 1) {
        echo "<div class='pagination'>";
        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i === $current_page) {
                echo "<span>{$i}</span> ";
            } else {
                echo "<a href='actor.php?page={$i}'>{$i}</a> ";
            }
        }
        echo "</div>";
    }
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
?>
