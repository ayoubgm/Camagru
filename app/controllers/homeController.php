<?php
	class homeController extends Controller {

		private $userMiddleware;
		private $userModel;
		private $galleryModel;

		public function				__construct()
		{
			$this->userMiddleware = self::call_middleware('UserMiddleware');
			$this->userModel = self::call_model('UserModel');
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

		public function				index ()
		{
			session_start();
			$homeObj = new homeController();
			$viewData = array();
			$viewData['gallery'] = $homeObj->galleryModel->getAllEditedImages();

			if ( isset( $_SESSION['userid'] ) ) {
				$viewData['userData'] = $homeObj->userModel->findUserById( $_SESSION['userid'] );
			}
			$viewData = [ 'success' => "true", 'data' => $viewData ];
			$homeObj->call_view( 'home' . DIRECTORY_SEPARATOR .'index', $viewData )->render();
		}
		
		public function				signup()
		{
			$viewData = [];
			
			switch( $_SERVER['REQUEST_METHOD'] ) {
				case 'GET':
					$viewData = [];
				break;
				case 'POST':
					if ( isset($_POST['btn-signup']) ) {
						unset( $_POST['btn-signup'] );

						if ( ( $error = $this->userMiddleware->signup($_POST) ) != null ) {
							$viewData = [ 'success' => "false", 'msg' => $error ];
						} else {
							try {
								if ( $this->userModel->save( $_POST ) ) {
									// $this->sendMail("Confirmation mail", strtolower($_POST['email']));
									$viewData = [ 'success' => "true", 'msg' => "Successful registration, you will receive an email for activation account !" ];
								} else {
									$viewData = [ 'success' => "false", 'msg' => "Registration failed !" ];
								}
							} catch ( Exception $e ) {
								$viewData = [ 'success' => "false", 'msg' => "Something goes wrong, try later !" ];
							}
						}
					}
				break;
			}
			$this->call_view('home' . DIRECTORY_SEPARATOR .'signup', $viewData)->render();
		}
		
		public function		signin()
		{
			$viewName = "";
			$viewData = [];
			switch ( $_SERVER['REQUEST_METHOD'] ) {
				case "GET":
					$viewName = 'home' . DIRECTORY_SEPARATOR .'signin';
					$viewData = [ 'success' => "true" ];
				break;
				case "POST":
					if ( isset( $_POST['btn-signin'] ) ) {
						unset( $_POST['btn-signin'] );
						if ( ($error = $this->userMiddleware->signin($_POST)) != null){
							$viewData = [ 'success' => "false", 'msg' => $error ];
						} else {
							session_start();
							$userData = $this->userModel->findUserByUsername($_POST['username']);
							$_SESSION['userid'] = $userData['id'];
							$_SESSION['username'] = $userData['username'];
							$viewName = 'home';
							$viewData = [ 'success' => "true", 'msg' => "You have been logged successfully !" ];
							header("Location: /camagru_git/home");
						}
					}
				break;
			}
			$this->call_view( $viewName,  $viewData)->render();
		}

		public function		reset_password ( )
		{
			session_start();
			$viewData = [];
			if ( isset( $_SESSION['userid'] ) ) { header("Location: /camagru_git/home"); }
			else {
				switch ( $_SERVER['REQUEST_METHOD'] ) {
					case "GET":
						$viewData = [];
					break;
					case "POST":
						if ( isset( $_POST['btn-reset'] ) ) {
							unset( $_POST['btn-reset'] );
							if ( ( $error = $this->userMiddleware->reset_password($_POST['email']) ) != null ) {
								$viewData = [ 'success' => "false", 'msg' => $error ];
							} else {
								try {
									if ( $this->userModel->resetpassword($_POST['email']) ) {
										// $this->sendMail("Reset password", strtolower($_POST['email']));
										$viewData = [ 'success' => "true", 'msg' => "A direct link for reset password has been sent successfully !" ];
									} else {
										$viewData = [ 'success' => "false", 'msg' => "Failed to reset your password !" ];
									}
								} catch ( Exception $e ) {
									$viewData = [ 'success' => "false", 'msg' => "Something goes wrong while reseting your password !" ];
								}
							}
						}
					break;
				}
			}
			$this->call_view('home' . DIRECTORY_SEPARATOR .'reset_password', $viewData)->render();
		}

		private function		validateToken ( $data )
		{
			if ( ( !isset( $data[0] ) || !isset( $data[1] ) ) || ( $data[0] !== "token" || !$data[1] ) ) { return "No token found !"; }
			else { return null; }
		}

		public function		new_password ( $data )
		{
			session_start();
			$viewData = [];
			if ( isset( $_SESSION['userid'] ) ) {
				header("Location: /camagru_git/home");
			} else if ( ( $error = $this->validateToken( $data ) ) != null ) {
				$viewData = [ 'success' => "false", 'msg' => $error ];
			} else {
				switch ( $_SERVER['REQUEST_METHOD'] ) {
					case "GET":
						if ( ( $error = $this->userMiddleware->validateRecoveryToken( $data[1] ) ) != null ) { $viewData = [ 'success' => "false", 'msg' => $error, 'data' => $data[1] ]; }
						else { $viewData = [ 'success' => "true", 'data' => $data[1] ]; }
					break;
					case "POST":
						if ( isset( $_POST['btn-submit'] ) ) {
							unset( $_POST['btn-submit'] );
							$_POST['token'] = $data[1];
							if ( ( $error = $this->userMiddleware->new_password( $_POST ) ) != null ) {
								$viewData = [ 'success' => "false", 'msg' => $error, 'data' => $data[1] ];
							} else {
								try {
									if ( $this->userModel->newpassword( array( 'newpassword' =>  password_hash($_POST['newpassword'], PASSWORD_ARGON2I), 'token' => $data[1] ) ) ) {
										$viewData = [ 'success' => "true", 'msg' => "Your password has been changed successfully !", 'data' => $data[1] ];
									}
									else { $viewData = [ 'success' => "false", 'msg' => "Failed to change your password !", 'data' => $data[1] ]; }
								} catch ( Exception $e ) {
									$viewData = [ 'success' => "false", 'msg' => "Something goes wrong while changing your password !", 'data' => $data[1] ];
								}
							}
						}
					break;
				}
			}
			$this->call_view( 'home' . DIRECTORY_SEPARATOR .'new_password', $viewData)->render();
		}
		
		public function account_confirmation ( $data )
		{
			$viewData = [];

			session_start();
			if ( isset( $_SESSION['userid'] ) ) {
				header("Location: /camagru_git/home");
			} else if ( ( $error = $this->validateToken( $data ) ) != null ) {
				$viewData = [ 'success' => "false", 'msg' => $error ];
			} else {
				if ( ( $error = $this->userMiddleware->validateActivationToken( $data[1] ) ) != null ) {
					$viewData = [ 'success' => "false", 'msg' => $error ];
				} else {
					try {
						if ( $this->userModel->activateAccount( array( 'token' => $data[1] ) ) ) {
							$viewData = [ 'success' => "true", 'msg' => "Your account has been activated successfully !", 'data' => $data[1] ];
						} else {
							$viewData = [ 'success' => "false", 'msg' => "Failed to activate your account !", 'data' => $data[1] ];
						}
					} catch ( Exception $e ) {
						$viewData = [ 'success' => "false", 'msg' => "Something goes wrong while activating your account !", 'data' => $data[1] ];
					}
				}
			}
			$this->call_view( 'home' . DIRECTORY_SEPARATOR .'account_confirmation', $viewData)->render();
		}

		public function		notfound()
		{
			$this->call_view( 'home' . DIRECTORY_SEPARATOR .'notfound')->render();
		}

	}
?>