<?php
session_start();
require_once 'common_helper.php';

$err = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CAPTCHA
    $captcha = $_POST['g-recaptcha-response'] ?? '';
    if (!$captcha) {
        $err = "Please complete the CAPTCHA.";
    } else {
        $secret = "6LdlVSYsAAAAABk4z3qO-STuVzVNY_6xqFJ8J3ka";
        $verify = file_get_contents(
            "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$captcha"
        );
        $verifyResponse = json_decode($verify, true);

        if ($verifyResponse['success'] != true) {
            $err = "CAPTCHA verification failed.";
        }
    }

    // Kalau CAPTCHA aman → proses login
    if (empty($err)) {
        $name = $_POST['user_name'] ?? '';
        $pwd  = $_POST['user_password'] ?? '';

        $res = api_call('POST', '/login', [
            'user_name'=>$name,
            'user_password'=>$pwd
        ]);

        if (isset($res['user_id'])) {
            $_SESSION['user_id']  = $res['user_id'];
            $_SESSION['user_role'] = $res['user_role'];
            $_SESSION['user_name'] = $res['user_name'];

            header("Location: dashboard.php");
            exit;
        } else {
            $err = "Login failed.";
        }
    }
}
?>
<!doctype html>
<html>
<head>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>

<h2>Login</h2>
<?php if ($err) echo "<p style='color:red;'>$err</p>"; ?>

<form method="post">
    Username: <input name="user_name"><br>
    Password: <input name="user_password" type="password"><br><br>

    <div class="g-recaptcha" data-sitekey="6LdlVSYsAAAAAGUUdmptNMFqOeYc8a7CkjUmG4T8"></div>
    <br>

    <button>Login</button>
</form>

</body>
</html>
