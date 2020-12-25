<?php
	class	setup extends DB {

		public function 		setup()
		{
			$queries = file_get_contents(CONFIG . 'queries.sql');
			return $this->query( $queries );
		}

	}
?>