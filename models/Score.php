<?php
require_once 'C:/xampp/htdocs/quiz-backend/config/database.php';
class Score {
    private $conn;
    private $table = 'scores';

    public $score_id;
    public $user_id;
    public $quiz_id;
    public $score;
    public $total_questions;
    public $taken_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO ".$this->table."
                  SET user_id = :user_id,
                      quiz_id = :quiz_id,
                      score = :score,
                      total_questions = :total_questions";

        $stmt = $this->conn->prepare($query);

        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->quiz_id = htmlspecialchars(strip_tags($this->quiz_id));
        $this->score = htmlspecialchars(strip_tags($this->score));
        $this->total_questions = htmlspecialchars(strip_tags($this->total_questions));

        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":quiz_id", $this->quiz_id);
        $stmt->bindParam(":score", $this->score);
        $stmt->bindParam(":total_questions", $this->total_questions);

        return $stmt->execute();
    }

    public function getUserScores($user_id) {
        $query = "SELECT s.*, q.title as quiz_title 
                  FROM ".$this->table." s
                  JOIN quizzes q ON s.quiz_id = q.quiz_id
                  WHERE s.user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        return $stmt;
    }

    public function getQuizScores($quiz_id) {
        $query = "SELECT s.*, u.name as user_name 
                  FROM ".$this->table." s
                  JOIN users u ON s.user_id = u.user_id
                  WHERE s.quiz_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $quiz_id);
        $stmt->execute();
        return $stmt;
    }
}
?>