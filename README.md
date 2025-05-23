-> sei que faltou usar metodos asicronos, ainda nao tenho tanto dominio de ajax
CREATE TABLE tasks_db (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    prioridade ENUM('alta', 'media', 'baixa') NOT NULL DEFAULT 'baixa',
    data DATE NOT NULL
);


