<?php
require_once 'C:/xampp/htdocs/quiz-backend/config/database.php';
class Quiz {
    private $conn;
    private $table = 'quizzes';

    public $quiz_id;
    public $title;
    public $description;
    public $created_by;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO ".$this->table."
                  SET title = :title,
                      description = :description,
                      created_by = :created_by";

        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->created_by = htmlspecialchars(strip_tags($this->created_by));

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":created_by", $this->created_by);

        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function getAll() {
        $query = "SELECT q.*, u.name as author_name 
                  FROM ".$this->table." q
                  LEFT JOIN users u ON q.created_by = u.user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getById($quiz_id) {
        $query = "SELECT q.*, u.name as author_name 
                  FROM ".$this->table." q
                  LEFT JOIN users u ON q.created_by = u.user_id
                  WHERE q.quiz_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $quiz_id);
        $stmt->execute();
        return $stmt;
    }

    public function update() {
        $query = "UPDATE ".$this->table."
                  SET title = :title,
                      description = :description
                  WHERE quiz_id = :quiz_id";

        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->quiz_id = htmlspecialchars(strip_tags($this->quiz_id));

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":quiz_id", $this->quiz_id);

        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM ".$this->table." WHERE quiz_id = :quiz_id";
        $stmt = $this->conn->prepare($query);
        $this->quiz_id = htmlspecialchars(strip_tags($this->quiz_id));
        $stmt->bindParam(":quiz_id", $this->quiz_id);
        return $stmt->execute();
    }
}
?>