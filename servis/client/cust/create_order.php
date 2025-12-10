<?php
session_start();
require_once '../common_helper.php';
if (!isset($_SESSION['user_id'])) header("Location: ../login.php");
$uid = $_SESSION['user_id'];
$devices = api_call('GET', "/devices?user_id=$uid");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = ["user_id"=>$uid, "device_id"=>intval($_POST['device_id']), "service_type"=>$_POST['service_type']];
    $res = api_call('POST', '/orders', $data);
    if (isset($res['order_id'])) header("Location: orders.php");
    else $err = $res['error'] ?? 'Error';
}
?>
<!doctype html><html><body>
<h3>Create Order</h3>
<form method="post">
Device:
<select name="device_id"><?php foreach($devices as $d){ echo "<option value='{$d['device_id']}'>#{$d['device_id']} - {$d['device_type']} {$d['device_model']}</option>"; }?></select><br>
Service: <input name="service_type" value="Repair"><br>
<button>Create</button>
</form>
</body></html>
