<?php
	/**
	 * 	Home controller class
	 */
	class homeController extends Controller
	{

		public function 				__construct()
		{
			parent::__construct();
		}

		public function 				index()
		{
			session_start();
			try {
				$this->viewData["data"][ "gallery"] = $this->gallery_model->getAllEditedImages();
				if ( $this->helper->isRequestGET( $_SERVER["REQUEST_METHOD"] ) ) {
					if ( $this->user_middleware->isSignin( $_SESSION ) ) {
						$this->viewData["data"] += [
							"userData" => $this->user_model->findUserById( $_SESSION['userid'] ),
							"countUnreadNotifs" => $this->notifications_model->getCountUnreadNotifications( $_SESSION['userid'] )
						];
					}
					$this->viewData += [ "success" => "true" ];
				}
			} catch ( Exception $e ) {
				$this->viewData["success"] = "false";
				$this->viewData["msg"] = "Something goes wrong, try later !";
			}
			$this->call_view( 'home' . DIRECTORY_SEPARATOR .'index', $this->viewData )->render();
		}

		public function 				signin()
		{
			session_start();
			try {
				if ( $this->user_middleware->isSignin( $_SESSION ) ) {
					header("Location: /");
				} else if ( $this->helper->isRequestGET( $_SERVER["REQUEST_METHOD"] ) ) {
					$this->viewData["success"] = "true";			
				} else if ( $this->helper->isRequestPOST( $_SERVER["REQUEST_METHOD"] ) ) {
					if ( 
						(
							$this->helper->validate_inputs([
								"btn-signin" => [ "REQUIRED" => true, "EMPTY" => false ],
								"username" => [ "REQUIRED" => true, "EMPTY" => false ],
								"password" => [ "REQUIRED" => true, "EMPTY" => false ]
							], $_POST )
						) &&
						(
							$_POST = $this->helper->filter_inputs( "POST", array(
								'btn-signin' => FILTER_SANITIZE_STRING,
								'username' => FILTER_SANITIZE_STRING,
								'password' => FILTER_SANITIZE_STRING
							))
						)
					) {
						unset( $_POST["btn-signin"] );
						if ( $error = $this->user_middleware->signin( $_POST ) ) {
							$this->viewData = [ "success" => "false", "msg" => $error ];
						} else {
							$userData = $this->user_model->findUserByUsername( $_POST["username"] );
							$_SESSION = [
								"userid" => $userData["id"],
								"username" => $userData["username"],
								"token" => bin2hex( random_bytes( 32 ) )
							];
							header("Location: /home");
						}
					} else {
						$this->viewData = [ "success" => "false", "msg" => "Something is missing !" ];
					}
				}
			} catch ( Exception $e ) {
				$this->viewData = [ "success" => "false", "msg" => "Couldn't login, try later !" ];
			}
			$this->call_view( 'home' . DIRECTORY_SEPARATOR .'signin', $this->viewData )->render();
		}
		
		public function 				signup()
		{
			session_start();
			try {
				if ( $this->user_middleware->isSignin( $_SESSION ) ) {
					header("Location: /");
				} else if ( $this->helper->isRequestGET( $_SERVER["REQUEST_METHOD"] ) ) {
					$this->viewData = [ "success" => "true" ];
				} else if ( $this->helper->isRequestPOST( $_SERVER["REQUEST_METHOD"] ) ) {
					if (
						(
							$this->helper->validate_inputs([
								'btn-signup' => [ "REQUIRED" => true, "EMPTY" => false ],
								'firstname' => [ "REQUIRED" => true, "EMPTY" => false ],
								'lastname' => [ "REQUIRED" => true, "EMPTY" => false ],
								'username' => [ "REQUIRED" => true, "EMPTY" => false ],
								'email' => [ "REQUIRED" => true, "EMPTY" => false ],
								'gender' => [ "REQUIRED" => true, "EMPTY" => false ],
								'address' => [ "REQUIRED" => true, "EMPTY" => true ],
								'password' => [ "REQUIRED" => true, "EMPTY" => false ] ,
								'confirmation_password' => [ "REQUIRED" => true, "EMPTY" => false ]
							], $_POST )
						) &&
						(
							$_POST = $this->helper->filter_inputs( "POST", array(
								'btn-signup' => FILTER_SANITIZE_STRING,
								'firstname' => FILTER_SANITIZE_STRING,
								'lastname' => FILTER_SANITIZE_STRING,
								'username' => FILTER_SANITIZE_STRING,
								'email' => FILTER_SANITIZE_EMAIL,
								'gender' => FILTER_SANITIZE_STRING,
								'address' => FILTER_SANITIZE_STRING,
								'password' => FILTER_SANITIZE_STRING,
								'confirmation_password' => FILTER_SANITIZE_STRING
							))
						)
					) {
						unset( $_POST["btn-signup"] );
						if ( $error = $this->user_middleware->signup( $_POST ) ) {
							$this->viewData = [ "success" => "false", "msg" => $error ];
						} else if ( $atoken = $this->user_model->save( $_POST ) ) {
							$this->helper->sendMail("Confirmation mail", strtolower( $_POST["email"] ), $atoken);
							$this->viewData = [ "success" => "true", "msg" => "Successful registration, you will receive an email for activation account !" ];
						} else {
							$this->viewData = [ "success" => "false", "msg" => "Failed to create your account !" ];
						}
					} else {
						$this->viewData = ["success" => "false", "msg" => "Couldn't create your account, try later !"];
					}
				}
			} catch ( Exception $e ) {
				$this->viewData = [ "success" => "false", "msg" => "Something goes wrong while create your account, try later !" ];
			}
			$this->call_view('home' . DIRECTORY_SEPARATOR .'signup', $this->viewData)->render();
		}

		public function 				reset_password()
		{
			session_start();
			try {
				if ( $this->user_middleware->isSignin( $_SESSION ) ) {
					header("Location: /");
				} else if ( $this->helper->isRequestGET( $_SERVER["REQUEST_METHOD"] ) ) {
					$this->viewData = [ "success" => "true" ];					
				} else if ( $this->helper->isRequestPOST( $_SERVER["REQUEST_METHOD"] ) ) {
					if (
						(
							$this->helper->validate_inputs([
								'btn-reset' => [ "REQUIRED" => true, "EMPTY" => false ],
								'email' => [ "REQUIRED" => true, "EMPTY" => false ],
							], $_POST )
						) &&
						(
							$_POST = $this->helper->filter_inputs( "POST", array(
								'btn-reset' => FILTER_SANITIZE_STRING,
								'email' => FILTER_SANITIZE_EMAIL
							))
						)
					) {
						if ( $error = $this->user_middleware->reset_password( $_POST["email"] ) ) {
							$this->viewData = [ "success" => "false", "msg" => $error ];
						} else if ( $rToken = $this->user_model->resetpassword( $_POST["email"] ) ) {
							$this->helper->sendMail("Reset password", strtolower( $_POST["email"] ), $rToken);
							$this->viewData = [ "success" => "true", "msg" => "A direct link for reset password has been sent successfully !" ];
						} else {
							$this->viewData = [ "success" => "false", "msg" => "Failed to reset your password !" ];
						}
					} else {
						$this->viewData = ["success" => "false", "msg" => "Couldn't reset your password, try later !" ];
					}
				}
			} catch ( Exception $e ) {
				$this->viewData = ["success" => "false", "msg" => "Something goes wrong while reseting your password, try later !"];
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
			session_start();
			try {
				if ( $this->user_middleware->isSignin( $_SESSION ) ) {
					header("Location: /");
				} else if ( $error = $this->validateToken( $data ) ) {
					$this->viewData = ["success" => "false","msg" => $error ];
				} else {
					$this->viewData["data"][ "token"] = $data[1];
					if ( $this->helper->isRequestGET( $_SERVER["REQUEST_METHOD"] ) ) {
						if ( $error = $this->user_middleware->validateRecoveryToken( $data[1] ) ) {
							$this->viewData += [ "success" => "false", "msg" => $error ];
						} else {
							$this->viewData["success"] = "true";
						}
					} else if ( $this->helper->isRequestPOST( $_SERVER["REQUEST_METHOD"] ) ) {
						if (
							(
								$this->helper->validate_inputs([
									'token' => [ "REQUIRED" => true, "EMPTY" => false ],
									'btn-submit' => [ "REQUIRED" => true, "EMPTY" => false ],
									'newpassword' => [ "REQUIRED" => true, "EMPTY" => false ],
									'confirmation_password' => [ "REQUIRED" => true, "EMPTY" => false ]
								], $_POST)
							) &&
							(
								$_POST = $this->helper->filter_inputs( "POST", array(
									'token' => FILTER_SANITIZE_STRING,
									'btn-submit' => FILTER_SANITIZE_STRING,
									'newpassword' => FILTER_SANITIZE_STRING,
									'confirmation_password' => FILTER_SANITIZE_STRING
								))
							)
						) {
							unset( $_POST["btn-submit"] );
							if ( $error = $this->user_middleware->new_password( $_POST ) ) {
								$this->viewData += [ "success" => "false", "msg" => $error ];
							} else if ( $this->user_model->newpassword( array( password_hash($_POST["newpassword"], PASSWORD_BCRYPT), $data[1] ) ) ) {
								$this->viewData += [ "success" => "true", "msg" => "Your password has been changed successfully !" ];
							} else {
								$this->viewData += [ "success" => "false", "msg" => "Failed to change your password !" ];
							}
						} else {
							$this->viewData["success"] = "false";
							$this->viewData["msg"] = "Couldn't change your password, try later !";
						}
					}
				}
			} catch ( Exception $e ) {
				$this->viewData["success"] = "false";
				$this->viewData["msg"] = "Something goes wrong while change your password, try later !";
			}
			$this->call_view( 'home' . DIRECTORY_SEPARATOR .'new_password', $this->viewData)->render();
		}

		public function 				account_confirmation ( $data )
		{
			session_start();
			try {
				if ( $this->user_middleware->isSignin( $_SESSION ) ) {
					header("Location: /");
				} else if ( $this->helper->isRequestGET( $_SERVER["REQUEST_METHOD"] ) ) {
					if ( $error = $this->validateToken( $data ) ) {
						$this->viewData = [ "success" => "false", "msg" => $error ];
					} else {
						$this->viewData[ "data" ] = [ "token" => $data[1] ];
						if ( $error = $this->user_middleware->validateActivationToken( $data[1] ) ) {
							$this->viewData += [ "success" => "false", "msg" => $error ];
						} else if ( $this->user_model->activateAccount( array( 'token' => $data[1] ) ) ) {
							$this->viewData += [ "success" => "true", "msg" => "Your account has been activated successfully !" ];
						} else {
							$this->viewData += [ "success" => "false", "msg" => "Failed to activate your account !" ];
						}
					}
				}
			} catch ( Exception $e ) {
				$this->viewData["success"] = "false";
				$this->viewData["msg"] = "Something goes wrong while activating your account, try later !";
			}
			$this->call_view( 'home' . DIRECTORY_SEPARATOR .'account_confirmation', $this->viewData)->render();
		}

		public function 				notfound()
		{
			$this->call_view('home' . DIRECTORY_SEPARATOR .'notfound')->render();
		}

	}
?>