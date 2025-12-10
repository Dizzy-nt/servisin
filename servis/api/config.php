<?php
// config.php
header("Content-Type: application/json; charset=UTF-8");
// Allow CORS for testing (be careful in production)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH, DELETE");
header("Access-Control-Allow-Headers: Content-Type, X-Requested-With, Authorization");

// DB credentials - sesuaikan
$db_host = "localhost"; // atau localhost
$db_user = "root";
$db_pass = ""; // isi password MariaDB jika ada
$db_name = "servis";

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($mysqli->connect_errno) {
    http_response_code(500);
    echo json_encode(["error" => "DB connection failed: " . $mysqli->connect_error]);
    exit;
}

function get_json_input() {
    $raw = file_get_contents("php://input");
    $data = json_decode($raw, true);
    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        respond(["error" => "Invalid JSON input"], 400);
    }
    return $data;
}

function respond($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data);
    exit;
}