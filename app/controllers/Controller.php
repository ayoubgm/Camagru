<?php

	/**
	 * 	controller class
	 */
	class Controller {
		
		protected function				call_model ( $model )
		{
			if ( file_exists( MODELS . $model . '.php') ) { return new $model(); }
		}

		protected function				call_view ( $view_name, $view_data = [] )
		{
			if ( file_exists(VIEWS . $view_name . '.php') ) {  return new View( $view_name, $view_data ); }
		}

		protected function				call_middleware ( $middleware )
		{
			if ( file_exists( MIDDLEWARES . $middleware . '.php') ) { return new $middleware(); }
		}

		protected function				call_helper ()
		{
			if ( file_exists( HELPERS . "helper.php" ) ) { return new helper(); }
		}
		
	}
?>