<?php
	class UserModel extends DB {

		public function			findUserByUsername ( $username )
		{
			$stt = $this->connect()->prepare("SELECT * FROM `users` WHERE username = ?");
			$stt->execute([ $username ]);
			return( $stt->fetch(PDO::FETCH_ASSOC) );
		}

		public function			findUserById ( $userid )
		{
			$stt = $this->connect()->prepare("SELECT * FROM `users` WHERE id = ?");
			$stt->execute([ $userid ]);
			$data =  $stt->fetch(PDO::FETCH_ASSOC);
			return array(
				'id' => $data['id'],
				'firstname' => $data['firstname'],
				'lastname' => $data['lastname'],
				'username' => $data['username'],
				'email' => $data['email'],
				'gender' => $data['gender'],
				'address' => $data['address'],
				'notifEmail' => $data['notifEmail'],
				'createdat' => $data['createdat']
			);
		}

		public function			save ( $data )
		{
			$newUser = array(
				strtolower($data['firstname']),
				strtolower($data['lastname']),
				strtolower($data['username']),
				strtolower($data['email']),
				strtolower($data['gender']),
				strtolower($data['address']),
				password_hash($data['password'], PASSWORD_ARGON2I),
				base64_encode( strtolower($data['email']) . date("Y-m-d H:i:s") )
			);
			$query = 'INSERT INTO users (firstname, lastname, username, email, gender, `address`, `password`, activationToken) VALUES (?,?,?,?,?,?,?,?)';
			$stt = $this->connect()->prepare( $query );
			return $stt->execute( $newUser );
		}

		public function			edit ( $userID, $editedData )
		{
			$editedData['id'] = $userID;
			$query = 'UPDATE `users` SET firstname = ?, lastname = ?, username = ?, email = ?, gender = ?, `address` = ? WHERE id = ?';
			$stt = $this->connect()->prepare( $query );
			return $stt->execute( array_values($editedData) );
		}

		public function			change_password ( $userID, $newPassword )
		{
			$query = 'UPDATE users SET `password` = ? WHERE id = ?';
			$stt = $this->connect()->prepare( $query );
			return $stt->execute([ $newPassword, $userID ]);
		}

		public function			change_preference_email_notifs ( $userid, $value )
		{
			$query = 'UPDATE users SET notifEmail = ? WHERE id = ?';
			$stt = $this->connect()->prepare( $query );
			return $stt->execute([ $value, $userid ]);
		}

		public function			resetpassword ( $email )
		{
			$query = 'UPDATE users SET recoveryToken = ? WHERE email = ?';
			$stt = $this->connect()->prepare( $query );
			return $stt->execute([ base64_encode( strtolower($email) . date("Y-m-d H:i:s") ), $email ]);
		}

	}
?>