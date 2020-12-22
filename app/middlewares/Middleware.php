<?php

	/**
	 *  middleware class
	 */
	class Middleware extends DB {

		public function				validateFirstname ( $firstname )
		{
			return ( !preg_match( "/^[a-z]{3,30}$/", strtolower( $firstname ) ) )
			? "The firstname must contains letters only ( between 3 and 30 ) !"
			: null;
		}

		public function				validateLastname ( $lastname )
		{
			return ( !preg_match( "/^[a-z]{3,30}$/", strtolower( $lastname ) ) )
			? "The lastname must contains letters only ( between 3 and 30 ) !"
			: null;
		}

		public function				validateUsername ( $username )
		{
			return ( !preg_match( "/^(?=.{3,20}$)(?![-_.])(?!.*[-_.]{2})[a-z0-9._-]+(?<![-_.])$/", strtolower( $username ) ) )
			? "The username should contain between 3 and 20 letters or numbers ( -, _ or . ) !"
			: null;
		}

		public function				validateEmail ( $email )
		{
			return ( !preg_match( "/[a-z0-9-_.]{1,50}@[a-z0-9-_.]{1,50}\.[a-z0-9]{2,10}$/", strtolower( $email ) ) )
			? "Invalid email address !"
			: null;
		}

		public function				validateGender ( $gender )
		{
			return ( strtolower($gender) == "male" || strtolower($gender) == "female" )
			? null
			: "The gender should be either male or female !";
		}

		public function				validateAddress ( $address )
		{
			return ( !preg_match("/^[a-z0-9\s,'-]*$/", strtolower($address)) )
			? "The address should be contains letters or numbers ( ',', ' or - ) !"
			: null;
		}

		public function				validatePassword ( $password )
		{
			return ( !preg_match( "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*_-]).{8,}$/", $password ) )
			? "The password should be minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character !"
			: null;
		}

		public function				validateOldPassword ( $oldpassword )
		{
			return ( $this->validatePassword( $oldpassword ) )
			? "The old password should be minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character !"
			: null;
		}

		public function				validateNewPassword ( $newpassword )
		{
			return ( $this->validatePassword( $newpassword ) )
			? "The new password should be minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character !"
			: null;
		}
		

		public function				isFullnameExists ( $firstname, $lastname )
		{
			$stt = $this->query("SELECT * FROM `users` WHERE firstname = ? AND lastname = ?", [ strtolower($firstname), strtolower($lastname) ]);
			return( $stt->fetch() );
		}

		public function				isUsernameExists ( $username )
		{
			$stt = $this->query("SELECT * FROM `users` WHERE username = ?", [ strtolower( $username ) ]);
			return( $stt->fetch() );
		}

		public function				isEmailExists ( $email )
		{
			$stt = $this->query("SELECT * FROM `users` WHERE email = ?", [ strtolower( $email ) ]);
			return( $stt->fetch() );
		}

		public function				isActiveAccount ( $username )
		{
			$stt = $this->query("SELECT * FROM `users` WHERE username = ?", [ strtolower( $username ) ]);
			$data = $stt->fetch();
			return ( empty($data['activationToken']) );
		}

		public function				isThePasswordIsValid( $id, $username, $password )
		{
			$stt = ( $id )
			? $this->query("SELECT * FROM `users` WHERE id = ?", [ $id ])
			: $this->query("SELECT * FROM `users` WHERE username = ?", [ strtolower( $username ) ]);
			$data = $stt->fetch();
			return ( password_verify($password, $data['password']) );
		}

		public function				isUsernameEditedExists ( $userid, $username )
		{
			$stt = $this->query("SELECT * FROM `users` WHERE username = ? AND id <> ?", [ strtolower( $username ), $userid ]);
			return( $stt->fetch() );
		}

		public function				isEmailEditedExists ( $userid, $email )
		{
			$stt = $this->query("SELECT * FROM `users` WHERE email = ? AND id <> ?", [ strtolower( $email ), $userid ]);
			return( $stt->fetch() );
		}

		public function				isRecoveryTokenValid ( $token )
		{
			$stt = $this->query("SELECT * FROM `users` WHERE recoveryToken = ?", [ $token ]);
			$data = $stt->fetch(PDO::FETCH_ASSOC);
			return ( $data ) ? array( 'id' => $data['id'] ) : null;
		}

		public function				isActivationTokenValid ( $token )
		{
			$stt = $this->query("SELECT * FROM `users` WHERE activationToken = ?", [ $token ]);
			$data = $stt->fetch(PDO::FETCH_ASSOC);
			return ( $data ) ? array( 'id' => $data['id'] ) : null;
		}

		public function				isImageOwnerExists ( $userid, $imgid )
		{
			$stt = $this->query("SELECT * FROM `gallery` WHERE id = ? AND userid = ?", [ $imgid, $userid ]);
			$data = $stt->fetch(PDO::FETCH_ASSOC);
			return ( $data ) ? $data : null;
		}

		public function				isImageExists ( $imgid )
		{
			$stt = $this->query("SELECT * FROM `gallery` WHERE id = ?", [ $imgid ]);
			$data = $stt->fetch(PDO::FETCH_ASSOC);
			return ( $data ) ? $data : null;
		}

		public function				validateImageDescription ( $descr )
		{
			return ( !preg_match( "/^[A-Za-z0-9 ]{10,200}$/", $descr ) )
			? "The description must contains letters and numbers only ( between 10 and 200 ) !"
			: null;
		}

		public function				isCommentExists ( $id )
		{
			$stt = $this->query("SELECT * FROM `comments` WHERE id = ?", [ $id ]);
			$data = $stt->fetch(PDO::FETCH_ASSOC);
			return ( $data ) ? $data : null;
		}

		public function				isUserTokenValid ( $token )
		{
			return ( hash_equals( $_SESSION["token"], $token ) ) ? true : false; 
		}

	}
?>