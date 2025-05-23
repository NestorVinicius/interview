<?php
$host = 'localhost';
$user = 'root';
$pass = '150723DEV';
$db = 'task_db';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>