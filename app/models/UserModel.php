<?php
	class UserModel extends DB {

		public function 	save ( $data ) {
			$query = 'INSERT INTO users (firstname, lastname, username, email, `address`, `password`, activationToken) VALUES (?,?,?,?,?,?,?)';
			$stt = $this->connect()->prepare( $query );
			return $stt->execute(array(
				strtolower($data['firstname']),
				strtolower($data['lastname']),
				strtolower($data['username']),
				strtolower($data['email']),
				strtolower($data['address']),
				password_hash($data['password'], PASSWORD_ARGON2I),
				base64_encode( strtolower($data['email']) . date("Y-m-d H:i:s") )
			));
		}

		public function 	findUserByUsername ( $username ) {
			$stt = $this->connect()->prepare("SELECT * FROM `users` WHERE username = ?");
			$stt->execute([ $username ]);
			return( $stt->fetch() );
		}

	}
?>