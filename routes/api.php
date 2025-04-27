<?php
header("Content-Type: application/json; charset=UTF-8");
require_once 'C:/xampp/htdocs/quiz-backend/config/database.php';
require_once 'C:/xampp/htdocs/quiz-backend/controllers/AuthController.php';
require_once 'C:/xampp/htdocs/quiz-backend/controllers/QuizController.php';
require_once 'C:/xampp/htdocs/quiz-backend/controllers/QuestionController.php';

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Parse request URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', trim($uri, '/'));

// Remove base path if exists
if ($uri[0] == 'quiz-backend') {
    array_shift($uri);
}

// Get input data
$input = json_decode(file_get_contents('php://input'));

// Initialize controllers
$auth = new AuthController();
$quiz = new QuizController();
$question = new QuestionController();

// Route the request
try {
        // Handle empty route (root endpoint)
        if (!isset($uri[0]) || $uri[0] === '') {
            http_response_code(200);
            echo json_encode(["message" => "Quiz API is running"]);
            exit;
        }
    switch ($uri[0]) {
        // Authentication endpoints
        case 'register':
            if ($method == 'POST') {
                echo $auth->register($input);
            } else {
                http_response_code(405);
                echo json_encode(["message" => "Method not allowed"]);
            }
            break;
            
        case 'login':
            if ($method == 'POST') {
                echo $auth->login($input);
            } else {
                http_response_code(405);
                echo json_encode(["message" => "Method not allowed"]);
            }
            break;
            
        // Quiz endpoints
        case 'quizzes':
            if ($method == 'GET' && count($uri) == 1) {
                // GET /quizzes
                echo $quiz->getAll();
            } elseif ($method == 'GET' && count($uri) == 2) {
                // GET /quizzes/{id}
                echo $quiz->getById($uri[1]);
            } elseif ($method == 'POST' && count($uri) == 1) {
                // POST /quizzes
                echo $quiz->create($input);
            } elseif ($method == 'PUT' && count($uri) == 2) {
                // PUT /quizzes/{id}
                $input->quiz_id = $uri[1];
                echo $quiz->update($input);
            } elseif ($method == 'DELETE' && count($uri) == 2) {
                // DELETE /quizzes/{id}
                echo $quiz->delete($uri[1]);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Endpoint not found"]);
            }
            break;
            
        // Question endpoints
        case 'questions':
            if ($method == 'GET' && count($uri) == 3 && $uri[1] == 'quiz') {
                // GET /questions/quiz/{quizId}
                echo $question->getByQuizId($uri[2]);
            } elseif ($method == 'GET' && count($uri) == 2) {
                // GET /questions/{id}
                echo $question->getById($uri[1]);
            } elseif ($method == 'POST' && count($uri) == 1) {
                // POST /questions
                echo $question->create($input);
            } elseif ($method == 'PUT' && count($uri) == 2) {
                // PUT /questions/{id}
                $input->question_id = $uri[1];
                echo $question->update($input);
            } elseif ($method == 'DELETE' && count($uri) == 2) {
                // DELETE /questions/{id}
                echo $question->delete($uri[1]);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Endpoint not found"]);
            }
            break;
            
        default:
            http_response_code(404);
            echo json_encode(["message" => "Endpoint not found"]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["message" => "Server error", "error" => $e->getMessage()]);
}
?>