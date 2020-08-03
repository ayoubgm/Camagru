<?php
	class Setup extends DB {
		
		public function 		setup()
		{
			$pdo = $this->connect();
			$queries = file_get_contents(CONFIG . 'queries.sql');
			$done = $pdo->query($queries);
		}

	}
?>