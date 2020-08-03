<?php
	class Home extends Controller{

		public function		index ()
		{
			$this->call_view('home' . DIRECTORY_SEPARATOR .'index');
		}

		public function		signin()
		{
			$this->call_view('home' . DIRECTORY_SEPARATOR .'signin');
		}

		public function		signup()
		{
			switch($_SERVER['REQUEST_METHOD']) {
				case 'GET':
					$this->call_view('home' . DIRECTORY_SEPARATOR .'signup');
					break;
				case 'POST':
					print_r($_POST);
					break;
			}
		}

		public function		notfound()
		{
			echo "404";
		}

	}
?>