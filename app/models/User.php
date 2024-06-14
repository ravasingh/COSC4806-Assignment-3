<?php
require_once('database.php');
class User {

    public $username;
    public $password;
    public $auth = false;

    public function __construct() {

    }

    public function get_all_users() {
      $db = db_connect();
      $statement = $db->prepare("SELECT * from users;");
      $statement->execute();
      $rows = $statement->fetch(PDO::FETCH_ASSOC);
      return $rows;
    }
    public function create_user($username, $password) {
        $db = db_connect();
        $statement = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':password', $hashed_password);
        return $statement->execute();
    }

    public function user_exists($username) {
        $db = db_connect();
        $statement = $db->prepare("SELECT * FROM users WHERE username = :username");
        $statement->bindValue(':username', $username);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC) ? true : false;
    }
    public function authenticate($username, $password) {
        /*
         * if username and password good then
         * $this->auth = true;
         */
    $username = strtolower($username);
    $db = db_connect();
        $statement = $db->prepare("SELECT * from users WHERE username = :name;");
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

}