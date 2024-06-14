<?php

class User {

    public $username;
    public $password;
    public $auth = false;

    public function __construct() {

    }

    public function test () {
      $db = db_connect();
      $statement = $db->prepare("select * from users;");
      $statement->execute();
      $rows = $statement->fetch(PDO::FETCH_ASSOC);
      return $rows;
    }
    public function create_user($username, $password) {
        $db = db_connect();
        $statement = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $statement->bindParam(':username', $username);
        $statement->bindParam(':password', $hashed_password);
        return $statement->execute();
    }

    public function user_exists($username) {
        $db = db_connect();
        $statement = $db->prepare("SELECT * FROM users WHERE username = :username");
        $statement->bindParam(':username', $username);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC) ? true : false;
    }


    public function authenticate($username, $password) {

    $username = strtolower($username);
    $db = db_connect();
        $statement = $db->prepare("select * from users WHERE username = :username;");
        $statement->bindValue(':name', $username);
        $statement->execute();
        $rows = $statement->fetch(PDO::FETCH_ASSOC);

    if (password_verify($password, $rows['password'])) {
      $_SESSION['auth'] = 1;
      $_SESSION['username'] = ucwords($username);
      unset($_SESSION['failedAuth']);
      header('Location: /home');
      die;
    } else {
      if(isset($_SESSION['failedAuth'])) {
        $_SESSION['failedAuth'] ++; //increment
      } else {
        $_SESSION['failedAuth'] = 1;
      }
      header('Location: /login');
      die;
    }
    }
  public function logAttempt($username, $status) {
      $sql = "INSERT INTO login_attempts (username, status, attempt_time) VALUES (:username, :status, :attempt_time)";
      $stmt = $this->database->prepare($sql);
      $stmt->bindParam(':username', $username);
      $stmt->bindParam(':status', $status);
      $stmt->bindParam(':attempt_time', date('Y-m-d H:i:s'));
      $stmt->execute();
  }
  public function getRecentAttempts($username)
  {
      $this->db->query('SELECT * FROM log WHERE username = :username ORDER BY attempt_time DESC LIMIT 3');
      $this->db->bind(':username', $username);
      return $this->db->resultSet();
  }
}
