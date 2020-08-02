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
			$this->call_view('home' . DIRECTORY_SEPARATOR .'signup');
		}

		public function		notfound()
		{
			echo "404";
		}

	}
?>