<?php
	class Controller {
		
		static protected function				call_model ( $model )
		{
			if ( file_exists( MODELS . $model . '.php') ) { return new $model; }
		}

		static protected function 		call_view ( $view_name, $view_data = [] )
		{
			if ( file_exists(VIEWS . $view_name . '.php') ) {  return new View( $view_name, $view_data ); }
		}

		static protected function		call_middleware ( $middleware )
		{
			if ( file_exists( MIDDLEWARES . $middleware . '.php') ) { return new $middleware; }
		}
		
	}
?>