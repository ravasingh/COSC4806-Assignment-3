<?php

public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name,
                $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }
        return $this->conn;
    }

    // login attempt
    public function logAttempt($username, $status) {
        $sql = "INSERT INTO login_attempts (username, status, attempt_time) VALUES (:username, :status, :attempt_time)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':attempt_time', date('Y-m-d H:i:s'));
        $stmt->execute();
    }

    // Get login attempts within the last 60 seconds
public function getRecentAttempts($username)
{
    $this->db->query('SELECT * FROM log WHERE username = :username ORDER BY attempt_time DESC LIMIT 3');
    $this->db->bind(':username', $username);
    return $this->db->resultSet();
}
}


function db_connect() {
    try { 
        $dbh = new PDO('mysql:host=' . DB_HOST . ';port='. DB_PORT . ';dbname=' . DB_DATABASE, DB_USER, DB_PASS);
        return $dbh;
    } catch (PDOException $e) {
        //We should set a global variable here so we know the DB is down
    }
}