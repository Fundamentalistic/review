<?php

namespace App\Storage;

use App\Model;

class DataStorage
{
    /**
     * @var \PDO 
     */
    public $pdo;

    public function __construct()
    {
        $this->pdo = new \PDO('mysql:dbname=task_tracker;host=127.0.0.1', 'user');
        // Нет пароля. Нет проверки соединения. Код лучше обернуть в try - catch
    }

    /**
     * @param int $projectId
     * @throws Model\NotFoundException
     */
    public function getProjectById($projectId)
    {
        // Нет проверки входа. Возможна передача очень большого числа. 
        $stmt = $this->pdo->query('SELECT * FROM project WHERE id = ' . (int) $projectId);

        if ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            return new Model\Project($row);
        }

        throw new Model\NotFoundException();
    }

    /**
     * @param int $project_id
     * @param int $limit
     * @param int $offset
     */
    public function getTasksByProjectId(int $project_id, $limit, $offset)
    {
        $stmt = $this->pdo->query("SELECT * FROM task WHERE project_id = $project_id LIMIT ?, ?");
        $stmt->execute([$limit, $offset]);
        // Возможна SQL инъекция

        $tasks = [];
        foreach ($stmt->fetchAll() as $row) {
            $tasks[] = new Model\Task($row);
        }

        return $tasks;
    }

    /**
     * @param array $data
     * @param int $projectId
     * @return Model\Task
     */
    public function createTask(array $data, $projectId)
    {
        $data['project_id'] = $projectId;

        $fields = implode(',', array_keys($data));
        $values = implode(',', array_map(function ($v) {
            return is_string($v) ? '"' . $v . '"' : $v;
        }, $data));

        $this->pdo->query("INSERT INTO task ($fields) VALUES ($values)"); // Возможна SQL инъекция ( опосредованно, сложно, но возможна )
        $data['id'] = $this->pdo->query('SELECT MAX(id) FROM task')->fetchColumn(); // Тут требуется блокировка таблиц. Возможна ситуация,
                                                                                    // при которой получение максимального айди произойдет 
                                                                                    // после внесения других данных
        return new Model\Task($data);
    }
}
