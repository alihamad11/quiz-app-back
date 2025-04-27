<?php
header("Content-Type: application/json; charset=UTF-8");
require_once 'C:/xampp/htdocs/quiz-backend/config/database.php';
require_once 'C:/xampp/htdocs/quiz-backend/models/Quiz.php';
require_once 'C:/xampp/htdocs/quiz-backend/models/Question.php';

class QuizController {
    private $db;
    private $quiz;
    private $question;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->quiz = new Quiz($this->db);
        $this->question = new Question($this->db);
    }

    public function create($data) {
        if(empty($data->title) || empty($data->created_by)) {
            http_response_code(400);
            return json_encode(["message" => "Title and creator ID are required"]);
        }

        $this->quiz->title = $data->title;
        $this->quiz->description = $data->description ?? '';
        $this->quiz->created_by = $data->created_by;

        if($quiz_id = $this->quiz->create()) {
            http_response_code(201);
            return json_encode([
                "message" => "Quiz created successfully",
                "quiz_id" => $quiz_id
            ]);
        } else {
            http_response_code(500);
            return json_encode(["message" => "Unable to create quiz"]);
        }
    }

    public function getAll() {
        $stmt = $this->quiz->getAll();
        $quizzes = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $quizzes[] = [
                "quiz_id" => $row['quiz_id'],
                "title" => $row['title'],
                "description" => $row['description'],
                "author_name" => $row['author_name'],
                "created_at" => $row['created_at']
            ];
        }

        http_response_code(200);
        return json_encode($quizzes);
    }

    public function getById($quiz_id) {
        $stmt = $this->quiz->getById($quiz_id);

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Get questions for this quiz
            $questions_stmt = $this->question->getByQuizId($quiz_id);
            $questions = [];
            
            while($question_row = $questions_stmt->fetch(PDO::FETCH_ASSOC)) {
                $questions[] = [
                    "question_id" => $question_row['question_id'],
                    "question_text" => $question_row['question_text'],
                    "options" => [
                        $question_row['option1'],
                        $question_row['option2'],
                        $question_row['option3'],
                        $question_row['option4']
                    ],
                    "correct_answer" => $question_row['correct_answer']
                ];
            }

            http_response_code(200);
            return json_encode([
                "quiz_id" => $row['quiz_id'],
                "title" => $row['title'],
                "description" => $row['description'],
                "author_name" => $row['author_name'],
                "created_at" => $row['created_at'],
                "questions" => $questions
            ]);
        } else {
            http_response_code(404);
            return json_encode(["message" => "Quiz not found"]);
        }
    }

    public function update($data) {
        if(empty($data->quiz_id) || empty($data->title)) {
            http_response_code(400);
            return json_encode(["message" => "Quiz ID and title are required"]);
        }

        $this->quiz->quiz_id = $data->quiz_id;
        $this->quiz->title = $data->title;
        $this->quiz->description = $data->description ?? '';

        if($this->quiz->update()) {
            http_response_code(200);
            return json_encode(["message" => "Quiz updated successfully"]);
        } else {
            http_response_code(500);
            return json_encode(["message" => "Unable to update quiz"]);
        }
    }

    public function delete($quiz_id) {
        $this->quiz->quiz_id = $quiz_id;
        
        if($this->quiz->delete()) {
            http_response_code(200);
            return json_encode(["message" => "Quiz deleted successfully"]);
        } else {
            http_response_code(500);
            return json_encode(["message" => "Unable to delete quiz"]);
        }
    }
}
?>