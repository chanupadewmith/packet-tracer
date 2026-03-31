<?php
include "db_connect.php";

$sql = "SELECT * FROM places ORDER BY id DESC";
$result = $conn->query($sql);

$places = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $places[] = $row;
    }
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($places, JSON_UNESCAPED_UNICODE);

$conn->close();
?>