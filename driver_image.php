<?php
// driver_image.php
require 'server/connection.php';

$card = $_GET['card_id'] ?? '';
if (!$card) {
    http_response_code(400);
    exit('Missing card_id');
}

// fetch just the blob
$sql = "SELECT driver_img FROM drivers WHERE card_id = :card LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute([':card' => $card]);
$img = $stmt->fetchColumn();

if (!$img) {
    http_response_code(404);
    exit('No image');
}

// (Assume JPEG; switch if you store PNG or other)
header('Content-Type: image/jpeg');
echo $img;
