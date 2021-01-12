<?php

	/**
	 * 	router class
	 */
	class Router {
		
		protected $url;
		protected $controller = "homeController";
		protected $method = "index";
		protected $params = [];
		

		
		public function 				__construct()
		{
			$this->url = $this->parseURL();
			$setup = new setup;
			$setup->connect();
			$this->redirect();
		}
		
		protected function				redirect()
		{
			$this->controller = new homeController();
			if ( isset( $this->url[0] ) && !empty( $this->url[0] ) ) {
				if ( file_exists( CONTROLLERS . strtolower($this->url[0]) .'Controller.php' ) ) {
					$this->controller = strtolower( $this->url[0] ) . 'Controller';
					unset($this->url[0]);
					$this->controller = new $this->controller();
					if ( isset($this->url[1]) && !empty( $this->url[1] ) ) {
						if ( method_exists($this->controller, strtolower($this->url[1])) ) {
							$this->method = strtolower($this->url[1]);
							unset($this->url[1]);
						} else {
							$this->controller = new homeController();
							$this->method = 'notfound';
						}
					} else if ( !method_exists($this->controller, $this->method ) ) {
						$this->controller = new homeController();
						$this->method = 'notfound';
					}
				} else {
					if ( method_exists($this->controller, strtolower($this->url[0]) ) ) {
						$this->method = strtolower($this->url[0]);
						unset($this->url[0]);
					}else {
						$this->method = 'notfound';
					}
				}
			} else if ( !method_exists($this->controller, $this->method ) ) {
				$this->method = 'notfound';
			}
			// Create or recreate database `db_camagru` on the mysql server 
			// if ( $this->controller instanceof homeController && $this->method === "index" ) {
			// 	if ( !$setup->setupDatabase() ) {
			// 		echo "Failed to create or recreate the database !";
			// 	}
			// }
			$this->params = $this->url ? array_values( $this->url ) : [];
			call_user_func_array([ $this->controller, $this->method ], [ $this->params ]);
		}
		
		protected function 				parseURL()
		{
			if ( isset( $_GET['url'] ) ) {
				return explode('/', filter_var( rtrim( strtolower($_GET['url']), '/' ), FILTER_SANITIZE_URL));
			}
		}
	}
?>