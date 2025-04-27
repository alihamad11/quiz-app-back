<?php
header("Content-Type: application/json; charset=UTF-8");
require_once 'C:/xampp/htdocs/quiz-backend/config/database.php';
require_once 'C:/xampp/htdocs/quiz-backend/models/Question.php';

class QuestionController {
    private $db;
    private $question;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->question = new Question($this->db);
    }

    public function create($data) {
        if(empty($data->quiz_id) || empty($data->question_text) || 
           empty($data->option1) || empty($data->option2) || 
           empty($data->correct_answer)) {
            http_response_code(400);
            return json_encode(["message" => "Required fields are missing"]);
        }

        $this->question->quiz_id = $data->quiz_id;
        $this->question->question_text = $data->question_text;
        $this->question->option1 = $data->option1;
        $this->question->option2 = $data->option2;
        $this->question->option3 = $data->option3 ?? '';
        $this->question->option4 = $data->option4 ?? '';
        $this->question->correct_answer = $data->correct_answer;

        if($question_id = $this->question->create()) {
            http_response_code(201);
            return json_encode([
                "message" => "Question created successfully",
                "question_id" => $question_id
            ]);
        } else {
            http_response_code(500);
            return json_encode(["message" => "Unable to create question"]);
        }
    }

    public function getByQuizId($quiz_id) {
        $stmt = $this->question->getByQuizId($quiz_id);
        $questions = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $questions[] = [
                "question_id" => $row['question_id'],
                "question_text" => $row['question_text'],
                "options" => [
                    $row['option1'],
                    $row['option2'],
                    $row['option3'],
                    $row['option4']
                ],
                "correct_answer" => $row['correct_answer']
            ];
        }

        http_response_code(200);
        return json_encode($questions);
    }

    public function getById($question_id) {
        $stmt = $this->question->getById($question_id);

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            http_response_code(200);
            return json_encode([
                "question_id" => $row['question_id'],
                "quiz_id" => $row['quiz_id'],
                "question_text" => $row['question_text'],
                "options" => [
                    $row['option1'],
                    $row['option2'],
                    $row['option3'],
                    $row['option4']
                ],
                "correct_answer" => $row['correct_answer']
            ]);
        } else {
            http_response_code(404);
            return json_encode(["message" => "Question not found"]);
        }
    }

    public function update($data) {
        if(empty($data->question_id) || empty($data->question_text) || 
           empty($data->option1) || empty($data->option2) || 
           empty($data->correct_answer)) {
            http_response_code(400);
            return json_encode(["message" => "Required fields are missing"]);
        }

        $this->question->question_id = $data->question_id;
        $this->question->question_text = $data->question_text;
        $this->question->option1 = $data->option1;
        $this->question->option2 = $data->option2;
        $this->question->option3 = $data->option3 ?? '';
        $this->question->option4 = $data->option4 ?? '';
        $this->question->correct_answer = $data->correct_answer;

        if($this->question->update()) {
            http_response_code(200);
            return json_encode(["message" => "Question updated successfully"]);
        } else {
            http_response_code(500);
            return json_encode(["message" => "Unable to update question"]);
        }
    }

    public function delete($question_id) {
        $this->question->question_id = $question_id;
        
        if($this->question->delete()) {
            http_response_code(200);
            return json_encode(["message" => "Question deleted successfully"]);
        } else {
            http_response_code(500);
            return json_encode(["message" => "Unable to delete question"]);
        }
    }
}
?>