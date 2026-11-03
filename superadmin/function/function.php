<?php
// error_reporting(0);
$siteTitle = 'Ssts';
$_detected_host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? '';
if (strpos($_detected_host, 'a2prealtech.com') !== false) {
    define('SITE_URL', 'https://a2prealtech.com/');
} else {
    define('SITE_URL', 'https://pink-sheep-796549.hostingersite.com/');
}
if (session_status() == PHP_SESSION_NONE) { session_start(); }
function getPDOObject()
{
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
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => true
        ];
        $pdo = new PDO($dsn, $user, $pass, $options);
        // Force utf8mb4 to support emojis and symbols
        $pdo->exec("SET NAMES utf8mb4");
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
    $lowertext = preg_replace('/[^a-z0-9\-\.]/', '-', $lowertext);
    
    // Clean up multiple hyphens
    $lowertext = preg_replace('/-+/', '-', $lowertext);
    
    // Trim hyphens from ends
    $lowertext = trim($lowertext, '-');
    
    return $lowertext;
}

function sqlfetch($query)	
{
	$row = array();
	$pdo = getPDOObject();
	$sql = $pdo->query($query);
	$datas = $sql->fetchAll(PDO::FETCH_ASSOC);
	foreach ($datas as $data)
		$row[] = $data;
	return $row;
}

/*
     * Returns rows from the database based on the conditions
     * @param string name of the table
     * @param array select, where, order_by, limit and return_type conditions
     */
function getRows($table, $conditions = array())
{
	$pdo = getPDOObject();
	$sql = 'SELECT ';
	$sql .= array_key_exists("select", $conditions) ? $conditions['select'] : '*';
	$sql .= ' FROM ' . $table;
	if (array_key_exists("where", $conditions)) {
		$sql .= ' WHERE ';
		$i = 0;
		foreach ($conditions['where'] as $key => $value) {
			$pre = ($i > 0) ? ' AND ' : '';
			$sql .= $pre . $key . " = '" . $value . "'";
			$i++;
		}
	}

	if (array_key_exists("order_by", $conditions)) {
		$sql .= ' ORDER BY ' . $conditions['order_by'];
	}

	if (array_key_exists("start", $conditions) && array_key_exists("limit", $conditions)) {
		$sql .= ' LIMIT ' . $conditions['start'] . ',' . $conditions['limit'];
	} elseif (!array_key_exists("start", $conditions) && array_key_exists("limit", $conditions)) {
		$sql .= ' LIMIT ' . $conditions['limit'];
	}

	$query = $pdo->prepare($sql);
	$query->execute();

	if (array_key_exists("return_type", $conditions) && $conditions['return_type'] != 'all') {
		switch ($conditions['return_type']) {
			case 'count':
				$data = $query->rowCount();
				break;
			case 'single':
				$data = $query->fetch(PDO::FETCH_ASSOC);
				break;
			default:
				$data = '';
		}
	} else {
		if ($query->rowCount() > 0) {
			$data = $query->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	return !empty($data) ? $data : false;
}

/*
 * Insert data into the database
 * @param string name of the table
 * @param array the data for inserting into the table
 */
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
		if (!array_key_exists('created', $data)) {
			$data['created'] = date("Y-m-d H:i:s");
		}
		if (!array_key_exists('modified', $data)) {
			$data['modified'] = date("Y-m-d H:i:s");
		}

		$actual_data = array();
		foreach ($data as $key => $val) {
			if (in_array($key, $column_name)) {
				$actual_data[$key] = $val;
			}
		}

		$columnString = implode(',', array_keys($actual_data));
		$valueString = ":" . implode(',:', array_keys($actual_data));
		$sql = "INSERT INTO " . $table . " (" . $columnString . ") VALUES (" . $valueString . ")";
		$query = $pdo->prepare($sql);
		foreach ($actual_data as $key => $val) {
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

/*
 * Update data into the database
 * @param string name of the table
 * @param array the data for updating into the table
 * @param array where condition on updating data
 */
function update($table, $data, $conditions)
{
	$sql = "SHOW COLUMNS FROM `" . $table . "`";
	$columns_query = sqlfetch($sql);

	foreach ($columns_query as $coloumn_data)
		$column_name[] = $coloumn_data['Field'];

	$actual_data = array();
	foreach ($data as $key => $val) {
		if (in_array($key, $column_name)) {
			$actual_data[$key] = $val;
		}
	}
	$pdo = getPDOObject();

	if (!empty($actual_data) && is_array($actual_data)) {
		$colvalSet = '';
		$whereSql = '';
		$i = 0;
		foreach ($actual_data as $key => $val) {
			$pre = ($i > 0) ? ', ' : '';
			$colvalSet .= $pre . $key . "=:" . $key;
			$i++;
		}
		if (!empty($conditions) && is_array($conditions)) {
			$whereSql .= ' WHERE ';
			$j = 0;
			foreach ($conditions as $key => $value) {
				$preCondition = ($j > 0) ? ' AND ' : '';
				$whereSql .= $preCondition . $key . " = '" . $value . "'";
				$j++;
			}
		}
		$sql = "UPDATE " . $table . " SET " . $colvalSet . $whereSql;
		$query = $pdo->prepare($sql);
		foreach ($actual_data as $key => $val) {
			$query->bindValue(":" . $key, $val);
		}
		$update = $query->execute();
		return $update ? $query->rowCount() : false;
	} else {
		return false;
	}
}

/*
 * Delete data from the database
 * @param string name of the table
 * @param array where condition on deleting data
 */
function del($table, $conditions)
{
	$pdo = getPDOObject();
	$whereSql = '';
	if (!empty($conditions) && is_array($conditions)) {
		$whereSql .= ' WHERE ';
		$i = 0;
		foreach ($conditions as $key => $value) {
			$pre = ($i > 0) ? ' AND ' : '';
			$whereSql .= $pre . $key . " = '" . $value . "'";
			$i++;
		}
	}
	$sql = "DELETE FROM " . $table . $whereSql;
	$delete = $pdo->exec($sql);
	return $delete ? $delete : false;
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

function check_session()
{
	if (!isset($_SESSION['admin_id'])) {
		header('location:alogin.php');
		exit();
	}
}

function check_login()
{
	if (isset($_SESSION['admin_id'])) {
		header('location:./index.php');
	}
}

function login_me()
{
    $pdo = getPDOObject();
    $message = ''; // Initialize $message to avoid undefined variable errors

    if (empty($_POST['email']) || empty($_POST['pass'])) {
        $message = '<div class="alert alert-danger">Please enter Username and Password</div>';
    } else {
        $user = $_POST['email'];
        $pass = $_POST['pass'];

        // Query to fetch the admin details
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ? AND password = ? ORDER BY id LIMIT 1");
        $stmt->execute(array($user, $pass));

        $num = $stmt->rowCount();

        if ($num > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch only one row (PDO::FETCH_ASSOC)

            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_name'] = $row['name']; // Assuming you have a 'name' column in the `admin` table
            $message = '<div class="alert alert-success">Login Successful, Please Refresh the page if not redirected.</div>';
            header('location:./index.php');
            exit; // Exit after redirect to prevent further script execution
        } else {
            $message = '<div class="alert alert-danger">Invalid Credentials, Please check Your Username and Password</div>';
        }
    }

    return $message;
}

function get_total_student_count()
{
	$pdo = getPDOObject();
	$sql = $pdo->query("SELECT * from student");
	$num = $sql->rowCount();
	return $num;
}
function get_total_student_active_count()
{
	$pdo = getPDOObject();
	$sql = $pdo->query("SELECT * from student where actstat='1'");
	$num = $sql->rowCount();
	return $num;
}
function get_total_public_count()
{
	$pdo = getPDOObject();
	$sql = $pdo->query("SELECT * from public_register");
	$num = $sql->rowCount();
	return $num;
}
function get_total_public_active_count()
{
	$pdo = getPDOObject();
	$sql = $pdo->query("SELECT * from public_register where actstat='1'");
	$num = $sql->rowCount();
	return $num;
}

function get_new_member_count()
{
	$pdo = getPDOObject();
	$sql = $pdo->query("SELECT * from seller where actstat='0'");
	$num = $sql->rowCount();
	return $num;
}


function get_test_name($id)
{

	$name = '';

	$query = "SELECT * from test where id='$id'";
	$sql = sqlfetch($query);
	foreach ($sql as $data)
		$name = $data['name'];
	return $name;
}

function get_admin_id()
{
	$mid = $_SESSION['admin_id'];
	return $mid;
}

function get_category_name($id)
{

	$name = '';
	$sql = sqlfetch("SELECT * FROM category where id='$id'");
	if (count($sql))
		foreach ($sql as $category)
			$name = $category['name'];
	return $name;
}
function get_subcategory_name($id)
{

	$name = '';
	$sql = sqlfetch("SELECT * FROM subcategory where id='$id'");
	if (count($sql))
		foreach ($sql as $category)
			$name = $category['name'];
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



function get_category_count()
{
	$count = 0;
	$data = sqlfetch("SELECT id from category");
	$count = count($data);
	return $count;
}

function get_product_count()
{
	$count = 0;
	$data = sqlfetch("SELECT id from product");
	$count = count($data);
	return $count;
}

function get_admin_count()
{
	$count = 0;
	$data = sqlfetch("SELECT id from admin");
	$count = count($data);
	return $count;
}

function get_banner_count()
{
	$count = 0;
	$data = sqlfetch("SELECT id from client");
	$count = count($data);
	return $count;
}
// function get_category_nameby_subcatid($id)
// {
	
	
	// $sql=mysql_query("SELECT * FROM subcat where id='$id'");
	// $data=mysql_fetch_array($sql);
	// echo $catid=$data['cat_id'];
	
	// $name=$catid;
	// return $name;
// }
