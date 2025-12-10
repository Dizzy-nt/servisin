<?php
// assignments.php
require_once 'config.php';

function handle_assignments($method) {
    global $mysqli;

    if ($method === 'GET') {

        // MODE 1: GET /assignments?technician_id=3
        if (isset($_GET['technician_id'])) {
            $tech = intval($_GET['technician_id']);
            if ($tech <= 0) respond(["error"=>"invalid technician_id"], 400);

            $sql = "SELECT oa.*, o.service_type, o.status, o.order_date,
                           d.device_type, d.device_brand, d.device_model
                    FROM order_assignment oa
                    JOIN orders o ON o.order_id = oa.order_id
                    JOIN device d ON d.device_id = o.device_id
                    WHERE oa.technician_id = $tech";

            $res = $mysqli->query($sql);
            $out = [];
            while ($r = $res->fetch_assoc()) $out[] = $r;

            respond($out);
        }

        // MODE 2: GET /assignments  → ambil semua
        $sql = "SELECT * FROM order_assignment";
        $res = $mysqli->query($sql);
        $out = [];
        while ($r = $res->fetch_assoc()) $out[] = $r;

        respond($out);
    }

    // POST /assignments (untuk assign order)
    if ($method === 'POST') {
        $data = get_json_input();
        $order_id = intval($data['order_id'] ?? 0);
        $tech_id  = intval($data['technician_id'] ?? 0);

        if ($order_id <= 0 || $tech_id <= 0) {
            respond(["error"=>"order_id and technician_id required"], 400);
        }

        // optional: Cek apakah sudah assigned sebelumnya
        $cek = $mysqli->query("SELECT * FROM order_assignment WHERE order_id=$order_id");
        if ($cek->num_rows > 0) {
            respond(["error"=>"order already assigned"], 400);
        }

        $sql = "INSERT INTO order_assignment(order_id, technician_id) VALUES ($order_id, $tech_id)";
        if ($mysqli->query($sql)) {
            respond(["success"=>true]);
        } else {
            respond(["error"=>$mysqli->error], 500);
        }
    }

    respond(["error"=>"Method not allowed"], 405);
}
