<?php
	/**
	 *  view class
	 */
	class view
	{

		protected $view_name;
		protected $view_data;

		public function				__construct( $name, $data = [] )
		{
			$this->view_name = $name;
			$this->view_data = $data;
		}

		public function				render ()
		{
			if ( file_exists(VIEWS . $this->view_name . '.php') ) {
				require_once(VIEWS . $this->view_name . '.php');
			}
		}
		
	}
?>