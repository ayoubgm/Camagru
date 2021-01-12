<?php

	/**
	 *	Users model class
	 */
	class		UsersModel extends Model
	{

		public function				findUserById ( $userid )
		{
			if ( $stt = $this->query("SELECT * FROM `users` WHERE id = ?", [ $userid ]) ) {
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
		}

		public function				findUserByUsername ( $username )
		{
			if ( $stt = $this->query("SELECT * FROM `users` WHERE username = ?", [ $username ]) ) {
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
		}

		public function				save ( $data )
		{
			$aToken = base64_encode( strtolower($data['email']) . date("Y-m-d H:i:s") );
			$newUser = array(
				strtolower($data['firstname']),
				strtolower($data['lastname']),
				strtolower($data['username']),
				strtolower($data['email']),
				strtolower($data['gender']),
				strtolower($data['address']),
				password_hash($data['password'], PASSWORD_BCRYPT),
				$aToken
			);
			if ( $this->query( 'INSERT INTO users (firstname, lastname, username, email, gender, `address`, `password`, activationToken) VALUES (?,?,?,?,?,?,?,?) ', $newUser ) ) {
				return $aToken;
			}
		}

		public function				resetpassword ( $email )
		{
			return $this->query(
				'UPDATE users SET recoveryToken = ? WHERE email = ?',
				[ base64_encode( strtolower($email) . date("Y-m-d H:i:s") ), $email ]
			);
		}
		
		public function				newpassword ( $data )
		{
			return $this->query(
				'UPDATE users SET `password` = ?, recoveryToken = NULL WHERE recoveryToken = ?',
				[ $data['newpassword'], $data['token'] ]
			);
		}

		public function				edit ( $userID, $editedData )
		{
			$editedData['id'] = $userID;
			return $this->query(
				' UPDATE `users` SET firstname = ?, lastname = ?, username = ?, email = ?, gender = ?, `address` = ? WHERE id = ?',
				array_map( 'strtolower', array_values($editedData) )
			);
		}

		public function				change_preference_email_notifs ( $userid, $value )
		{
			return $this->query( 'UPDATE users SET notifEmail = ? WHERE id = ?', [ $value, $userid ] );
		}

		public function				activateAccount ( $data )
		{
			return $this->query( 'UPDATE users SET activationToken = NULL WHERE activationToken = ?', [ $data['token'] ] );
		}
		
	}
?>