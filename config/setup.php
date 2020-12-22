<?php
	class	setup extends DB {
		
		public function			__construct()
		{
			parent::__construct();
		}

		public function 		setup()
		{
			$queries = file_get_contents(CONFIG . 'queries.sql');
			$this->query( $queries );
		}

	}
?>