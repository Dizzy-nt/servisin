<?php
session_start();
require_once '../common_helper.php';

// hanya admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        "user_name"     => $_POST['user_name'],
        "user_role"     => $_POST['user_role'],
        "user_password" => $_POST['user_password'],
        "user_phone"    => $_POST['user_phone']
    ];

    $res = api_call('POST', '/users', $data);

    if (isset($res['user_id'])) {
        header("Location: users.php");
        exit;
    } else {
        $err = $res['error'] ?? "Failed to create user";
    }
}
?>
<!doctype html>
<html>
<body>

<h2>Create New User</h2>

<?php if (!empty($err)) echo "<p style='color:red;'>$err</p>"; ?>

<form method="post">

    Username: <br>
    <input name="user_name" required><br><br>

    Password: <br>
    <input name="user_password" required><br><br>

    Role: <br>
    <select name="user_role">
        <option value="customer">Customer</option>
        <option value="technician">Technician</option>
        <option value="admin">Admin</option>
    </select>
    <br><br>

    Phone: <br>
    <input name="user_phone" type="number" required><br><br>

    <button>Create</button>

</form>

<br>
<a href="users.php">Back to Users</a>

</body>
</html>
