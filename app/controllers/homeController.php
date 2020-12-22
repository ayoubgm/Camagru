<?php

	/**
	 * 	home controller class
	 */
	class homeController extends Controller {

		private $viewData;
		private $userMiddleware;
		private $userModel;
		private $galleryModel;
		private $notificationsModel;

		public function 				__construct()
		{
			$this->viewData = array();
			$this->userModel = $this->call_model('UsersModel');
			$this->userMiddleware = $this->call_middleware('UserMiddleware');
			$this->galleryModel = $this->call_model('GalleryModel');
			$this->notificationsModel = $this->call_model('NotificationsModel');
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
			try {
				session_start();
				$this->viewData["data"] = [ "gallery" => $this->galleryModel->getAllEditedImages() ];
				if ( $this->userMiddleware->isSignin( $_SESSION ) ) {
					$this->viewData["data"] += [
						"userData" => $this->userModel->findUserById( $_SESSION['userid'] ),
						"countUnreadNotifs" => $this->notificationsModel->getCountUnreadNotifications( $_SESSION['userid'] )
					];
				}
				$this->viewData["success"] = "true";
			} catch ( Exception $e ) {
				$this->viewData["success"] = "false";
				$this->viewData["msg"] = "Something goes wrong, try later !";
			}
			$this->call_view( 'home' . DIRECTORY_SEPARATOR .'index', $this->viewData )->render();
		}

		public function 				signin()
		{
			try {
				session_start();
				if ( $this->userMiddleware->isSignin( $_SESSION ) ) {
					header("Location: /");
				} else {
					switch ( $_SERVER["REQUEST_METHOD"] ) {
						case "GET":
							$this->viewData = [ "success" => "true" ];			
						break;
						case "POST":
							if ( isset( $_POST["btn-signin"] ) ) {
								unset( $_POST["btn-signin"] );
								if ( $error = $this->userMiddleware->signin( $_POST ) ) {
									$this->viewData = [ "success" => "false", "msg" => $error ];
								} else {
									$userData = $this->userModel->findUserByUsername( $_POST["username"] );
									$_SESSION = [
										"userid" => $userData["id"],
										"username" => $userData["username"],
										"token" => bin2hex( random_bytes( 32 ) )
									];
									header("Location: /home");
								}
							}
						break;
					}
				}
			} catch ( Exception $e ) {
				$this->viewData = [ "success" => "false", "msg" => "Couldn't login, try later !" ];
			}
			$this->call_view( 'home' . DIRECTORY_SEPARATOR .'signin', $this->viewData )->render();
		}
		
		public function 				signup()
		{
			try {
				session_start();
				if ( $this->userMiddleware->isSignin( $_SESSION ) ) {
					header("Location: /");
				} else {
					switch( $_SERVER["REQUEST_METHOD"] ) {
						case "GET":
							$this->viewData = [ "success" => "true" ];			
						break;
						case "POST":
							if ( isset($_POST["btn-signup"]) ) {
								unset( $_POST["btn-signup"] );
								if ( $error = $this->userMiddleware->signup($_POST) ) {
									$this->viewData = [ "success" => "false", "msg" => $error ];
								} else {
									$this->userModel->save( $_POST );
									// $this->sendMail("Confirmation mail", strtolower($_POST["email"]));
									$this->viewData = [ "success" => "true", "msg" => "Successful registration, you will receive an email for activation account !" ];
								}
							}
						break;
					}
				}
			} catch ( Exception $e ) {
				$this->viewData = [ "success" => "false", "msg" => "Something goes wrong, try later !" ];
			}
			$this->call_view('home' . DIRECTORY_SEPARATOR .'signup', $this->viewData)->render();
		}

		public function 				reset_password()
		{
			try {
				session_start();
				if ( $this->userMiddleware->isSignin( $_SESSION ) ) {
					header("Location: /");
				} else {
					switch ( $_SERVER["REQUEST_METHOD"] ) {
						case "GET":
							$this->viewData = [ "success" => "true" ];			
						break;
						case "POST":
							if ( isset( $_POST["btn-reset"] ) ) {
								if ( $error = $this->userMiddleware->reset_password($_POST["email"]) ) {
									$this->viewData = [ "success" => "false", "msg" => $error ];
								} else {
									$this->userModel->resetpassword($_POST["email"]);
									// $this->sendMail("Reset password", strtolower($_POST["email"]));
									$this->viewData = [ "success" => "true", "msg" => "A direct link for reset password has been sent successfully !" ];
								}
							}
						break;
					}
				}
			} catch ( Exception $e ) {
				$this->viewData = [ "success" => "false", "msg" => "Something goes wrong while reseting your password, try later !" ];
			}
			$this->call_view('home' . DIRECTORY_SEPARATOR .'reset_password', $this->viewData)->render();
		}

		private function 				validateToken( $data )
		{
			if ( ( !isset( $data[0] ) || !isset( $data[1] ) ) || ( $data[0] !== "token" || !$data[1] ) ) {
				return "No token found !";
			}
		}

		public function 				new_password( $data )
		{
			try {
				session_start();
				if ( $this->userMiddleware->isSignin( $_SESSION ) ) {
					header("Location: /");
				} else if ( $error = $this->validateToken( $data ) ) {
					$this->viewData = [ "success" => "false", "msg" => $error ];
				} else {
					switch ( $_SERVER["REQUEST_METHOD"] ) {
						case "GET":
							if ( $error = $this->userMiddleware->validateRecoveryToken( $data[1] ) ) {
								$this->viewData = [ "success" => "false", "msg" => $error, "data" => [ "token" => $data[1] ] ];
							} else {
								$this->viewData = [ "success" => "true", "data" => [ "token" => $data[1] ] ];
							}
						break;
						case "POST":
							if ( isset( $_POST["btn-submit"] ) ) {
								unset( $_POST["btn-submit"] ); $_POST["token"] = $data[1];
								if ( $error = $this->userMiddleware->new_password( $_POST ) ) {
									$this->viewData = [ "success" => "false", "msg" => $error, "data" => [ "token" => $data[1] ] ];
								} else {
									$this->userModel->newpassword( array( "newpassword" => password_hash($_POST["newpassword"], PASSWORD_ARGON2I), "token" => $data[1] ) );
									$this->viewData = [ "success" => "true", "msg" => "Your password has been changed successfully !", "data" => [ "token" => $data[1] ] ];
								}
							}
						break;
					}
				}
			} catch ( Exception $e ) {
				$this->viewData = ["success" => "false", "msg" => "Something goes wrong, try later !"];
			}
			$this->call_view( 'home' . DIRECTORY_SEPARATOR .'new_password', $this->viewData)->render();
		}
		
		public function 				account_confirmation ( $data )
		{
			try {
				switch ( $_SERVER["REQUEST_METHOD"] ) {
					case "GET":
						session_start();
						if ( $this->userMiddleware->isSignin( $_SESSION ) ) {
							header("Location: /");
						} else if ( ( $error = $this->validateToken( $data ) ) != null ) {
							$this->viewData = [ "success" => "false", "msg" => $error ];
						} else {
							$this->viewData[ "data" ] = [ "token" => $data[1] ];
							if ( ( $error = $this->userMiddleware->validateActivationToken( $data[1] ) ) != null ) {
								$this->viewData += [ "success" => "false", "msg" => $error ];
							} else {
								$this->userModel->activateAccount( array( 'token' => $data[1] ) );
								$this->viewData += [ "success" => "true", "msg" => "Your account has been activated successfully !" ];
							}
						}
					break;
				}
			} catch ( Exception $e ) {
				$this->viewData = [ "success" => "false", "msg" => "Something goes wrong while activating your account, try later !"];
			}
			$this->call_view( 'home' . DIRECTORY_SEPARATOR .'account_confirmation', $this->viewData)->render();
		}

		public function 				notfound()
		{
			$this->call_view('home' . DIRECTORY_SEPARATOR .'notfound')->render();
		}

	}
?>