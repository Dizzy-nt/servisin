<?php
session_start();
require_once '../common_helper.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: ../login.php");
    exit;
}

$uid = $_SESSION['user_id'];

// ambil semua order milik customer ini
$orders = api_call('GET', "/orders?user_id=$uid");

// Cek bila API error
if (!is_array($orders)) {
    $orders = [];
}
?>
<!doctype html>
<html>
<body>

<h2>My Orders</h2>

<a href="create_order.php">+ Create New Order</a>
<br><br>

<table border="1" cellpadding="6">
    <tr>
        <th>Order ID</th>
        <th>Device ID</th>
        <th>Service Type</th>
        <th>Order Date</th>
        <th>Status</th>
    </tr>

    <?php if (count($orders) === 0): ?>
        <tr><td colspan="5">No orders found.</td></tr>
    <?php else: ?>
        <?php foreach ($orders as $o): ?>
            <tr>
                <td><?= htmlspecialchars($o['order_id']) ?></td>
                <td><?= htmlspecialchars($o['device_id']) ?></td>
                <td><?= htmlspecialchars($o['service_type']) ?></td>
                <td><?= htmlspecialchars($o['order_date']) ?></td>
                <td><?= htmlspecialchars($o['status']) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>

</table>

<br>
<a href="../dashboard.php">Back to Dashboard</a>

</body>
</html>
