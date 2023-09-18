<?php
// Include your database connection and configuration here
require_once 'config.php';

// Calculate the current page number from the query string
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 20;

// Calculate the starting record for the current page
$start = ($current_page - 1) * $records_per_page;

try {
    // Query to retrieve films with pagination
    $sql = "SELECT film_id, title FROM film LIMIT :start, :per_page";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':start', $start, PDO::PARAM_INT);
    $stmt->bindParam(':per_page', $records_per_page, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch films
    $films = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Query to count total films
    $count_sql = "SELECT COUNT(*) FROM film";
    $total_films = $pdo->query($count_sql)->fetchColumn();

} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Film List</title>
</head>
<body>
<?php include('menu.php'); ?>
    <h1>Film List</h1>
    <ul>
        <?php foreach ($films as $film): ?>
            <li><a href="film_info.php?film_id=<?php echo $film['film_id']?>"><?php echo $film['title']; ?></a></li>
        <?php endforeach; ?>
    </ul>

    <!-- Pagination Links -->
    <div class="pagination">
        <?php
        $total_pages = ceil($total_films / $records_per_page);
        for ($i = 1; $i <= $total_pages; $i++) {
            echo '<a href="?page=' . $i . '">' . $i . '</a> ';
        }
        ?>
    </div>
</body>
</html>
