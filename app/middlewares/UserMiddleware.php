<?php

	/**
	 * 	user middlewares class
	 */
	class UserMiddleware extends Middleware
	{

		// Middleware for validating sign in data
		public function					signin ( $data )
		{
			if ( !$data['username'] || !$data['password'] ) {
				return "Invalid data provided !";
			} else if ( !$this->isUsernameExists( $data['username']) ) {
				return "The account does't exists !";
			} else if ( !$this->isActiveAccount( $data['username' ]) ) {
				return "You must activate your account first !";
			} else if ( !$this->isThePasswordIsValid( null, $data['username' ], $data['password'] ) ) {
				return "Incorrect password !";
			}
		}

		// Middleware for validating register data
		public function					signup ( $data )
		{
			if ( !$data['firstname'] || !$data['lastname'] || !$data['username'] || !$data['email'] || !$data['gender']  || !$data['password'] || !$data['confirmation_password'] ) {
				return "Invalid data provided !";
			} else if (
				( $error = $this->validateFirstname( $data['firstname'] ) ) ||
				( $error = $this->validateLastname( $data['lastname'] ) ) ||
				( $error = $this->validateUsername( $data['username'] ) ) ||
				( $error = $this->validateEmail( $data['email'] ) ) ||
				( $error = $this->validateGender( $data['gender'] ) ) ||
				( $error = $this->validateAddress( $data['address'] ) ) ||
				( $error = $this->validatePassword( $data['password'] ) )
			) {
				return $error;
			} else if ( $data['password'] != $data['confirmation_password'] ) {
				return "Passwords doesn't match !";
			} else if ( $this->isFullnameExists( strtolower( $data['firstname'] ), strtolower( $data['lastname'] ) ) ) {
				return "The fullname is already exists !";
			} else if ( $this->isUsernameExists( strtolower( $data['username']) ) ) {
				return "The username is already exists !";
			} else if ( $this->isEmailExists( strtolower( $data['email']) ) ) {
				return "The email is already exists !";
			}
		}

		public function					reset_password ( $email )
		{
			if ( !$email ) {
				return "the email can't be empty !";
			} else if ( $error = $this->validateEmail( $email ) ) {
				return $error;
			} else if ( !$this->isEmailExists( strtolower( $email ) ) ) {
				return "The email is not found !";
			}
		}

		public function					validateRecoveryToken ( $token ) {
			if ( !$token ) {
				return "No token found !";
			} else if ( !$this->isRecoveryTokenValid( $token ) ) {
				return "The recovery token is invalid or has already expired !";
			}
		}

		public function					validateActivationToken ( $token ) {
			if ( !$token ) {
				return "No token found !";
			} else if ( !$this->isActivationTokenValid( $token ) ) {
				return "The activation token is invalid or the account already activated !";
			}
		}

		public function					new_password ( $data )
		{
			if ( !$data['newpassword'] || !$data['confirmation_password'] || !$data['token'] ) {
				return "Invalid data provided !";
			} else if ( $error = $this->validatePassword( $data['newpassword'] ) ) {
				return $error;
			} else if ( $data['newpassword'] != $data['confirmation_password'] ) {
				return "Passwords doesn't match !";
			} else if ( !$this->isRecoveryTokenValid( $data['token'] ) ) {
				return "The recovery token is invalid or has already expired !";
			}
		}
		
		// Middleware for validating edited data
		public function					edit ( $userID, $data )
		{
			if ( !$data['firstname'] || !$data['lastname'] || !$data['username'] || !$data['email']  || !$data['gender'] ) {
				return "Invalid data provided !";
			} else if (
				( $error = $this->validateFirstname( $data['firstname'] ) ) ||
				( $error = $this->validateLastname( $data['lastname'] ) ) ||
				( $error = $this->validateUsername( $data['username'] ) ) ||
				( $error = $this->validateEmail( $data['email'] ) ) ||
				( $error = $this->validateGender( $data['gender'] ) ) ||
				( $error = $this->validateAddress( $data['address'] ) )
			) {
				return $error;
			} else if ( $this->isUsernameEditedExists( $userID, $data['username'] ) ) {
				return "The username is already exists !";
			} else if ( $this->isEmailEditedExists( $userID, $data['email'] ) ) {
				return "The email is already exists !";
			}
		}

		public function					change_password ( $id, $data )
		{
			if (
				( $error = $this->validateNewPassword( $data['oldpassword'] ) ) ||
				( $error = $this->validateOldPassword( $data['newpassword'] ) )
			) {
				return $error;
			} else if ( $data['newpassword'] != $data['confirmation_password'] ) {
				return "Passwords doesn't match !";
			} else if ( !$this->isThePasswordIsValid( $id, null, $data['oldpassword'] ) ) {
				return "The old password is Incorrect !";
			}
		}

		public function					validateDescription ( $description )
		{
			return $this->validateImageDescription( $description );
		}

		public function					isSignin ( $session )
		{
			return ( isset( $session['userid'] ) && isset( $session['username'] ) ) ? true : false; 
		}

	}
?>