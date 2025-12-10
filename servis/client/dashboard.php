<?php
session_start();
if (!isset($_SESSION['user_id'])) header("Location: login.php");
$role = $_SESSION['user_role'];
?>
<!doctype html>
<html>
<body>
<h2>Welcome, <?=htmlspecialchars($_SESSION['user_name'])?></h2>
<p>Role: <?=$role?></p>
<ul>
    <?php if ($role === 'customer'): ?>
        <li><a href="cust/devices.php">My Devices</a></li>
        <li><a href="cust/orders.php">My Orders</a></li>
    <?php endif; ?>
    <?php if ($role === 'admin'): ?>
        <li><a href="admin/manage_orders.php">Manage Orders</a></li>
        <li><a href="admin/users.php">Manage Users</a></li>
    <?php endif; ?>
    <?php if ($role === 'technician'): ?>
        <li><a href="tech/assignments.php">My Assignments</a></li>
    <?php endif; ?>
</ul>
<a href="logout.php">Logout</a>
</body>
</html>
