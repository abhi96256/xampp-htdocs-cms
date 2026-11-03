<?php
$conn = new mysqli("localhost", "u615712904_a2p", "eFJYgph0]Fw", "u615712904_a2p");
$searchTerm = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';
?>



<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
</head>
<body>
    <form method="get" action="searchdemo.php">
        <input type="text" name="q" value="<?= htmlspecialchars($searchTerm) ?>" placeholder="Search...">
        <input type="submit" value="Search">
    </form>

    <?php
    if ($searchTerm != '') {
        $sql = "SELECT title, slug FROM pages WHERE content LIKE '%$searchTerm%'";
        $result = $conn->query($sql);

        echo "<h2>Search Results for \"$searchTerm\"</h2><ul>";

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<li><a href='" . $row['slug'] . "'>" . $row['title'] . "</a></li>";
            }
        } else {
            echo "<li>No results found.</li>";
        }

        echo "</ul>";
    }
    ?>
</body>
</html>
