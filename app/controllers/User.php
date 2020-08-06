<?php
	class User extends Controller {

		public function		profile() {
			$this->call_view( 'user' . DIRECTORY_SEPARATOR .'profile', )->render();
		}

		public function     logout () {
			$this->call_view( 'user' . DIRECTORY_SEPARATOR .'logout', )->render();
		}

	}
?>