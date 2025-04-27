<?php

require_once 'C:/xampp/htdocs/quiz-backend/config/database.php';

class User {
    private $conn;
    private $table = 'users';

    public $user_id;
    public $name;
    public $email;
    public $password;
    public $is_admin;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function emailExists() {
        $query = "SELECT user_id, name, password, is_admin 
                  FROM ".$this->table." 
                  WHERE email = ? 
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(1, $this->email);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO ".$this->table."
                  SET name = :name,
                      email = :email,
                      password = :password,
                      is_admin = :is_admin";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = password_hash(
            htmlspecialchars(strip_tags($this->password)), 
            PASSWORD_BCRYPT
        );
        $this->is_admin = isset($this->is_admin) ? $this->is_admin : false;

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":is_admin", $this->is_admin);

        return $stmt->execute();
    }

    public function getById($user_id) {
        $query = "SELECT user_id, name, email, is_admin, created_at 
                  FROM ".$this->table." 
                  WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        return $stmt;
    }
}
?>