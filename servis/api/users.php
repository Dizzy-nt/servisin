<?php
// users.php
require_once 'config.php';

function handle_login() {
    global $mysqli;
    $data = get_json_input();
    if (empty($data['user_name']) || empty($data['user_password'])) {
        respond(["error"=>"Missing credentials"], 400);
    }
    $user_name = $mysqli->real_escape_string($data['user_name']);
    $pwd = $mysqli->real_escape_string($data['user_password']);

    $q = "SELECT user_id, user_name, user_role FROM user WHERE user_name='$user_name' AND user_password='$pwd' LIMIT 1";
    $res = $mysqli->query($q);
    if ($res && $res->num_rows == 1) {
        $row = $res->fetch_assoc();
        respond(["user_id"=>$row['user_id'], "user_name"=>$row['user_name'], "user_role"=>$row['user_role']]);
    } else {
        respond(["error"=>"Invalid credentials"], 401);
    }
}

function handle_users($method, $id=null) {
    global $mysqli;
    if ($method === 'POST') {
        // create user (admin should call)
        $data = get_json_input();
        $name = $mysqli->real_escape_string($data['user_name'] ?? '');
        $role = $mysqli->real_escape_string($data['user_role'] ?? 'customer');
        $pwd  = $mysqli->real_escape_string($data['user_password'] ?? '');
        $phone = isset($data['user_phone']) ? intval($data['user_phone']) : null;

        if (!$name || !$pwd) respond(["error"=>"Missing fields"], 400);
        $sql = "INSERT INTO user (user_name, user_role, user_password, user_phone) VALUES ('$name','$role','$pwd',".($phone? $phone:"NULL").")";
        if ($mysqli->query($sql)) {
            respond(["msg"=>"User created", "user_id"=>$mysqli->insert_id], 201);
        } else {
            respond(["error"=>$mysqli->error], 500);
        }
    } elseif ($method === 'GET') {
        $res = $mysqli->query("SELECT user_id, user_name, user_role, user_phone FROM user");
        $out = [];
        while($r = $res->fetch_assoc()) $out[] = $r;
        respond($out);
    } else {
        respond(["error"=>"Method not supported"], 405);
    }
}
