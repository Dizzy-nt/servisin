<?php
session_start();
require_once '../common_helper.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$orders = api_call('GET', '/orders');
$users  = api_call('GET', '/users');

// handle assign
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);
    $tech     = intval($_POST['technician_id']);

    if ($tech <= 0) {
        $err = "Please choose a technician!";
    } else {
        $res = api_call('POST', "/orders/$order_id/assign", [
            "technician_id" => $tech
        ]);

        if (isset($res['success'])) {
            header("Location: manage_orders.php");
            exit;
        } else {
            $err = $res['error'] ?? "Assignment failed!";
        }
    }
}
?>
<!doctype html>
<html>
<body>

<h2>All Orders</h2>

<?php if (!empty($err)) echo "<p style='color:red;'>$err</p>"; ?>

<table border="1" cellpadding="6">
<tr>
    <th>ID</th>
    <th>Device</th>
    <th>Customer</th>
    <th>Status</th>
    <th>Assigned To</th>
    <th>Action</th>
</tr>

<?php foreach ($orders as $o): ?>
<tr>
    <td><?= $o['order_id'] ?></td>
    <td><?= $o['device_id'] ?></td>
    <td><?= $o['user_id'] ?></td>
    <td><?= $o['status'] ?></td>

    <td>
        <?php if (!empty($o['technician_id'])): ?>
            <?php
                $tname = "";
                foreach ($users as $u) {
                    if ($u['user_id'] == $o['technician_id']) {
                        $tname = $u['user_name'];
                        break;
                    }
                }
                echo "#{$o['technician_id']} $tname";
            ?>
        <?php else: ?>
            <span style="color:gray;">Not assigned</span>
        <?php endif; ?>
    </td>

    <td>
        <?php if (empty($o['technician_id'])): ?>
        <form method="post">
            <input type="hidden" name="order_id" value="<?= $o['order_id'] ?>">

            <select name="technician_id" required>
                <option value="">-- choose tech --</option>
                <?php foreach ($users as $u): ?>
                    <?php if ($u['user_role'] === 'technician'): ?>
                        <option value="<?= $u['user_id'] ?>">
                            #<?= $u['user_id'] ?> <?= $u['user_name'] ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>

            <button>Assign</button>
        </form>
        <?php else: ?>
            ✔ Already Assigned
        <?php endif; ?>
    </td>

</tr>
<?php endforeach; ?>
</table>

<br>
<a href="../dashboard.php">Back to Dashboard</a>

</body>
</html>
