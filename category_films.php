<?php
// Include your database connection and configuration here
require_once 'config.php';

try {
    // Check if category_id is provided in the query string
    if (isset($_GET['category_id'])) {
        $category_id = (int)$_GET['category_id'];

        // Pagination settings
        $records_per_page = 20;
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $start = ($current_page - 1) * $records_per_page;

        // Query to retrieve category name by category_id
        $category_sql = "SELECT name FROM category WHERE category_id = :category_id";
        $category_stmt = $pdo->prepare($category_sql);
        $category_stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $category_stmt->execute();

        // Fetch the category name
        $category = $category_stmt->fetch(PDO::FETCH_ASSOC);

        // Query to retrieve films in the specified category with pagination
        $films_sql = "SELECT f.film_id, f.title
                      FROM film AS f
                      INNER JOIN film_category AS fc ON f.film_id = fc.film_id
                      WHERE fc.category_id = :category_id
                      LIMIT :start, :per_page";
        $films_stmt = $pdo->prepare($films_sql);
        $films_stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $films_stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $films_stmt->bindParam(':per_page', $records_per_page, PDO::PARAM_INT);
        $films_stmt->execute();

        // Fetch the list of films
        $films = $films_stmt->fetchAll(PDO::FETCH_ASSOC);

        // Query to count total films in the category
        $count_sql = "SELECT COUNT(*) FROM film AS f
                      INNER JOIN film_category AS fc ON f.film_id = fc.film_id
                      WHERE fc.category_id = :category_id";
        $count_stmt = $pdo->prepare($count_sql);
        $count_stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $count_stmt->execute();
        $total_films = $count_stmt->fetchColumn();

        // Check if the category exists
        if ($category) {
            // Display category name
            include('menu.php');
            echo "<p><strong>Film Category</strong></p>";
            echo "<h1>Films in Category: {$category['name']}</h1>";

            // Display the list of films in the category
            if (!empty($films)) {
                echo "<ul>";
                foreach ($films as $film) {
                    echo "<li><a href=\"film_info.php?film_id={$film['film_id']}\">{$film['title']}</a></li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No films found in this category.</p>";
            }

            // Pagination links
            $total_pages = ceil($total_films / $records_per_page);
            if ($total_pages > 1) {
                echo "<div class='pagination'>";
                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i === $current_page) {
                        echo "<span>{$i}</span> ";
                    } else {
                        echo "<a href='category_films.php?category_id={$category_id}&page={$i}'>{$i}</a> ";
                    }
                }
                echo "</div>";
            }
        } else {
            echo "<p>Category not found.</p>";
        }
    } else {
        echo "<p>Invalid category ID.</p>";
    }
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
?>
