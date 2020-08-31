<?php
	class Middleware extends DB {

		protected function		validateFirstname ( $firstname )
		{
			return ( !preg_match( "/^[a-zA-Z]{3,30}$/", $firstname ) )
			? "The firstname must contains letters only ( between 3 and 30 ) !"
			: null;
		}

		protected function		validateLastname ( $lastname )
		{
			return ( !preg_match( "/^[a-zA-Z]{3,30}$/", $lastname ) )
			? "The lastname must contains letters only ( between 3 and 30 ) !"
			: null;
		}

		protected function		validateUsername ( $username )
		{
			return ( !preg_match( "/^(?=.{3,20}$)(?![-_.])(?!.*[-_.]{2})[a-zA-Z0-9._-]+(?<![-_.])$/", $username ) )
			? "The username should contain between 3 and 20 letters or numbers ( -, _ or . ) !"
			: null;
		}

		protected function		validateEmail ( $email )
		{
			return ( !preg_match( "/[a-zA-Z0-9-_.]{1,50}@[a-zA-Z0-9-_.]{1,50}\.[a-z0-9]{2,10}$/", $email ) )
			? "Invalid email address !"
			: null;
		}

		protected function		validateGender ( $gender )
		{
			return ( $gender == "male" || $gender == "female" )
			? null
			: "The gender should be either male or female !";
		}

		protected function		validateAddress ( $address )
		{
			return ( !preg_match("/^[a-zA-Z0-9\s,'-]*$/", $address) )
			? "The address should be contains letters or numbers ( ',', ' or - ) !"
			: null;
		}

		protected function		validatePassword ( $password )
		{
			return ( !preg_match( "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*_-]).{8,}$/", $password ) )
			? "The password should be minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character !"
			: null;
		}

		protected function		validateOldPassword ( $oldpassword )
		{
			return ( $this->validatePassword( $oldpassword ) )
			? "The old password should be minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character !"
			: null;
		}

		protected function		validateNewPassword ( $newpassword )
		{
			return ( $this->validatePassword( $newpassword ) )
			? "The new password should be minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character !"
			: null;
		}
		

		protected function		isFullnameExists ( $firstname, $lastname )
		{
			$stt = $this->connect()->prepare("SELECT * FROM `users` WHERE firstname = ? AND lastname = ?");
			$stt->execute([ $firstname, $lastname ]);
			return( $stt->fetch() );
		}

		protected function		isUsernameExists ( $username )
		{
			$stt = $this->connect()->prepare("SELECT * FROM `users` WHERE username = ?");
			$stt->execute([ $username ]);
			return( $stt->fetch() );
		}

		protected function		isEmailExists ( $email )
		{
			$stt = $this->connect()->prepare("SELECT * FROM `users` WHERE email = ?");
			$stt->execute([ $email ]);
			return( $stt->fetch() );
		}

		protected function		isActiveAccount ( $username )
		{
			$stt = $this->connect()->prepare("SELECT * FROM `users` WHERE username = ?");
			$stt->execute([ $username ]);
			$userData = $stt->fetch();
			
			return ( empty($userData['activationToken']) );
		}

		protected function		isThePasswordIsValid( $id, $username, $password )
		{
			if (  $id != null ) {
				$stt = $this->connect()->prepare("SELECT * FROM `users` WHERE id = ?");
				$stt->execute([ $id ]);
			} else if ( $username != null ){
				$stt = $this->connect()->prepare("SELECT * FROM `users` WHERE username = ?");
				$stt->execute([ $username ]);
			}
			$userData = $stt->fetch();
			return ( password_verify($password, $userData['password']) );
		}

		protected function		isUsernameEditedExists ( $userid, $username )
		{
			$stt = $this->connect()->prepare("SELECT * FROM `users` WHERE username = ? AND id <> ?");
			$stt->execute([ $username, $userid ]);
			return( $stt->fetch() );
		}

		protected function		isEmailEditedExists ( $userid, $email )
		{
			$stt = $this->connect()->prepare("SELECT * FROM `users` WHERE email = ? AND id <> ?");
			$stt->execute([ $email, $userid ]);
			return( $stt->fetch() );
		}

		protected function		isRecoveryTokenValid ( $token )
		{
			$stt = $this->connect()->prepare("SELECT * FROM `users` WHERE recoveryToken = ?");
			$stt->execute([ $token ]);
			$data = $stt->fetch(PDO::FETCH_ASSOC);
			return ( $data ) ? array( 'id' => $data['id'] ) : null;
		}

		public function			isActivationTokenValid ( $token )
		{
			$stt = $this->connect()->prepare("SELECT * FROM `users` WHERE activationToken = ?");
			$stt->execute([ $token ]);
			$data = $stt->fetch(PDO::FETCH_ASSOC);
			return ( $data ) ? array( 'id' => $data['id'] ) : null;
		}

		protected function			isImageOwnerExists ( $userid, $imgid )
		{
			$stt = $this->connect()->prepare("SELECT * FROM `gallery` WHERE id = ? AND userid = ?");
			$stt->execute([ $imgid, $userid ]);
			$data = $stt->fetch(PDO::FETCH_ASSOC);
			return ( $data ) ? $data : null;
		}

		protected function			isImageExists ( $imgid )
		{
			$stt = $this->connect()->prepare("SELECT * FROM `gallery` WHERE id = ?");
			$stt->execute([ $imgid ]);
			$data = $stt->fetch(PDO::FETCH_ASSOC);
			return ( $data ) ? $data : null;
		}

	}
?>