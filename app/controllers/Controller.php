<?php
	class Controller {
		
		public function     call_model( $model )
		{
			if ( file_exists(MODELS . $model . '.php') ) { return new $model(); }
		}

		public function 	call_view( $view )
		{
			if ( file_exists(VIEWS . $view . '.php') ) { require_once(VIEWS . $view . '.php'); }
		}

	}
?>