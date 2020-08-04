<?php
	class Home extends Controller {

		public function		index ()
		{
			$indexView = $this->call_view('home' . DIRECTORY_SEPARATOR .'index');
			$indexView->render();
		}

		public function		signin()
		{
			$signinView = $this->call_view('home' . DIRECTORY_SEPARATOR .'signin');
			$signinView->render();
		}

		public function		signup()
		{
			switch($_SERVER['REQUEST_METHOD']) {
				case 'GET':
					$signupView = $this->call_view('home' . DIRECTORY_SEPARATOR .'signup');
					$signupView->render();
					break;
				case 'POST':
					$userMiddleware = $this->call_middleware('UserMiddleware');
					$userMiddleware->signup($this, $_POST);
					$userModel = $this->call_model('UserModel');
					$userModel->save($_POST);
					break;
			}
		}

		public function		notfound()
		{
			echo "404";
		}

	}
?>