<?php
	class UserMiddleware extends Middleware{

		// Middleware for validating register data
		public function      signup ( $data ) {
			if ( !$data['firstname'] || !$data['lastname'] || !$data['username'] || !$data['email'] || !$data['password'] || !$data['confirmation_password'] ) { return "Invalid data provided !"; }
			else if ( $error = $this->validateFirstname( $data['firstname'] ) ) { return $error; }
			else if ( $error = $this->validateLastname( $data['lastname'] ) ) { return $error; }
			else if ( $error = $this->validateUsername( $data['username'] ) ) { return $error; }
			else if ( $error = $this->validateEmail( $data['email'] ) ) { return $error; }
			else if ( $error = $this->validateGender( $data['gender'] ) ) { return $error; }
			else if ( $error = $this->validateAddress( $data['address'] ) ) { return $error; }
			else if ( $error = $this->validatePassword( $data['password'] ) ) { return $error; }
			else if ( $data['password'] != $data['confirmation_password'] ) { return "Passwords doesn't match !"; }
			else if ( $this->isFullnameExists( strtolower( $data['firstname'] ), strtolower( $data['lastname'] ) ) ) { return "The fullname is already exists !"; }
			else if ( $this->isUsernameExists( strtolower( $data['username']) ) ) { return "The username is already exists !"; }
			else if ( $this->isEmailExists( strtolower( $data['email']) ) ) { return "The email is already exists !"; }
			else { return null; }
		}

		// Middleware for validating sign in data
        public function      signin ( $data ) {
			if ( !$data['username'] || !$data['password'] ) { return "Invalid data provided !"; }
			else if ( !$this->isUsernameExists( strtolower( $data['username']) ) ) { return "The username does't exists !"; }
			else if ( !$this->isActiveAccount( strtolower( $data['username' ]) ) ) { return "You must activate your account first !"; }
			else if ( !$this->isThePasswordIsValid( strtolower( $data['username' ]), $data['password'] ) ) { return "Incorrect password !"; }
			else { return null; }
		}
		
		public function      edit ( $userID, $data ) {
			if ( isset( $data['firstname']) && ( $error = $this->validateFirstname( $data['firstname'] ) ) ) { return $error; }
			else if ( isset( $data['lastname']) && ( $error = $this->validateLastname( $data['lastname'] ) ) ) { return $error; }
			else if ( isset( $data['usertname']) && ( $error = $this->validateUsername( $data['username'] ) ) ) { return $error; }
			else if ( isset( $data['email']) && ( $error = $this->validateEmail( $data['email'] ) ) ) { return $error; }
			else if ( isset( $data['gender']) && ( $error = $this->validateGender( $data['gender'] ) ) ) { return $error; }
			else if ( isset( $data['address']) && ( $error = $this->validateAddress( $data['address'] ) ) ) { return $error; }
			else { return null; }
		}

	}
?>