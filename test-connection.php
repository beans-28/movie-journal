<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Testing Database Connection...</h2>";

$conn = new mysqli('localhost', 'root', '', 'movie_journal');

if ($conn->connect_error) {
    echo "<p style='color: red;'>❌ Connection failed: " . $conn->connect_error . "</p>";
} else {
    echo "<p style='color: green;'>✅ Connected successfully!</p>";
    
    $result = $conn->query("SHOW TABLES LIKE 'movies'");
    if ($result->num_rows > 0) {
        echo "<p style='color: green;'>✅ Movies table exists!</p>";
        
        $countResult = $conn->query("SELECT COUNT(*) as total FROM movies");
        $count = $countResult->fetch_assoc()['total'];
        echo "<p style='color: green;'>✅ Total movies in database: $count</p>";
    } else {
        echo "<p style='color: red;'>❌ Movies table does NOT exist!</p>";
    }
}

$conn->close();
?>