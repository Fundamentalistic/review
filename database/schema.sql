CREATE TABLE project (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP    # Если планируется выборка по дате создания, нет соответсвующего индекса
) Engine=InnoDB;

CREATE TABLE task (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    status VARCHAR(16) NOT NULL,                    # Аналогично нет индекса по статусу
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP   # Если планируется выборка по дате создания, нет соответсвующего индекса
) Engine=InnoDB;
