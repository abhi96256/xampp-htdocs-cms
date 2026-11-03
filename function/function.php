<?php
// Production pe errors display band karo (performance + security)
error_reporting(0);
ini_set('display_errors', 0);

$siteTitle = 'Full_ecom';
session_start();
$_detected_host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? '';
if (strpos($_detected_host, 'a2prealtech.com') !== false) {
    define('SITE_URL', 'https://a2prealtech.com/');
} else {
    define('SITE_URL', 'https://pink-sheep-796549.hostingersite.com/');
}
define('SITE_TITLE', 'Ssts');

// SINGLETON PATTERN: Ek hi DB connection reuse karo
function getPDOObject()
{
    static $pdo = null; // Pehli baar ke baad same connection reuse hoga
    if ($pdo !== null) {
        return $pdo;
    }
    $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? '';
    if (strpos($host, 'a2prealtech.com') !== false) {
        $dsn  = 'mysql:host=localhost;dbname=u615712904_a2p;charset=utf8mb4';
        $user = 'u615712904_a2p';
        $pass = 'VermaA2p@#9717';
    } else {
        $dsn  = 'mysql:host=localhost;dbname=u435351083_cms;charset=utf8mb4';
        $user = 'u435351083_jms';
        $pass = 'Maydivjms1@3';
    }
    try {
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $pdo;
    } catch (PDOException $e) {
        error_log('DB Connection Failed: ' . $e->getMessage());
        die('Database connection error. Please try again later.');
    }
}

function makeurlnamebynameCategory($str)
{
    $inputstring = trim(strip_tags($str));
    $lowertext = strtolower($inputstring);
    
    // Replace ampersand with 'and' first to avoid confusion with later hyphen replacements
    $lowertext = str_replace("&", "and", $lowertext);
    
    // Replace all non-alphanumeric characters (except hyphen AND DOT) with hyphen
    // Original code preserved dots, so we keep them.
    $lowertext = preg_replace('/[^a-z0-9\-\.]/', '-', $lowertext);
    
    // Clean up multiple hyphens
    $lowertext = preg_replace('/-+/', '-', $lowertext);
    
    // Trim hyphens from ends
    $lowertext = trim($lowertext, '-');
    
    return $lowertext;
}

/**
 * Robustly find a record by its slug when no explicit slug column is reliable or exists.
 * This handles special characters like '&' by trying multiple variations.
 */
function findRecordBySlug($table, $slug, $nameColumn = 'name') {
    // Determine if slug column exists conceptually for the query
    // 1. Try exact match on 'slug' column OR 'name' column (fast)
    $results = sqlfetch("SELECT * FROM `$table` WHERE ($nameColumn = '$slug' OR slug = '$slug') AND actstat=1");
    if (count($results)) return $results;

    // 2. Try match after replacing spaces with hyphens in SQL
    $results = sqlfetch("SELECT * FROM `$table` WHERE LOWER(REPLACE($nameColumn, ' ', '-')) = LOWER('$slug') AND actstat=1");
    if (count($results)) return $results;

    // 3. Try match with '&' handled (if slug has 'and')
    if (strpos($slug, 'and') !== false) {
        $alt_slug = str_replace('and', '&', $slug); // try replacing 'and' back to '&'
        $results = sqlfetch("SELECT * FROM `$table` WHERE (LOWER(REPLACE($nameColumn, ' ', '-')) = LOWER('$alt_slug') OR $nameColumn LIKE '%&%') AND actstat=1");
        if (count($results)) {
             foreach($results as $row) {
                 if (makeurlnamebynameCategory($row[$nameColumn]) === $slug) return [$row];
             }
        }
    }

    // 4. Final Fallback: Fetch all active and match using PHP's slugify function
    // This is the most reliable but slowest. For these tables (blogs/products), count is usually < 1000.
    $all = sqlfetch("SELECT * FROM `$table` WHERE actstat=1");
    foreach ($all as $row) {
        if (makeurlnamebynameCategory($row[$nameColumn]) === $slug) {
            return [$row];
        }
    }

    return [];
}


function makeurlnormal($str)
{
	//$lowertext = preg_replace('/[^A-Za-z0-9\-]/', '', $lowertext);
	$inputstring = trim(strip_tags($str));
	$lowertext = strtolower($inputstring);
	$lowertext = str_replace("  ", " ", $lowertext);
	$lowertext = str_replace(" ", " ", $lowertext);
	$lowertext = str_replace("&", "and", $lowertext);
	$lowertext = str_replace("/", " ", $lowertext);
	$lowertext = str_replace("`", " ", $lowertext);
	$lowertext = str_replace("~", " ", $lowertext);
	$lowertext = str_replace("!", " ", $lowertext);
	$lowertext = str_replace("@", " ", $lowertext);
	$lowertext = str_replace("#", " ", $lowertext);
	$lowertext = str_replace("$", " ", $lowertext);
	$lowertext = str_replace("^", " ", $lowertext);
	$lowertext = str_replace("&", " ", $lowertext);
	$lowertext = str_replace("*", " ", $lowertext);
	$lowertext = str_replace("(", " ", $lowertext);
	$lowertext = str_replace(")", " ", $lowertext);
	$lowertext = str_replace("_", " ", $lowertext);
	$lowertext = str_replace("-", " ", $lowertext);
	$lowertext = str_replace("|", " ", $lowertext);
	$lowertext = str_replace("{", " ", $lowertext);
	$lowertext = str_replace("}", " ", $lowertext);
	$lowertext = str_replace("[", " ", $lowertext);
	$lowertext = str_replace("]", " ", $lowertext);
	$lowertext = str_replace(":", " ", $lowertext);
	$lowertext = str_replace(";", " ", $lowertext);
	$lowertext = str_replace("<", " ", $lowertext);
	$lowertext = str_replace(">", " ", $lowertext);
	$lowertext = str_replace(".", " ", $lowertext);
	$lowertext = str_replace("?", " ", $lowertext);
	$lowertext = str_replace("%", "percent", $lowertext);
	$lowertext = str_replace("--", " ", $lowertext);
	$lowertext = str_replace("---", " ", $lowertext);
	$lowertext = str_replace(" ", " ", $lowertext);
	$lowertext = str_replace("'", " ", $lowertext);
	$lowertext = str_replace(",", " ", $lowertext);
	$lowertext = str_replace(".", " ", $lowertext);
	return $lowertext;
}
function insert($table, $data)
{
	$pdo = getPDOObject();

	// $fld_str='';$val_str='';
	// if($table_name && is_array($data_array))
	// {
	$sql = "SHOW COLUMNS FROM `" . $table . "`";
	$columns_query = sqlfetch($sql);

	foreach ($columns_query as $coloumn_data)
		$column_name[] = $coloumn_data['Field'];
	// print_r($column_name);  

	if (!empty($data) && is_array($data)) {
		$columns = '';
		$values  = '';
		$i = 0;
		if (!array_key_exists('created', $data)) {
			$data['created'] = date("Y-m-d H:i:s");
		}
		if (!array_key_exists('modified', $data)) {
			$data['modified'] = date("Y-m-d H:i:s");
		}

		$actual_data = array();

		foreach ($data as $key => $val) {
			if (in_array($key, $column_name)) {
				// echo $key;
				$actual_data[$key] = $val;
			}
		}
		// print_r($actual_data);
		$columnString = implode(',', array_keys($actual_data));
		$valueString = ":" . implode(',:', array_keys($actual_data));
		$sql = "INSERT INTO " . $table . " (" . $columnString . ") VALUES (" . $valueString . ")";
		$query = $pdo->prepare($sql);
		foreach ($actual_data as $key => $val) {
			$val = htmlspecialchars(strip_tags($val));
			$query->bindValue(":" . $key, $val);
		}
		$insert = $query->execute();
		if ($insert) {
			$data['id'] = $pdo->lastInsertId();
			return $data;
		} else {
			return false;
		}
	} else {
		return false;
	}
}


function sqlfetch($query)
{
	$row = array();
	$pdo = getPDOObject();
    try {
        $sql = $pdo->query($query);
        if (!$sql) return [];
        $datas = $sql->fetchAll(PDO::FETCH_ASSOC);
        foreach ($datas as $data)
            $row[] = $data;
    } catch (PDOException $e) {
        // If error is "Unknown column 'slug'", it's okay, we'll return empty and use other fallbacks
        return [];
    }
	return $row;
}

function get_active_status_text($num)
{
	$status = '';
	if ($num == 0)
		$status = '<span class="label label-default">Deactive</span>';
	if ($num == 1)
		$status = '<span class="label label-success">Active</span>';
	return $status;
}


function get_category_name($id)
{

	$name = '';
	$sql = sqlfetch("SELECT * FROM category where name='$id'");
	if (count($sql))
		foreach ($sql as $category)
			$name = $category['id'];
	return $name;
}

function get_product_name($id)
{
	$name = '';
	$sql = sqlfetch("SELECT * FROM product where id='$id'");
	if (count($sql))
		foreach ($sql as $product)
			$name = $product['name'];
	return $name;
}

function get_subproduct_name($id)
{
	$name = '';
	$sql = sqlfetch("SELECT * FROM subproduct where id='$id'");
	if (count($sql))
		foreach ($sql as $product)
			$name = $product['name'];
	return $name;
}


function get_category_id($name)
{

	$id = 0;
	$sql = sqlfetch("SELECT * FROM category where name='$name' order by fld_order limit 1");
	if (count($sql))
		foreach ($sql as $category)
			$id = $category['id'];
	return $id;
}

function get_subcategory_id($name)
{

	$id = 0;
	$sql = sqlfetch("SELECT * FROM subcategory where name='$name' order by fld_order limit 1");
	if (count($sql))
		foreach ($sql as $category)
			$id = $category['id'];
	return $id;
}

function get_blog_id($name)
{

	$id = 0;
	$sql = sqlfetch("SELECT * FROM pages where name='$name' order by fld_order limit 1");
	if (count($sql))
		foreach ($sql as $category)
			$id = $category['id'];
	return $id;
}

function get_product_id($name)
{

	$id = 0;
	$sql = sqlfetch("SELECT * FROM product where name='$name' order by fld_order limit 1");
	if (count($sql))
		foreach ($sql as $product)
			$id = $product['id'];
	return $id;
}


function get_subproduct_id($name)
{

	$id = 0;
	$sql = sqlfetch("SELECT * FROM subproduct where name='$name' order by fld_order limit 1");
	if (count($sql))
		foreach ($sql as $product)
			$id = $product['id'];
	return $id;
}

function get_product_cat($name)
{

	$id = 0;
	$sql = sqlfetch("SELECT * FROM product where name='$name' order by fld_order limit 1");
	if (count($sql))
		foreach ($sql as $product)
			$id = $product['subcat'];
	return $id;
}

function get_subproduct_prod($name)
{

	$id = 0;
	$sql = sqlfetch("SELECT * FROM subproduct where name='$name' order by fld_order limit 1");
	if (count($sql))
		foreach ($sql as $product)
			$id = $product['subcat'];
	return $id;
}

function get_num_sub_prod($id)
{

	$count = 0;
	$sql = sqlfetch("SELECT * FROM `subproduct` where subcat='$id'");
	if (count($sql))
		$count = count($sql);
	return $count;
}

function get_page_id($name)
{
	$categoryname = '';
	$data = sqlfetch("SELECT * FROM pages where name='$name'");
	foreach ($data as $category) {
		$categoryname = $category['id'];
	}
	return $categoryname;
}


function get_first_prod_by_cat($id)
{
	$data = sqlfetch("SELECT * FROM product where subcat='$id' order by id limit 1");
	foreach ($data as $product)
		$pid = $product['id'];
	return $pid;
}

function custom_echo($x, $length)
{
    $x = (string)$x;
	if (strlen($x) <= $length) {
		echo $x;
	} else {
		$y = substr($x, 0, $length) . '...';
		echo $y;
	}
}
