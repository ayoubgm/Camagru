<?php
	class Controller {
		
		protected $view;
		protected $model;

		public function     call_model ( $model )
		{
			if ( file_exists( MODELS . $model . '.php') ) { return new $model; }
		}

		public function 	call_view ( $view_name, $view_data = [] )
		{
			$this->view = new View($view_name, $view_data);
		}

		public function		call_middleware ( $middleware )
		{
			if ( file_exists( MIDDLEWARES . $middleware . '.php') ) { return new $middleware; }
		}

	}
?>