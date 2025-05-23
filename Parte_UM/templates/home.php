<?php
$title = "home page";

$pageContent = "helloword";


ob_start();

?>

<style>
    /* Estilos CSS incorporados */
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f8f9fa;
    }

    .container {
        padding: 2rem;
    }

    .card {
        margin-bottom: 1.5rem;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .border-prioridade-alta { border-left: 4px solid #dc3545 !important; }
    .border-prioridade-media { border-left: 4px solid #ffc107 !important; }
    .border-prioridade-baixa { border-left: 4px solid #28a745 !important; }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .priority-filter {
        margin-bottom: 20px;
        max-width: 300px;
    }
</style>

<div class="container mt-5">
    <h2 class="mb-4">Minhas Tarefas</h2>
    
    <form method="GET" class="priority-filter">
        <select name="prioridade" class="form-select" onchange="this.form.submit()">
            <option value="">Todas as Prioridades</option>
            <option value="alta" <?= isset($_GET['prioridade']) && $_GET['prioridade'] === 'alta' ? 'selected' : '' ?>>Alta</option>
            <option value="media" <?= isset($_GET['prioridade']) && $_GET['prioridade'] === 'media' ? 'selected' : '' ?>>Média</option>
            <option value="baixa" <?= isset($_GET['prioridade']) && $_GET['prioridade'] === 'baixa' ? 'selected' : '' ?>>Baixa</option>
        </select>
    </form>

    <div class="row">
        <?php
        
        include __DIR__ . '/../includes/db.php';

        $prioridade = isset($_GET['prioridade']) ? $_GET['prioridade'] : '';
        $sql = "SELECT * FROM tasks_db";
        
        if (!empty($prioridade)) {
            $sql .= " WHERE prioridade = ?";
        }
        
        //  alta > media > baixa > data decrescente
        $sql .= " ORDER BY FIELD(prioridade, 'alta', 'media', 'baixa'), data DESC";
        
        $stmt = $conn->prepare($sql);
        
        if (!empty($prioridade)) {
            $stmt->bind_param("s", $prioridade);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $dataFormatada = date('d/m/Y', strtotime($row['data']));
                $corPrioridade = match ($row['prioridade']) {
                    'alta' => 'border-prioridade-alta',
                    'media' => 'border-prioridade-media',
                    'baixa' => 'border-prioridade-baixa',
                    default => ''
                };
        ?>
                <div class="col-md-4 mb-4">
                    <div class="card <?= $corPrioridade ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted">
                                Prioridade: <?= ucfirst($row['prioridade']) ?>
                            </h6>
                            <p class="card-text">
                                <small class="text-body-secondary">Data: <?= $dataFormatada ?></small>
                            </p>
                            <!--<div class="d-flex gap-2">
                                <button onclick="editTask(<?= $row['id'] ?>)" class="btn btn-sm btn-primary">
                                    Editar
                                </button>
                                <button onclick="deleteTask(<?= $row['id'] ?>)" class="btn btn-sm btn-danger">
                                    Excluir
                                </button>
                            </div>-->
                        </div>
                    </div>
                </div>
        <?php
            }
        } else {
            echo '<div class="col-12"><div class="alert alert-info">Nenhuma tarefa encontrada.</div></div>';
        }
        $stmt->close();
        $conn->close();
        ?>
    </div>
</div>
<script>
function deleteTask(id) {
    if (confirm('Tem certeza que deseja excluir esta tarefa?')) {
        $.ajax({
            url: 'ajax/delete_task.php',
            method: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function(data) {
                alert(data.message);
                loadTasks();
            },
            error: function(xhr, status, error) {
                alert('Erro: ' + error);
            }
        });
    }
}

function editTask(id) {
     $.ajax({
        url: 'ajax/get_task.php',
        method: 'GET',
        data: { id: id },
        dataType: 'json',
        success: function(task) {
            $('#editId').val(task.id);
            $('#editTitle').val(task.title);
            $('#editPrioridade').val(task.prioridade);
            $('#editData').val(task.data);
            $('#editModal').show();
        },
        error: function(xhr, status, error) {
            alert('Erro: ' + error);
        }
    }); 
}
</script>

<?php
$pageContent = ob_get_clean();
include 'base.php';