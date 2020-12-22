<?php

	/**
	 *	Users model class
	 */
	class		UsersModel extends DB
	{

		public function				findUserById ( $userid )
		{
			$stt = $this->query("SELECT * FROM `users` WHERE id = ?", [ $userid ]);
			return ( $data = $stt->fetch(PDO::FETCH_ASSOC) )
			? array(
				'id' => $data['id'],
				'firstname' => $data['firstname'],
				'lastname' => $data['lastname'],
				'username' => $data['username'],
				'email' => $data['email'],
				'gender' => $data['gender'],
				'address' => $data['address'],
				'notifEmail' => $data['notifEmail'],
				'createdat' => $data['createdat']
			)
			: null;
		}

		public function				findUserByUsername ( $username )
		{
			$stt = $this->query("SELECT * FROM `users` WHERE username = ?", [ $username ]);
			return ( $data = $stt->fetch(PDO::FETCH_ASSOC) )
			? array(
				'id' => $data['id'],
				'firstname' => $data['firstname'],
				'lastname' => $data['lastname'],
				'username' => $data['username'],
				'email' => $data['email'],
				'gender' => $data['gender'],
				'address' => $data['address'],
				'notifEmail' => $data['notifEmail'],
				'createdat' => $data['createdat']
			)
			: null;
		}

		public function				save ( $data )
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
			$this->query('
				INSERT INTO users (firstname, lastname, username, email, gender, `address`, `password`, activationToken)
				VALUES (?,?,?,?,?,?,?,?)
			', $newUser);
		}

		public function				edit ( $userID, $editedData )
		{
			$editedData['id'] = $userID;
			$query = '
				UPDATE `users`
				SET firstname = ?, lastname = ?, username = ?, email = ?, gender = ?, `address` = ?
				WHERE id = ?
			';
			$this->query( $query, array_map( 'strtolower', array_values($editedData) ) );
		}

		public function				change_password ( $userID, $newPassword )
		{
			$query = 'UPDATE users SET `password` = ? WHERE id = ?';
			$this->query( $query, [ $newPassword, $userID ] );
		}

		public function				change_preference_email_notifs ( $userid, $value )
		{
			$query = 'UPDATE users SET notifEmail = ? WHERE id = ?';
			$this->query( $query, [ $value, $userid ] );
		}

		public function				resetpassword ( $email )
		{
			$query = 'UPDATE users SET recoveryToken = ? WHERE email = ?';
			$this->query( $query, [ base64_encode( strtolower($email) . date("Y-m-d H:i:s") ), $email ] );
		}

		public function				newpassword ( $data )
		{
			$query = 'UPDATE users SET `password` = ?, recoveryToken = NULL WHERE recoveryToken = ?';
			$this->query( $query, [ $data['newpassword'], $data['token'] ] );
		}

		public function				activateAccount ( $data )
		{
			$query = 'UPDATE users SET activationToken = NULL WHERE activationToken = ?';
			$this->query( $query, [ $data['token'] ] );
		}

	}
?>