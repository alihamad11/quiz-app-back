<?php
header("Content-Type: application/json; charset=UTF-8");
require_once 'C:/xampp/htdocs/quiz-backend/config/database.php';
require_once 'C:/xampp/htdocs/quiz-backend/models/User.php';

class AuthController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function register($data) {
        if(empty($data->name) || empty($data->email) || empty($data->password)) {
            http_response_code(400);
            return json_encode(["message" => "All fields are required"]);
        }

        $this->user->name = $data->name;
        $this->user->email = $data->email;
        $this->user->password = $data->password;
        $this->user->is_admin = isset($data->is_admin) ? $data->is_admin : false;

        if($this->user->emailExists()->rowCount() > 0) {
            http_response_code(400);
            return json_encode(["message" => "Email already exists"]);
        }

        if($this->user->create()) {
            http_response_code(201);
            return json_encode(["message" => "User registered successfully"]);
        } else {
            http_response_code(500);
            return json_encode(["message" => "Unable to register user"]);
        }
    }

    public function login($data) {
        if(empty($data->email) || empty($data->password)) {
            http_response_code(400);
            return json_encode(["message" => "Email and password are required"]);
        }

        $this->user->email = $data->email;
        $stmt = $this->user->emailExists();

        if($stmt->rowCount() == 0) {
            http_response_code(404);
            return json_encode(["message" => "User not found"]);
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->user->user_id = $row['user_id'];
        $this->user->name = $row['name'];
        $this->user->password = $row['password'];
        $this->user->is_admin = $row['is_admin'];

        if(password_verify($data->password, $this->user->password)) {
            $user_data = [
                "user_id" => $this->user->user_id,
                "name" => $this->user->name,
                "email" => $this->user->email,
                "is_admin" => $this->user->is_admin
            ];

            http_response_code(200);
            return json_encode([
                "message" => "Login successful",
                "user" => $user_data
            ]);
        } else {
            http_response_code(401);
            return json_encode(["message" => "Invalid credentials"]);
        }
    }

    public function getUser($user_id) {
        $this->user->user_id = $user_id;
        $stmt = $this->user->getById($user_id);

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            http_response_code(200);
            return json_encode([
                "user_id" => $row['user_id'],
                "name" => $row['name'],
                "email" => $row['email'],
                "is_admin" => $row['is_admin'],
                "created_at" => $row['created_at']
            ]);
        } else {
            http_response_code(404);
            return json_encode(["message" => "User not found"]);
        }
    }
}
?>