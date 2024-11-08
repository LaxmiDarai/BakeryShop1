<?php

//main connection file for both admin & front end
$servername = "localhost"; //server
$username = "root"; //username
$password = ""; //password
$dbname = "bakery_shop"; 


$db = mysqli_connect($servername, $username, $password, $dbname); 

if (!$db) {       
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['search'])) {
    
    $searchTerm = $_POST['search'];

    // Fetch all products from the database (linear search checks each row)
    $query = "SELECT * FROM dishes";
    $result = $conn->query($query);

    // Check if any products were found
    if ($result->num_rows > 0) {
        $found = false; // To track if any match is found
        echo "<h3>Search Results for '$searchTerm':</h3>";
        // Loop through all the products (Linear Search)
        while ($row = $result->fetch_assoc()) {
            // Check if the product name or description contains the search term
            if (stripos($row['title'], $searchTerm) !== false || stripos($row['slogan'], $searchTerm) !== false) {
                // Product matches the search term, display it
                echo "Title: " . $row['title'] . "<br>";
                echo "Description: " . $row['slogan'] . "<br>";
                echo "Price: " . $row['price'] . "<br><hr>";
                $found = true; // At least one product matched
            }
        }

        // If no products matched the search term
        if (!$found) {
            echo "No products found matching your search.";
        }
    } else {
        echo "No products available.";
    }
}

// Close connection
$conn->close();
?>

