<?php
	class UserMiddleware extends Middleware{
		
		private function 	setError ( $controller, $data ) {
			$view = $controller->call_view('home' . DIRECTORY_SEPARATOR .'signup', $data);
			$view->render();
		}

		// Middleware for validating register data
		public function      signup ( $controller, $data ) {
			if ( !$data['firstname'] || !$data['lastname'] || !$data['username'] || !$data['email'] || !$data['password'] || !$data['confirmation_password'] ) {
				$this->setError( $controller, ['success' => "false", 'msg' => "Invalid data provided !" ]);
			} else if ( $error = $this->validateFirstname( $data['firstname'] ) ) {
				$this->setError( $controller, [ 'success' => "false", 'msg' => $error ]);
			} else if ( $error = $this->validateLastname( $data['lastname'] ) ) {
				$this->setError( $controller, [ 'success' => "false", 'msg' => $error ]);
			} else if ( $error = $this->validateUsername( $data['username'] ) ) {
				$this->setError( $controller, [ 'success' => "false", 'msg' => $error ]);
			} else if ( $error = $this->validateEmail( $data['email'] ) ) {
				$this->setError( $controller, [ 'success' => "false", 'msg' => $error ]);
			} else if ( $error = $this->validateAddress( $data['address'] ) ) {
				$this->setError( $controller, [ 'success' => "false", 'msg' => $error ]);
			} else if ( $error = $this->validatePassword( $data['password'] ) ) {
				$this->setError( $controller, [ 'success' => "false", 'msg' => $error ]);
			} else if ( $data['password'] != $data['confirmation_password'] ) {
				$this->setError( $controller, [ 'success' => "false", 'msg' => "Passwords doesn't match !" ]);
			} else if ( $this->isFullnameExists( strtolower( $data['firstname'] ), strtolower( $data['lastname'] ) ) ) {
				$this->setError( $controller, [ 'success' => "false", 'msg' => "The fullname is already exists !" ]);
			} else if ( $this->isUsernameExists( strtolower( $data['username']) ) ) {
				$this->setError( $controller, [ 'success' => "false", 'msg' => "The username is already exists !" ]);
			} else if ( $this->isEmailExists( strtolower( $data['email']) ) ) {
				$this->setError( $controller, [ 'success' => "false", 'msg' => "The email is already exists !" ]);
			}
		}

		// Middleware for validating sign in data
        public function      signin ( $data ) {

        }

	}
?>