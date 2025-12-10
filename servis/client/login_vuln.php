<?php
// client/login.php
session_start();
require_once 'common_helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['user_name'] ?? '';
    $pwd  = $_POST['user_password'] ?? '';
    $res = api_call('POST', '/login', ['user_name'=>$name, 'user_password'=>$pwd]);
    if (isset($res['user_id'])) {
        // manual session: simpan user_id dan role
        $_SESSION['user_id'] = $res['user_id'];
        $_SESSION['user_role'] = $res['user_role'];
        $_SESSION['user_name'] = $res['user_name'];
        header("Location: dashboard.php");
        exit;
    } else {
        $err = $res['error'] ?? 'Login failed';
    }
}
?>
<!doctype html>
<html>
<body>
<h2>Login</h2>
<?php if (!empty($err)) echo "<p style='color:red;'>$err</p>"; ?>
<form method="post">
    Username: <input name="user_name"><br>
    Password: <input name="user_password" type="password"><br>
    <button>Login</button>
</form>
</body>
</html>
