<?php
session_start();
require_once '../common_helper.php';
if (!isset($_SESSION['user_id'])) header("Location: ../login.php");
$uid = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        "user_id"=>$uid,
        "device_type"=>$_POST['device_type'],
        "device_brand"=>$_POST['device_brand'],
        "device_model"=>$_POST['device_model'],
        "issue_desc"=>$_POST['issue_desc']
    ];
    $res = api_call('POST', '/devices', $data);
    if (isset($res['device_id'])) header("Location: devices.php");
    else $err = $res['error'] ?? 'Error';
}
?>
<!doctype html><html><body>
<h3>Add Device</h3>
<?php if (!empty($err)) echo "<p style='color:red;'>$err</p>"; ?>
<form method="post">
Type: <input name="device_type"><br>
Brand: <input name="device_brand"><br>
Model: <input name="device_model"><br>
Issue: <textarea name="issue_desc"></textarea><br>
<button>Add</button>
</form>
</body></html>
