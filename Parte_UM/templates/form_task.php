<?php include __DIR__ . '/components/head.php'; ?>
<?php include __DIR__ . '/components/header.php'; ?>


<body class="d-flex flex-column min-vh-100">
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<?php $title = 'tarefas' //por algum motivo isso nao funcionou, realmente nao sei porque?>
<div class="flex-fill container">
    <form id="taskForm">
        <input type="text" id="title" placeholder="Título" required>
        <select id="prioridade" required>
            <option value="baixa">Baixa</option>
            <option value="media">Média</option>
            <option value="alta">Alta</option>
        </select>
        <input type="date" id="data" required>
        <button type="submit">Adicionar Tarefa</button>
    </form>

    <div id="tasksTable"></div>

    <div id="editModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border: 1px solid #ccc;">
        <h3>Editar Tarefa</h3>
        <form id="editForm">
            <input type="hidden" id="editId">
            <input type="text" id="editTitle" placeholder="Título" required><br>
            <select id="editPrioridade" required>
                <option value="baixa">Baixa</option>
                <option value="media">Média</option>
                <option value="alta">Alta</option>
            </select><br>
            <input type="date" id="editData" required><br>
            <button type="submit">Salvar</button>
            <button type="button" onclick="document.getElementById('editModal').style.display = 'none'">Cancelar</button>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    loadTasks();

    $('#taskForm').submit(function(e) {
        e.preventDefault();
        const formData = {
            title: $('#title').val(),
            prioridade: $('#prioridade').val(),
            data: $('#data').val()
        };

        $.ajax({
            url: 'ajax/create_task.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(data) {
                alert(data.message);
                if (data.status === 'success') {
                    loadTasks();
                    $('#taskForm')[0].reset();
                }
            },
            error: function(xhr, status, error) {
                alert('Erro: ' + error);
            }
        });
    });

    $('#editForm').submit(function(e) {
        e.preventDefault();
        const formData = {
            id: $('#editId').val(),
            title: $('#editTitle').val(),
            prioridade: $('#editPrioridade').val(),
            data: $('#editData').val()
        };

        $.ajax({
            url: 'ajax/update_task.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(data) {
                alert(data.message);
                if (data.status === 'success') {
                    loadTasks();
                    $('#editModal').hide();
                }
            },
            error: function(xhr, status, error) {
                alert('Erro: ' + error);
            }
        });
    });
});

function loadTasks() {
    $.ajax({
        url: 'ajax/read_tasks.php',
        method: 'GET',
        dataType: 'json',
        success: function(tasks) {
            let html = '<table border="1" style="width: 100%; margin-top: 20px;">';
            html += '<tr><th>Título</th><th>Prioridade</th><th>Data</th><th>Ações</th></tr>';
            $.each(tasks, function(index, task) {
                html += `
                    <tr>
                        <td>${task.title}</td>
                        <td>${task.prioridade}</td>
                        <td>${task.data}</td>
                        <td>
                            <button onclick="editTask(${task.id})">Editar</button>
                            <button onclick="deleteTask(${task.id})">Excluir</button>
                        </td>
                    </tr>
                `;
            });
            html += '</table>';
            $('#tasksTable').html(html);
        },
        error: function(xhr, status, error) {
            alert('Erro ao carregar tarefas: ' + error);
        }
    });
}

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


<?php include __DIR__ . '/components/footer.php'; ?>

</body>