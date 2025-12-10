<?php
// index.php
require_once "config.php";

$method = $_SERVER['REQUEST_METHOD'];
$path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/');
$script_name = $_SERVER['SCRIPT_NAME'];
// Normalize path to get route after /api
$base = dirname($script_name);
$route = substr($path, strlen($base));
$route = strtok($route, '?'); // remove query

// Simple routing
// Examples: /login, /users, /devices, /orders, /orders/1/assign, /orders/1/status, /assignments
$parts = array_values(array_filter(explode('/', $route)));

if (count($parts) == 0) {
    respond(["msg"=>"API root"], 200);
}

$resource = $parts[0];
$id = isset($parts[1]) ? $parts[1] : null;
$sub = isset($parts[2]) ? $parts[2] : null;

switch ($resource) {
    case 'login':
        require_once 'users.php';
        handle_login();
        break;
    case 'users':
        require_once 'users.php';
        handle_users($method, $id);
        break;
    case 'devices':
        require_once 'devices.php';
        handle_devices($method, $id);
        break;
    case 'orders':
        require_once 'orders.php';
        // orders/{id}/assign or orders/{id}/status
        handle_orders($method, $id, $sub);
        break;
    case 'assignments':
        require_once 'assignments.php';
        handle_assignments($method);
        break;
    default:
        respond(["error"=>"Unknown resource: $resource"], 404);
}
