<?php
$umessage = '';
include('./function/function.php'); // Include your database connection and utility functions
require('include/header.php');
?>

<h2>Create Admin</h2>
<form method="POST" action="">
    <label for="email">Admin Email:</label>
    <input type="email" name="email" id="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required><br><br>

    <label for="permissions">Assign Permissions:</label><br>
    <!-- Checkboxes for each permission -->
    <input type="checkbox" name="permissions[]" value="1" id="perm1">
    <label for="perm1">Manage Users</label><br>

    <input type="checkbox" name="permissions[]" value="2" id="perm2">
    <label for="perm2">View Reports</label><br>

    <input type="checkbox" name="permissions[]" value="3" id="perm3">
    <label for="perm3">Edit Content</label><br>

    <input type="checkbox" name="permissions[]" value="4" id="perm4">
    <label for="perm4">Manage Settings</label><br><br>

    <button type="submit" name="create_admin">Create Admin</button>
</form>

<?php
if (isset($_POST['create_admin'])) {
    // Retrieve and sanitize inputs
    $email = $_POST['email'];
    $password = $_POST['password']; // Plain text password
    $permissions = isset($_POST['permissions']) ? array_map('intval', $_POST['permissions']) : [];

    // Create PDO object
    $pdo = getPDOObject(); // Adjust to your function for getting PDO object

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM sub_users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $umessage = '<div class="alert alert-danger" role="alert">Admin with this email already exists!</div>';
    } else {
        // Insert admin into users table
        $stmt = $pdo->prepare("INSERT INTO sub_users (email, password, role) VALUES (?, ?, 'admin')");
        if ($stmt->execute([$email, $password])) {
            $admin_id = $pdo->lastInsertId(); // Get newly created admin's user ID

            // Assign permissions to the new admin
            if (!empty($permissions)) {
                $stmt = $pdo->prepare("INSERT INTO user_permissions (user_id, permission_id) VALUES (?, ?)");
                foreach ($permissions as $permission_id) {
                    $stmt->execute([$admin_id, $permission_id]);
                }
            }

            $umessage = '<div class="alert alert-success" role="alert">Admin created successfully!</div>';
        } else {
            $umessage = '<div class="alert alert-danger" role="alert">Error: ' . implode(', ', $pdo->errorInfo()) . '</div>';
        }
    }
}
?>

<?php
// Include footer for your web page
require('include/footer.php');
?>
