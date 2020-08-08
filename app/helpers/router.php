<?php
	class Router {
		
		protected $controller = "homeController";
		protected $method = "index";
		protected $params = [];
		
		public function __construct()
		{
			require(CONFIG . 'config.php');
			$url = $this->parseURL();

			if ( isset($url[0]) && file_exists( CONTROLLERS . strtolower($url[0]) .'Controller.php' ) ) {
				$this->controller = strtolower( $url[0] ) . 'Controller';
				unset($url[0]);
				$this->controller = new $this->controller();
				if ( isset($url[1]) && method_exists($this->controller, strtolower($url[1])) ){
					$this->method = strtolower($url[1]); unset($url[1]);
				} else if ( !$this->controller instanceof homeController ) {
					$this->controller = new homeController();
					$this->method = 'notfound';
				}
			} else {
				$this->controller = new $this->controller();
				// if $url[0] not a controller it can be a method of home controller 
				if ( isset($url[0]) && method_exists($this->controller, strtolower($url[0]) ) ) { $this->method = strtolower($url[0]); }
				else { $this->method = 'notfound'; }
			}
			// if ( $this->controller instanceof homeController && $this->method === "index" ) {
			// 	$objsetup = new Setup();
			// 	$objsetup->setup();
			// }
			$this->params = $url ? array_values($url) : [];
			call_user_func_array([$this->controller, $this->method], [ $this->params ]);
		}
		
		protected function parseURL()
		{
			if ( isset($_GET['url']) ) {
				return  $url = explode('/', filter_var(rtrim(strtolower($_GET['url']), '/'), FILTER_SANITIZE_URL));
			}
		}
	}
?>