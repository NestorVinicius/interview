<?php
// Inclui o arquivo de conexão com o banco de dados
include __DIR__ . '/../../includes/db.php';

// Define o cabeçalho para resposta JSON
header('Content-Type: application/json');

// Verifica se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validação dos campos obrigatórios
    if (empty($_POST['title'])) {
        echo json_encode(['status' => 'error', 'message' => 'O título é obrigatório.']);
        exit;
    }

    if (empty($_POST['prioridade'])) {
        echo json_encode(['status' => 'error', 'message' => 'A prioridade é obrigatória.']);
        exit;
    }

    if (empty($_POST['data'])) {
        echo json_encode(['status' => 'error', 'message' => 'A data é obrigatória.']);
        exit;
    }

    // Sanitização dos dados
    $title = trim($_POST['title']);
    $prioridade = $_POST['prioridade'];
    $data = $_POST['data'];

    // Validação do formato da data (YYYY-MM-DD)
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)) {
        echo json_encode(['status' => 'error', 'message' => 'Formato de data inválido. Use YYYY-MM-DD.']);
        exit;
    }

    // Validação da prioridade
    $prioridadesPermitidas = ['alta', 'media', 'baixa'];
    if (!in_array($prioridade, $prioridadesPermitidas)) {
        echo json_encode(['status' => 'error', 'message' => 'Prioridade inválida.']);
        exit;
    }

    try {
        // Prepara a query SQL
        $stmt = $conn->prepare("INSERT INTO tasks_db (title, prioridade, data) VALUES (?, ?, ?)");
        
        if (!$stmt) {
            throw new Exception("Erro ao preparar a query: " . $conn->error);
        }

        // Vincula os parâmetros
        $stmt->bind_param("sss", $title, $prioridade, $data);

        // Executa a query
        if ($stmt->execute()) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Tarefa criada com sucesso!',
                'id' => $stmt->insert_id // Retorna o ID da tarefa criada
            ]);
        } else {
            throw new Exception("Erro ao executar a query: " . $stmt->error);
        }

    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
        exit;
    } finally {
        // Fecha a statement e a conexão
        if (isset($stmt)) $stmt->close();
        $conn->close();
    }

} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Método não permitido. Use POST.'
    ]);
}
?>