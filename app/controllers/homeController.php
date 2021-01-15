<?php

	/**
	 * 	Home controller class
	 */
	class homeController extends Controller {

		public function 				__construct()
		{
			parent::__construct();
		}

		public function 				index()
		{
			session_start();
			try {
				$this->viewData["data"][ "gallery"] = $this->gallery_model->getAllEditedImages();
				if ( $this->user_middleware->isSignin( $_SESSION ) ) {
					$this->viewData["data"] += [
						"userData" => $this->user_model->findUserById( $_SESSION['userid'] ),
						"countUnreadNotifs" => $this->notifications_model->getCountUnreadNotifications( $_SESSION['userid'] )
					];
				}
				$this->viewData += [ "success" => "true" ];
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
				} else {
					switch ( $_SERVER["REQUEST_METHOD"] ) {
						case "GET":
							$this->viewData["success"] = "true";			
						break;
						case "POST":
							$_POST = filter_input_array(INPUT_POST, array(
								'username' => FILTER_SANITIZE_STRING,
								'password' => FILTER_SANITIZE_STRING,
								'btn-signin' => FILTER_SANITIZE_STRING
							));
							foreach ( $_POST as $key => $value ) { $_POST[ $key ] = is_bool( $_POST[ $key ] ) ? "" : $_POST[ $key ]; }
							if ( isset( $_POST["btn-signin"] ) ) {
								unset( $_POST["btn-signin"] );
								if ( $error = $this->user_middleware->signin( $_POST ) ) {
									$this->viewData = [
										"success" => "false",
										"msg" => $error
									];
								} else {
									$userData = $this->user_model->findUserByUsername( $_POST["username"] );
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
				$this->viewData = [
					"success" => "false",
					"msg" => "Couldn't login, try later !"
				];
			}
			$this->call_view( 'home' . DIRECTORY_SEPARATOR .'signin', $this->viewData )->render();
		}
		
		public function 				signup()
		{
			session_start();
			try {
				if ( $this->user_middleware->isSignin( $_SESSION ) ) {
					header("Location: /");
				} else {
					switch( $_SERVER["REQUEST_METHOD"] ) {
						case "GET":
							$this->viewData = [ "success" => "true" ];			
						break;
						case "POST":
							$_POST = filter_input_array( INPUT_POST, array(
								'btn-signup' => FILTER_SANITIZE_STRING,
								'firstname' => FILTER_SANITIZE_STRING,
								'lastname' => FILTER_SANITIZE_STRING,
								'username' => FILTER_SANITIZE_STRING,
								'email' => FILTER_SANITIZE_EMAIL,
								'gender' => FILTER_SANITIZE_STRING,
								'password' => FILTER_SANITIZE_STRING,
								'confirmation_password' => FILTER_SANITIZE_STRING
							));
							foreach ( $_POST as $key => $value ) { $_POST[ $key ] = is_bool( $_POST[ $key ] ) ? "" : $_POST[ $key ]; }
							if ( isset( $_POST["btn-signup"] ) && !empty( $_POST["btn-signup"] ) ) {
								unset( $_POST["btn-signup"] );
								if ( $error = $this->user_middleware->signup( $_POST ) ) {
									$this->viewData = [
										"success" => "false",
										"msg" => $error
									];
								} else if ( $atoken = $this->user_model->save( $_POST ) ) {
									$this->helper->sendMail("Confirmation mail", strtolower( $_POST["email"] ), $atoken);
									$this->viewData = [
										"success" => "true",
										"msg" => "Successful registration, you will receive an email for activation account !"
									];
								} else {
									$this->viewData = [
										"success" => "false",
										"msg" => "Failed to create your account !"
									];
								}
							}
						break;
					}
				}
			} catch ( Exception $e ) {
				$this->viewData = [
					"success" => "false",
					"msg" => "Something goes wrong, try later !"
				];
			}
			$this->call_view('home' . DIRECTORY_SEPARATOR .'signup', $this->viewData)->render();
		}

		public function 				reset_password()
		{
			session_start();
			try {
				if ( $this->user_middleware->isSignin( $_SESSION ) ) {
					header("Location: /");
				} else {
					switch ( $_SERVER["REQUEST_METHOD"] ) {
						case "GET":
							$this->viewData = [ "success" => "true" ];			
						break;
						case "POST": 
							$_POST = filter_input_array( INPUT_POST, array(
								'btn-reset' => FILTER_SANITIZE_STRING,
								'email' => FILTER_SANITIZE_EMAIL
							));
							foreach ( $_POST as $key => $value ) { $_POST[ $key ] = is_bool( $_POST[ $key ] ) ? "" : $_POST[ $key ]; }
							if ( isset( $_POST["btn-reset"] ) && !empty( $_POST["btn-reset"] ) ) {
								if ( $error = $this->user_middleware->reset_password( $_POST["email"] ) ) {
									$this->viewData = [
										"success" => "false",
										"msg" => $error
									];
								} else if ( $rToken = $this->user_model->resetpassword( $_POST["email"] ) ) {
									$this->helper->sendMail("Reset password", strtolower( $_POST["email"] ), $rToken);
									$this->viewData = [
										"success" => "true",
										"msg" => "A direct link for reset password has been sent successfully !"
									];
								} else {
									$this->viewData = [
										"success" => "false",
										"msg" => "failed to reset your password !"
									];
								}
							}
						break;
					}
				}
			} catch ( Exception $e ) {
				$this->viewData = [
					"success" => "false",
					"msg" => "Something goes wrong while reseting your password, try later !"
				];
			}
			$this->call_view('home' . DIRECTORY_SEPARATOR .'reset_password', $this->viewData)->render();
		}

		private function 				validateToken( $data )
		{
			if ( ( !isset( $data[0] ) || !isset( $data[1] ) ) || ( $data[0] !== "token" || !$data[1] ) ) {
				return "No token found !";
			}
		}

		public function 				account_confirmation ( $data )
		{
			session_start();
			try {
				if ( $this->user_middleware->isSignin( $_SESSION ) ) {
					header("Location: /");
				} else {
					switch ( $_SERVER["REQUEST_METHOD"] ) {
						case "GET":
							if ( $error = $this->validateToken( $data ) ) {
								$this->viewData = [
									"success" => "false",
									"msg" => $error
								];
							} else {
								$this->viewData[ "data" ] = [ "token" => $data[1] ];
								if ( $error = $this->user_middleware->validateActivationToken( $data[1] ) ) {
									$this->viewData += [
										"success" => "false",
										"msg" => $error
									];
								} else if ( $this->user_model->activateAccount( array( 'token' => $data[1] ) ) ) {
									$this->viewData += [
										"success" => "true",
										"msg" => "Your account has been activated successfully !"
									];
								} else {
									$this->viewData += [
										"success" => "false",
										"msg" => "Failed to activate your account !"
									];
								}
							}
						break;
					}
				}
			} catch ( Exception $e ) {
				$this->viewData = [
					"success" => "false",
					"msg" => "Something goes wrong while activating your account, try later !"
				];
			}
			$this->call_view( 'home' . DIRECTORY_SEPARATOR .'account_confirmation', $this->viewData)->render();
		}

		public function 				new_password( $data )
		{
			session_start();
			try {
				if ( $this->user_middleware->isSignin( $_SESSION ) ) {
					header("Location: /");
				} else if ( $error = $this->validateToken( $data ) ) {
					$this->viewData = [
						"success" => "false",
						"msg" => $error
					];
				} else {
					$this->viewData["data"][ "token"] = $data[1];
					switch ( $_SERVER["REQUEST_METHOD"] ) {
						case "GET":
							if ( $error = $this->user_middleware->validateRecoveryToken( $data[1] ) ) {
								$this->viewData += [
									"success" => "false",
									"msg" => $error
								];
							} else {
								$this->viewData += [ "success" => "true" ];
							}
						break;
						case "POST":
							if ( isset( $_POST["btn-submit"] ) ) {
								unset( $_POST["btn-submit"] ); $_POST["token"] = $data[1];
								if ( $error = $this->user_middleware->new_password( $_POST ) ) {
									$this->viewData += [
										"success" => "false",
										"msg" => $error
									];
								} else if ( $this->user_model->newpassword( array( password_hash($_POST["newpassword"], PASSWORD_BCRYPT), $data[1] ) ) ) {
									$this->viewData += [
										"success" => "true",
										"msg" => "Your password has been changed successfully !"
									];
								} else {
									$this->viewData += [
										"success" => "false",
										"msg" => "Failed to change your password !"
									];
								}
							}
						break;
					}
				}
			} catch ( Exception $e ) {
				$this->viewData["success"] = "false";
				$this->viewData["msg"] = "Something goes wrong, try later !";
			}
			$this->call_view( 'home' . DIRECTORY_SEPARATOR .'new_password', $this->viewData)->render();
		}

		public function 				notfound()
		{
			$this->call_view('home' . DIRECTORY_SEPARATOR .'notfound')->render();
		}

	}
?>