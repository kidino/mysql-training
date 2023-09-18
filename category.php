<?php
// Include your database connection and configuration here
require_once 'config.php';

try {
    // Query to retrieve all film categories
    $categories_sql = "SELECT category_id, name FROM category";
    $categories_stmt = $pdo->prepare($categories_sql);
    $categories_stmt->execute();

    // Fetch the list of categories
    $categories = $categories_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Display the list of categories
    include('menu.php');
    echo "<h1>Film Categories</h1>";
    echo "<ul>";
    foreach ($categories as $category) {
        $category_id = $category['category_id'];
        $category_name = $category['name'];
        echo "<li><a href='category_films.php?category_id={$category_id}'>{$category_name}</a></li>";
    }
    echo "</ul>";
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
?>
