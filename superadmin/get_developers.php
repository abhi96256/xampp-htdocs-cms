<?php
include('./function/function.php');
$city = isset($_GET['city']) ? trim($_GET['city']) : '';
$developers = [];

if (!empty($city)) {
    // Strict filtering: Only show developers mapped to this city in location_developers table
    // We use a broader LIKE match to handle "Gurgaon" vs "Gurugram" etc if needed, 
    // but primarily focus on what's in the table.
    $results = sqlfetch("SELECT DISTINCT developer FROM location_developers WHERE location LIKE '%$city%' OR '$city' LIKE CONCAT('%', location, '%') ORDER BY developer ASC");
    foreach ($results as $r) {
        if(!empty($r['developer'])) $developers[] = $r['developer'];
    }
}

// No fallback to all developers anymore, as user wants strict city-based filtering
echo json_encode($developers);
?>
