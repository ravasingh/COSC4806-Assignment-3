<?php

class Login extends Controller {

public function index()
		{
				$this->view('login/index');
		}

		public function authenticate()
		{
				$username = $_POST['username'];
				$password = $_POST['password'];

				$user = $this->model('User')->user_exists($username);

				if ($user && password_verify($password, $user['password'])) {
						$this->logAttempt($username, 'good');
						header('Location: /home');
				} else {
						$this->logAttempt($username, 'bad');
						if ($this->isLockedOut($username)) {
								echo "Account locked. Please try again later.";
						} else {
								echo "Invalid credentials.";
						}
				}
		}

		private function logAttempt($username, $status)
		{
				$attempt_time = date('Y-m-d H:i:s');
				$this->model('Log')->logAttempt($username, $status, $attempt_time);
		}

		private function isLockedOut($username)
		{
				$attempts = $this->model('Log')->getRecentAttempts($username);
				if (count($attempts) >= 3) {
						$lastAttemptTime = strtotime(end($attempts)['attempt_time']);
						$lockoutTime = strtotime('+60 seconds', $lastAttemptTime);
						return time() < $lockoutTime;
				}
				return false;
		}
}

