<?php
	class User extends Controller {

		public function     logout () {
			$this->call_view( 'user' . DIRECTORY_SEPARATOR .'logout', )->render();
		}

	}
?>