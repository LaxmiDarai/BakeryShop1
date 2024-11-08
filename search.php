<?php
include 'db_connection.php'; // Include your database connection file

if (isset($_GET['query'])) {
    $searchQuery = mysqli_real_escape_string($db, $_GET['query']); // Sanitize user input

    // Create the SQL query to get all rows from the table
    $sql = "SELECT * FROM dishes";
    $result = mysqli_query($db, $sql);

    echo "<h1>Search Results for '$searchQuery':</h1>";

    if (mysqli_num_rows($result) > 0) {
       
        $dishes = mysqli_fetch_all($result, MYSQLI_ASSOC);

       
        function linearSearch($array, $query) {
            $matches = [];
            foreach ($array as $item) {
                if (stripos($item['title'], $query) !== false || stripos($item['slogan'], $query) !== false) {
                    $matches[] = $item; 
            }
            return $matches;
        }

        // Perform linear search
        $matches = linearSearch($dishes, $searchQuery);

        if (!empty($matches)) {
            echo "<ul>";
            foreach ($matches as $match) {
                echo "<li>" . $match['title'] . " - " . $match['slogan'] . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No results found for '$searchQuery'</p>";
        }
    } else {
        echo "<p>No results found for '$searchQuery'</p>";
    }
} else {
    echo "<p>Please enter a search query.</p>";
}

// Close the database connection
mysqli_close($db);
?>
