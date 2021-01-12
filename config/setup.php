<?php
	class	setup extends database {

		public function 		setupDatabase()
		{
			$queries = file_get_contents(CONFIG . 'queries.sql');
			return $this->query( $queries );
		}

	}
?>