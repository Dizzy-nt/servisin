<?php
session_start();
require_once '../common_helper.php';

// hanya admin yang boleh akses
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// ambil semua user
$users = api_call('GET', "/users");

// kalau API gagal decode
if (!is_array($users)) {
    $users = [];
}
?>
<!doctype html>
<html>
<body>

<h2>Manage Users</h2>

<a href="create_user.php">+ Create New User</a>
<br><br>

<table border="1" cellpadding="6">
    <tr>
        <th>User ID</th>
        <th>Username</th>
        <th>Role</th>
        <th>Phone</th>
    </tr>

    <?php if (count($users) === 0): ?>
        <tr><td colspan="4">No users found.</td></tr>
    <?php else: ?>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u['user_id']) ?></td>
                <td><?= htmlspecialchars($u['user_name']) ?></td>
                <td><?= htmlspecialchars($u['user_role']) ?></td>
                <td><?= htmlspecialchars($u['user_phone']) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>

</table>

<br>
<a href="../dashboard.php">Back to Dashboard</a>

</body>
</html>
