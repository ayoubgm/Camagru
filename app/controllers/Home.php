<?php
	class Home extends Controller {

		public function		index ()
		{
			$this->call_view('home' . DIRECTORY_SEPARATOR .'index')->render();
		}

		// private function 	sendMail ( $subject, $to ) {
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
		
		public function		signup()
		{
			switch($_SERVER['REQUEST_METHOD']) {
				case 'GET':
					$this->call_view('home' . DIRECTORY_SEPARATOR .'signup')->render();
				break;
				case 'POST':
					$userMiddleware = $this->call_middleware('UserMiddleware');
					$userMiddleware->signup($this, $_POST);
					$userModel = $this->call_model('UserModel');
					try {
						if ( $userModel->save($_POST) ) {
							// $this->sendMail("Confirmation mail", strtolower($_POST['email']));
							$view = $this->call_view( 'home' . DIRECTORY_SEPARATOR .'signup', [ 'success' => "true", 'msg' => "Successful registration, you will receive an email for activation account !" ])->render();
						} else {
							$view = $this->call_view( 'home' . DIRECTORY_SEPARATOR .'signup', [ 'success' => "false", 'msg' => "Registration failed !" ] )->render();
						}
					} catch ( Exception $e ) {
						$view = $this->call_view( 'home' . DIRECTORY_SEPARATOR .'signup', [ 'success' => "false", 'msg' => "Something goes wrong, try later !" ] )->render();
					}
				break;
			}
		}
		
		public function		signin()
		{
			$this->call_view('home' . DIRECTORY_SEPARATOR .'signin')->render();
		}
		
		public function		notfound()
		{
			echo "404";
		}

	}
?>