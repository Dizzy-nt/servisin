<?php
session_start();
require_once '../common_helper.php';
if (!isset($_SESSION['user_id'])) header("Location: ../login.php");
$uid = $_SESSION['user_id'];
$devices = api_call('GET', "/devices?user_id=$uid");
?>
<!doctype html><html><body>
<h3>My Devices</h3>
<a href="create_device.php">Add Device</a>
<table border=1>
<tr><th>ID</th><th>Type</th><th>Brand</th><th>Model</th><th>Issue</th></tr>
<?php foreach($devices as $d): ?>
<tr>
<td><?=$d['device_id']?></td>
<td><?=htmlspecialchars($d['device_type'])?></td>
<td><?=htmlspecialchars($d['device_brand'])?></td>
<td><?=htmlspecialchars($d['device_model'])?></td>
<td><?=htmlspecialchars($d['issue_desc'])?></td>
</tr>
<?php endforeach;?>
</table>
<a href="../dashboard.php">Back to Dashboard</a>
</body></html>
