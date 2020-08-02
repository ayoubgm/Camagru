<?php

	class Home extends Controller{

		public function		index () {
			$this->view('home' . DIRECTORY_SEPARATOR .'index');
		}

		public function		signin() {
			$this->view('home' . DIRECTORY_SEPARATOR .'signin');
		}

		public function		signup() {
			$this->view('home' . DIRECTORY_SEPARATOR .'signup');
		}

		public function		notfound() {
			echo "404";
		}

	}

?>