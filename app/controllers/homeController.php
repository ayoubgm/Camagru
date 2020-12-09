<?php

	/**
	 * 	home controller class
	 */
	class homeController extends Controller {

		private $userMiddleware;
		private $userModel;
		private $galleryModel;

		public function 				__construct()
		{
			$this->userMiddleware = self::call_middleware('UserMiddleware');
			$this->userModel = self::call_model('UsersModel');
			$this->galleryModel = self::call_model('GalleryModel');
		} 
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

		public function 				index()
		{
			$viewData = array();
			
			try {
				session_start();
				$viewData['data']['gallery'] = $this->galleryModel->getAllEditedImages();
				if ( isset( $_SESSION['userid'] ) ) {
					$viewData['data'] += [ 'userData' => $this->userModel->findUserById( $_SESSION['userid'] ), 'userGallery' => $this->galleryModel->userGallery( $_SESSION['userid'] ) ];
				}
				$viewData['success'] = "true";
			} catch ( Exception $e ) {
				$viewData = [ 'success' => "false", 'msg' => "Something goes wrong, try later !" ];
			}
			$this->call_view( 'home' . DIRECTORY_SEPARATOR .'index', $viewData )->render();
		}
		
		public function 				signup()
		{
			$viewData = array();
			
			switch( $_SERVER['REQUEST_METHOD'] ) {
				case 'POST':
					if ( isset($_POST['btn-signup']) ) {
						unset( $_POST['btn-signup'] );
						try {
							if ( ( $error = $this->userMiddleware->signup($_POST) ) != null ) {
								$viewData = [ 'success' => "false", 'msg' => $error ];
							} else {
								if ( $this->userModel->save( $_POST ) ) {
									// $this->sendMail("Confirmation mail", strtolower($_POST['email']));
									$viewData = [ 'success' => "true", 'msg' => "Successful registration, you will receive an email for activation account !" ];
								} else {
									$viewData = [ 'success' => "false", 'msg' => "Registration failed !" ];
								}
							}
						} catch ( Exception $e ) {
							$viewData = [ 'success' => "false", 'msg' => "Something goes wrong, try later !" ];
						}
					}
				break;
			}
			$this->call_view('home' . DIRECTORY_SEPARATOR .'signup', $viewData)->render();
		}
		
		public function 				signin()
		{
			$viewData = array();

			switch ( $_SERVER['REQUEST_METHOD'] ) {
				case "POST":
					if ( isset( $_POST['btn-signin'] ) ) {
						unset( $_POST['btn-signin'] );
						try {
							if ( ($error = $this->userMiddleware->signin( $_POST )) != null) {
								$viewData = [ 'success' => "false", 'msg' => $error ];
							} else {
								session_start();
								$userData = $this->userModel->findUserByUsername( $_POST['username'] );
								$_SESSION = [ 'userid' => $userData['id'], 'username' => $userData['username'] ];
								header("Location: /home");
							}
						} catch ( Exception $e ) {
							$viewData = [ 'success' => "false", 'msg' => "Something goes wrong, try later !" ];
						}
					}
				break;
			}
			$this->call_view( 'home' . DIRECTORY_SEPARATOR .'signin', $viewData )->render();
		}

		public function 				reset_password()
		{
			$viewData = array();
			
			try {
				session_start();
				if ( isset( $_SESSION['userid'] ) ) {
					header("Location: /home");
				} else {
					switch ( $_SERVER['REQUEST_METHOD'] ) {
						case "POST":
							if ( isset( $_POST['btn-reset'] ) ) {
								if ( ( $error = $this->userMiddleware->reset_password($_POST['email']) ) != null ) {
									$viewData = [ 'success' => "false", 'msg' => $error ];
								} else {	
									if ( $this->userModel->resetpassword($_POST['email']) ) {
										// $this->sendMail("Reset password", strtolower($_POST['email']));
										$viewData = [ 'success' => "true", 'msg' => "A direct link for reset password has been sent successfully !" ];
									} else {
										$viewData = [ 'success' => "false", 'msg' => "Failed to reset your password !" ];
									}
								}
							}
						break;
					}
				}
			} catch ( Exception $e ) {
				$viewData = [ 'success' => "false", 'msg' => "Something goes wrong while reseting your password !" ];
			}
			$this->call_view('home' . DIRECTORY_SEPARATOR .'reset_password', $viewData)->render();
		}

		private function 				validateToken( $data )
		{
			if ( ( !isset( $data[0] ) || !isset( $data[1] ) ) || ( $data[0] !== "token" || !$data[1] ) ) { return "No token found !"; }
			else { return null; }
		}

		public function 				new_password( $data )
		{
			$viewData = array();
			
			try {
				session_start();
				if ( isset( $_SESSION['userid'] ) ) {
					header("Location: /home");
				} else if ( ( $error = $this->validateToken( $data ) ) != null ) {
					$viewData = [ "success" => "false", "msg" => $error ];
				} else {
					switch ( $_SERVER['REQUEST_METHOD'] ) {
						case "GET":
							if ( ( $error = $this->userMiddleware->validateRecoveryToken( $data[1] ) ) != null ) {
								$viewData = [ "success" => "false", "msg" => $error, "data" => [ 'token' => $data[1] ] ];
							} else {
								$viewData = [ "success" => "true", "data" => [ 'token' => $data[1] ] ];
							}
						break;
						case "POST":
							if ( isset( $_POST['btn-submit'] ) ) {
								unset( $_POST['btn-submit'] );
								$_POST['token'] = $data[1];
								if ( ( $error = $this->userMiddleware->new_password( $_POST ) ) != null ) {
									$viewData = [ "success" => "false", "msg" => $error, "data" => [ 'token' => $data[1] ] ];
								} else if ( $this->userModel->newpassword( array( 'newpassword' =>  password_hash($_POST['newpassword'], PASSWORD_ARGON2I), 'token' => $data[1] ) ) ) {
									$viewData = [ "success" => "true", "msg" => "Your password has been changed successfully !", "data" => [ 'token' => $data[1] ] ];
								} else {
									$viewData = [ "success" => "false", "msg" => "Failed to change your password !", "data" => [ 'token' => $data[1] ] ];
								}
							}
						break;
					}
				}
			} catch ( Exception $e ) {
				$viewData = [ "success" => "false", "msg" => "Something goes wrong, try later !" ];
			}
			$this->call_view( 'home' . DIRECTORY_SEPARATOR .'new_password', $viewData)->render();
		}
		
		public function 				account_confirmation ( $data )
		{
			$viewData = array();

			try {
				session_start();
				if ( isset( $_SESSION['userid'] ) ) {
					header("Location: /home");
				} else if ( ( $error = $this->validateToken( $data ) ) != null ) {
					$viewData = [ "success" => "false", "msg" => $error ];
				} else {
					if ( ( $error = $this->userMiddleware->validateActivationToken( $data[1] ) ) != null ) {
						$viewData = [ "success" => "false", "msg" => $error ];
					} else {
						$viewData = [ "data" => $data[1] ];
						if ( $this->userModel->activateAccount( array( 'token' => $data[1] ) ) ) {
							$viewData = [ "success" => "true", "msg" => "Your account has been activated successfully !" ];
						} else {
							$viewData = [ "success" => "false", "msg" => "Failed to activate your account !" ];
						}	
					}
				}
			} catch ( Exception $e ) {
				$viewData = [ "success" => "false", "msg" => "Something goes wrong while activating your account !", "data" => $data[1] ];
			}
			$this->call_view( 'home' . DIRECTORY_SEPARATOR .'account_confirmation', $viewData)->render();
		}

		public function 				notfound()
		{
			$this->call_view( 'notfound')->render();
		}

	}
?>