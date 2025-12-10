<?php
// orders.php
require_once 'config.php';

function handle_orders($method, $id=null, $sub=null) {
    global $mysqli;

    // =========================
    //  GET /orders
    // =========================
    if ($method === 'GET') {

        $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;
        $technician_id = isset($_GET['technician_id']) ? intval($_GET['technician_id']) : null;

        if ($technician_id) {
            $sql = "SELECT o.*, oa.technician_id
                    FROM orders o
                    JOIN order_assignment oa ON oa.order_id = o.order_id
                    WHERE oa.technician_id = $technician_id";
        } elseif ($user_id) {
            $sql = "SELECT o.*, oa.technician_id
                    FROM orders o
                    LEFT JOIN order_assignment oa ON oa.order_id = o.order_id
                    WHERE o.user_id = $user_id";
        } else {
            // GET ALL ORDERS + technician_id
            $sql = "SELECT o.*, oa.technician_id
                    FROM orders o
                    LEFT JOIN order_assignment oa ON oa.order_id = o.order_id";
        }

        $res = $mysqli->query($sql);
        $out = [];
        while ($r = $res->fetch_assoc()) $out[] = $r;
        respond($out);
    }

    // =========================
    //  POST /orders  (create order)
    // =========================
    elseif ($method === 'POST' && $id === null) {
        $data = get_json_input();

        $device_id = intval($data['device_id'] ?? 0);
        $user_id   = intval($data['user_id'] ?? 0);
        $stype     = $mysqli->real_escape_string($data['service_type'] ?? 'Repair');

        if (!$device_id || !$user_id) {
            respond(["error"=>"Missing fields"], 400);
        }

        $now = date('Y-m-d H:i:s');

        $sql = "INSERT INTO orders (device_id, user_id, service_type, order_date, status)
                VALUES ($device_id, $user_id, '$stype', '$now', 'pending')";

        if ($mysqli->query($sql)) {
            respond(["msg"=>"Order created", "order_id"=>$mysqli->insert_id], 201);
        } else {
            respond(["error"=>$mysqli->error], 500);
        }
    }

    // =========================
    //  POST /orders/{id}/assign
    // =========================
    elseif ($method === 'POST' && $id && $sub === 'assign') {
        $data = get_json_input();
        $tech = intval($data['technician_id'] ?? 0);

        if ($tech <= 0) respond(["error"=>"technician_id required"], 400);

        // prevent double assign
        $cek = $mysqli->query("SELECT * FROM order_assignment WHERE order_id=$id");
        if ($cek->num_rows > 0) {
            respond(["error"=>"order already assigned"], 400);
        }

        $now = date('Y-m-d H:i:s');

        $sql = "INSERT INTO order_assignment(order_id, technician_id, assigned_date)
                VALUES ($id, $tech, '$now')";

        if ($mysqli->query($sql)) {
            $mysqli->query("UPDATE orders SET status='assigned' WHERE order_id=$id");
            respond(["success"=>true]);
        } else {
            respond(["error"=>$mysqli->error], 500);
        }
    }

    // =========================
    //  PATCH /orders/{id}/status
    // =========================
    elseif ($method === 'PATCH' && $id && $sub === 'status') {
        $data = get_json_input();
        $status = $mysqli->real_escape_string($data['status'] ?? '');

        $allowed = ['pending', 'assigned', 'in_progress', 'completed'];
        if (!in_array($status, $allowed)) {
            respond(["error"=>"Invalid status"], 400);
        }

        $sql = "UPDATE orders SET status='$status' WHERE order_id = $id";
        if ($mysqli->query($sql)) {
            respond(["msg"=>"Status updated"]);
        } else {
            respond(["error"=>$mysqli->error], 500);
        }
    }

    else {
        respond(["error"=>"Not implemented"], 400);
    }
}
