<?php
	class homeController extends Controller {

		// private function 	sendMail ( $subject, $to )
		// {
		// 	switch ( $subject ) {
		// 		case "Confirmation mail":
		// 			$headers = 'From: ' .$to . "\r\n".'Reply-To: ' . $to. "\r\n".'X-Mailer: PHP/' . phpversion();
		// 			if ( mail($to, $subject, "HELLO", $headers) )
		// 				echo "Send successfull";
		// 			else
		// 				echo "not send";
		// 		break;
		// 	}
		// }

		public function		index ()
		{
			$this->call_view('home' . DIRECTORY_SEPARATOR .'index')->render();
		}
		
		public function		signup()
		{
			switch( $_SERVER['REQUEST_METHOD'] ) {
				case 'GET':
					$this->call_view('home' . DIRECTORY_SEPARATOR .'signup')->render();
				break;
				case 'POST':
					if ( isset($_POST['btn-signup']) ) {
						unset( $_POST['btn-signup'] );
						if ( ( $error = $this->call_middleware('UserMiddleware')->signup($_POST) ) != null ) {
							$this->call_view( 'home' . DIRECTORY_SEPARATOR .'signup', [ 'success' => "false", 'msg' => $error ])->render();
						} else {
							try {
								if ( $this->call_model('UserModel')->save($_POST) ) {
									// $this->sendMail("Confirmation mail", strtolower($_POST['email']));
									$this->call_view(
										'home' . DIRECTORY_SEPARATOR .'signup',
										[ 'success' => "true", 'msg' => "Successful registration, you will receive an email for activation account !" ]
									)->render();
								} else {
									$this->call_view('home' . DIRECTORY_SEPARATOR .'signup', [ 'success' => "false", 'msg' => "Registration failed !" ] )->render();
								}
							} catch ( Exception $e ) {
								$this->call_view('home' . DIRECTORY_SEPARATOR .'signup', [ 'success' => "false", 'msg' => "Something goes wrong, try later !" ])->render();
							}
						}
					}
				break;
			}
		}
		
		public function		signin()
		{
			switch ( $_SERVER['REQUEST_METHOD'] ) {
				case "GET":
					$this->call_view('home' . DIRECTORY_SEPARATOR .'signin')->render();
				break;
				case "POST":
					if ( isset( $_POST['btn-signin'] ) ) {
						unset( $_POST['btn-signin'] );
						if ( ($error = $this->call_middleware('UserMiddleware')->signin($_POST)) != null){
							$this->call_view('home' . DIRECTORY_SEPARATOR .'signin', [ 'success' => "false", 'msg' => $error ] )->render();
						} else {
							$userData = $this->call_model('UserModel')->findUserByUsername($_POST['username']);
							session_start();
							$_SESSION['userid'] = $userData['id'];
							$_SESSION['username'] = $userData['username'];
							$this->call_view( 'home', [ 'success' => "true" ] )->render();
							header("Location: /camagru_git/home");
						}
					}
				break;
			}
		}

		public function		reset_password ( ) {
			session_start();
			if ( isset( $_SESSION['userid'] ) ) {
				header("Location: /camagru_git/home");
			} else {
				// request to reset password with user email
				switch ( $_SERVER['REQUEST_METHOD'] ) {
					case "GET":
						$this->call_view('home' . DIRECTORY_SEPARATOR .'reset_password')->render();
					break;
					case "POST":
						if ( isset( $_POST['btn-reset'] ) ) {
							unset( $_POST['btn-reset'] );
							if ( ( $error = $this->call_middleware('UserMiddleware')->reset_password($_POST['email']) ) != null ) {
								$this->call_view(
									'home' . DIRECTORY_SEPARATOR .'reset_password',
									[ 'success' => "false", 'msg' => $error ]
								)->render();
							} else {
								try {
									if ( $this->call_model('UserModel')->resetpassword($_POST['email']) ) {
										$this->call_view(
											'home' . DIRECTORY_SEPARATOR .'reset_password',
											[ 'success' => "true", 'msg' => "A direct link for reset password has been sent successfully !" ]
										)->render();
										// $this->sendMail("Reset password", strtolower($_POST['email']));
									} else {
										$this->call_view(
											'home' . DIRECTORY_SEPARATOR .'reset_password',
											[ 'success' => "false", 'msg' => "Failed to reset your password !" ]
										)->render();
									}
								} catch ( Exception $e ) {
									$this->call_view(
										'home' . DIRECTORY_SEPARATOR .'reset_password',
										[ 'success' => "false", 'msg' => "Something goes wrong while reseting your password !" ]
									)->render();
								}
							}
						}
					break;
				}
			}
		}
		
		public function		notfound()
		{
			echo "404";
		}

	}
?>