<?php

	/**
	 * 	router class
	 */
	class Router {
		
		protected $controller = "homeController";
		protected $method = "index";
		protected $params = [];
		
		public function 				__construct()
		{
			require(CONFIG . 'config.php');
			$setupObject = new setup;
			$setupObject->connect();

			$url = $this->parseURL();
			$this->controller = new homeController();
			if ( isset( $url[0] ) && !empty( $url[0] ) ) {
				if ( file_exists( CONTROLLERS . strtolower($url[0]) .'Controller.php' ) ) {
					$this->controller = strtolower( $url[0] ) . 'Controller';
					unset($url[0]);
					$this->controller = new $this->controller();
					if ( isset($url[1]) && !empty( $url[1] ) ) {
						if ( method_exists($this->controller, strtolower($url[1])) ) {
							$this->method = strtolower($url[1]);
							unset($url[1]);
						} else {
							$this->controller = new homeController();
							$this->method = 'notfound';
						}
					} else if ( !method_exists($this->controller, $this->method ) ) {
						$this->controller = new homeController();
						$this->method = 'notfound';
					}
				} else {
					if ( method_exists($this->controller, strtolower($url[0]) ) ) {
						$this->method = strtolower($url[0]);
						unset($url[0]);
					}else {
						$this->method = 'notfound';
					}
				}
			} else if ( !method_exists($this->controller, $this->method ) ) {
				$this->method = 'notfound';
			}

			// Create or recreate database `db_camagru` on the mysql server 
			if ( $this->controller instanceof homeController && $this->method === "index" ) {
				if ( !$setupObject->setup() ) {
					echo "Failed to create or recreate the database !";
				}
			}
			$this->params = $url ? array_values( $url ) : [];
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