<?php
	class UserModel extends DB {

		public function 	save ( $data ) {
			$registerData = [
				':firstname' => strtolower($data['firstname']),
				':lastname' => strtolower($data['lastname']),
				':username' => strtolower($data['username']),
				':email' => strtolower($data['email']),
				':address' => strtolower($data['address']),
				':password' => password_hash($data['password'], PASSWORD_ARGON2I),
				':activationToken' => base64_encode( strtolower($data['email']) . date(now) )
			];

			$stt = $this->connect()->prepare("INSERT INTO `users` ()");
		}

	}
?>