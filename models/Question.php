<?php
require_once 'C:/xampp/htdocs/quiz-backend/config/database.php';
class Question {
    private $conn;
    private $table = 'questions';

    public $question_id;
    public $quiz_id;
    public $question_text;
    public $option1;
    public $option2;
    public $option3;
    public $option4;
    public $correct_answer;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO ".$this->table."
                  SET quiz_id = :quiz_id,
                      question_text = :question_text,
                      option1 = :option1,
                      option2 = :option2,
                      option3 = :option3,
                      option4 = :option4,
                      correct_answer = :correct_answer";

        $stmt = $this->conn->prepare($query);

        $this->quiz_id = htmlspecialchars(strip_tags($this->quiz_id));
        $this->question_text = htmlspecialchars(strip_tags($this->question_text));
        $this->option1 = htmlspecialchars(strip_tags($this->option1));
        $this->option2 = htmlspecialchars(strip_tags($this->option2));
        $this->option3 = htmlspecialchars(strip_tags($this->option3));
        $this->option4 = htmlspecialchars(strip_tags($this->option4));
        $this->correct_answer = htmlspecialchars(strip_tags($this->correct_answer));

        $stmt->bindParam(":quiz_id", $this->quiz_id);
        $stmt->bindParam(":question_text", $this->question_text);
        $stmt->bindParam(":option1", $this->option1);
        $stmt->bindParam(":option2", $this->option2);
        $stmt->bindParam(":option3", $this->option3);
        $stmt->bindParam(":option4", $this->option4);
        $stmt->bindParam(":correct_answer", $this->correct_answer);

        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function getByQuizId($quiz_id) {
        $query = "SELECT * FROM ".$this->table." WHERE quiz_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $quiz_id);
        $stmt->execute();
        return $stmt;
    }

    public function getById($question_id) {
        $query = "SELECT * FROM ".$this->table." WHERE question_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $question_id);
        $stmt->execute();
        return $stmt;
    }

    public function update() {
        $query = "UPDATE ".$this->table."
                  SET question_text = :question_text,
                      option1 = :option1,
                      option2 = :option2,
                      option3 = :option3,
                      option4 = :option4,
                      correct_answer = :correct_answer
                  WHERE question_id = :question_id";

        $stmt = $this->conn->prepare($query);

        $this->question_text = htmlspecialchars(strip_tags($this->question_text));
        $this->option1 = htmlspecialchars(strip_tags($this->option1));
        $this->option2 = htmlspecialchars(strip_tags($this->option2));
        $this->option3 = htmlspecialchars(strip_tags($this->option3));
        $this->option4 = htmlspecialchars(strip_tags($this->option4));
        $this->correct_answer = htmlspecialchars(strip_tags($this->correct_answer));
        $this->question_id = htmlspecialchars(strip_tags($this->question_id));

        $stmt->bindParam(":question_text", $this->question_text);
        $stmt->bindParam(":option1", $this->option1);
        $stmt->bindParam(":option2", $this->option2);
        $stmt->bindParam(":option3", $this->option3);
        $stmt->bindParam(":option4", $this->option4);
        $stmt->bindParam(":correct_answer", $this->correct_answer);
        $stmt->bindParam(":question_id", $this->question_id);

        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM ".$this->table." WHERE question_id = :question_id";
        $stmt = $this->conn->prepare($query);
        $this->question_id = htmlspecialchars(strip_tags($this->question_id));
        $stmt->bindParam(":question_id", $this->question_id);
        return $stmt->execute();
    }
}
?>