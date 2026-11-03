<?php
// Ensure no errors are output before JSON
error_reporting(0);
ini_set('display_errors', 0);

require_once('./function/function.php');
$pdo = getPDOObject();

$action = isset($_GET['action']) ? $_GET['action'] : '';
$response = [];

try {
    if ($action == 'get_locations') {
        $stmt = $pdo->prepare("SELECT DISTINCT city FROM subproduct WHERE actstat=1 AND city != '' ORDER BY city ASC");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($data as $row) {
            $response[] = ['location' => $row['city']];
        }
    } 
    
    else if ($action == 'get_categories') {
        $city = $_GET['city'] ?? '';
        $stmt = $pdo->prepare("SELECT DISTINCT c.id, c.name FROM subproduct s JOIN category c ON s.subcat2 = c.id WHERE s.city = ? AND s.actstat=1 ORDER BY c.name ASC");
        $stmt->execute([$city]);
        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } 
    
    else if ($action == 'get_property_types') {
        $city = $_GET['city'] ?? '';
        $cat_id = $_GET['category_id'] ?? '';
        $stmt = $pdo->prepare("SELECT DISTINCT sc.id, sc.name as property_type FROM subproduct s JOIN subcategory sc ON s.subcat = sc.id WHERE s.city = ? AND s.subcat2 = ? AND s.actstat=1 ORDER BY sc.name ASC");
        $stmt->execute([$city, $cat_id]);
        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } 
    
    else if ($action == 'get_developers') {
        $city = $_GET['city'] ?? '';
        $cat_id = $_GET['category_id'] ?? '';
        $subcat_id = $_GET['subcategory_id'] ?? '';
        $stmt = $pdo->prepare("SELECT DISTINCT developer FROM subproduct WHERE city = ? AND subcat2 = ? AND subcat = ? AND actstat = 1 AND developer != '' ORDER BY developer ASC");
        $stmt->execute([$city, $cat_id, $subcat_id]);
        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

} catch (Throwable $e) {
    // Catch Fatal Errors in PHP 7+
} catch (Exception $e) {
    // Catch normal exceptions
}

// Clean any buffer and send JSON
if (ob_get_length()) ob_clean();
header('Content-Type: application/json');
echo json_encode($response);
exit;
?>
