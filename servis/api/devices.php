<?php
// devices.php
require_once 'config.php';

function handle_devices($method, $id=null) {
    global $mysqli;
    if ($method === 'GET') {
        $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;
        if (!$user_id) respond(["error"=>"user_id required"], 400);
        $sql = "SELECT * FROM device WHERE user_id = $user_id";
        $res = $mysqli->query($sql);
        $out = [];
        while($r = $res->fetch_assoc()) $out[] = $r;
        respond($out);
    } elseif ($method === 'POST') {
        $data = get_json_input();
        $uid = intval($data['user_id'] ?? 0);
        $type = $mysqli->real_escape_string($data['device_type'] ?? '');
        $brand = $mysqli->real_escape_string($data['device_brand'] ?? '');
        $model = $mysqli->real_escape_string($data['device_model'] ?? '');
        $issue = $mysqli->real_escape_string($data['issue_desc'] ?? '');

        if (!$uid || !$type) respond(["error"=>"Missing fields"], 400);
        $sql = "INSERT INTO device (user_id, device_type, device_brand, device_model, issue_desc)
                VALUES ($uid, '$type', '$brand', '$model', '$issue')";
        if ($mysqli->query($sql)) {
            respond(["msg"=>"Device added","device_id"=>$mysqli->insert_id], 201);
        } else respond(["error"=>$mysqli->error], 500);
    } else {
        respond(["error"=>"Method not allowed"], 405);
    }
}
