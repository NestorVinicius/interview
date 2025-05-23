<?php
include __DIR__ . '/../../includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
        echo json_encode(['status' => 'error', 'message' => 'ID inválido.']);
        exit;
    }

    $id = (int)$_POST['id'];

    $check = $conn->prepare("SELECT id FROM tasks_db WHERE id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Tarefa não encontrada.']);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM tasks_db WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Tarefa excluída!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao excluir: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método não permitido.']);
}
