<?php
	class Router {
		
		protected $controller = "Home";
		protected $method = "index";
		protected $params = [];
		
		public function __construct()
		{
			require_once(CONFIG . 'config.php');
			
			
			$url = $this->parseURL();
			
			if ( isset($url[0]) && file_exists( CONTROLLERS . ucfirst($url[0]) .'.php' ) ) {
				$this->controller = ucfirst($url[0]);
				unset($url[0]);
				$this->controller = new $this->controller();
				if ( isset($url[1]) && method_exists($this->controller, strtolower($url[1])) ){
					$this->method = strtolower($url[1]);
					unset($url[1]);
				} else {
					if ( !$this->controller instanceof Home ) { $this->method = 'notfound'; }
				}
			} else {
				$this->controller = new $this->controller();
				
				if ( isset($url[0]) ) {
					if (method_exists($this->controller, strtolower($url[0]))) {
						$this->method = strtolower($url[0]);
					} else {
						$this->method = "notfound";
					}
				}
			}
			$this->params = $url ? array_values($url) : [];
			// if ( $this->controller instanceof Home && $this->method === "index" ) {
			// 	$objsetup = new Setup();
			// 	$objsetup->setup();
			// }
			call_user_func_array([$this->controller, $this->method], $this->params);
		}
		
		protected function parseURL()
		{
			if ( isset($_GET['url']) ) {
				return  $url = explode('/', filter_var(rtrim(strtolower($_GET['url']), '/'), FILTER_SANITIZE_URL));
			}
		}
	}
?>