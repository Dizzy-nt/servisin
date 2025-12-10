<?php
session_start();
require_once '../common_helper.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'technician') header("Location: ../login.php");
$tid = $_SESSION['user_id'];
$assignments = api_call('GET', "/assignments?technician_id=$tid");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];
    $res = api_call('PATCH', "/orders/$order_id/status", ['status'=>$status]);
    header("Location: assignments.php");
}
?>
<!doctype html><html><body>
<h3>My Assignments</h3>
<table border=1>
<th>Order</th><th>Device</th><th>Status</th><th>Action</th></tr>
<?php foreach($assignments as $a): ?>
<tr>
<td><?=$a['order_id']?></td>
<td><?=$a['device_type']?> <?=$a['device_brand']?> <?=$a['device_model']?></td>
<td><?=$a['status']?></td>
<td>
<form method="post">
<input type="hidden" name="order_id" value="<?=$a['order_id']?>">
<select name="status">
<option value="in_progress">In Progress</option>
<option value="completed">Completed</option>
</select>
<button>Update</button>
</form>
</td>
</tr>
<?php endforeach;?>
</table>
<a href="../dashboard.php">Back to Dashboard</a>
</body></html>
