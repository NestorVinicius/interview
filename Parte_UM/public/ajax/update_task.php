<?php
include __DIR__ . '/../../includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
        echo json_encode(['status' => 'error', 'message' => 'ID inválido.']);
        exit;
    }

    if (empty($_POST['title'])) {
        echo json_encode(['status' => 'error', 'message' => 'O título é obrigatório.']);
        exit;
    }

    if (empty($_POST['data'])) {
        echo json_encode(['status' => 'error', 'message' => 'A data é obrigatória.']);
        exit;
    }

    $id = (int)$_POST['id'];
    $title = trim($_POST['title']);
    $prioridade = $_POST['prioridade'];
    $data = $_POST['data'];

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)) {
        echo json_encode(['status' => 'error', 'message' => 'Formato de data inválido (use YYYY-MM-DD).']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE tasks_db SET title = ?, prioridade = ?, data = ? WHERE id = ?");
    $stmt->bind_param("sssi", $title, $prioridade, $data, $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Tarefa atualizada!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método não permitido.']);
}
?>