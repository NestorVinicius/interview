<?php
include __DIR__ . '/../../includes/db.php';

header('Content-Type: application/json');

$sql = "SELECT * FROM tasks_db ORDER BY data DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $tasks = [];
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
    echo json_encode($tasks);
} else {
    echo json_encode([]);
}
?>