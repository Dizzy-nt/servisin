<?php
echo "<pre>";
echo "Testing connection...\n";

$ch = curl_init("http://192.168.56.2/servis/api");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$error = curl_error($ch);
$info = curl_getinfo($ch);
curl_close($ch);

echo "CURL ERROR: $error\n";
echo "HTTP CODE: " . $info['http_code'] . "\n";
echo "RESPONSE:\n$response\n";
echo "</pre>";
