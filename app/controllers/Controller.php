<?php

	/**
	 * 	controller class
	 */
	class Controller {
		
		protected $viewData;
		protected $user_middleware;
		protected $user_model;
		protected $gallery_model;
		protected $notifications_model;
		protected $helper;

		protected function				__construct()
		{
			$this->viewData = array();
			$this->user_middleware = $this->call_middleware('UserMiddleware');
			$this->user_model = $this->call_model('UsersModel');
			$this->gallery_model = $this->call_model('GalleryModel');
			$this->notifications_model = $this->call_model('NotificationsModel');
			$this->helper = $this->call_helper();
		}

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