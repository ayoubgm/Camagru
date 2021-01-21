<?php

	class      setupController extends database
	{
		
		public function			index()
		{
			try {
				$pdo = new PDO( "mysql:host=" . $_SERVER["HTTP_HOST"], "root", "tiger" );
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$pdo->exec("DROP DATABASE IF EXISTS db_camagru");
				header("Location: /home");
			} catch ( PDOException $e ) {
				var_dump( $e );
				// return NULL;
			}
		}

	}

?>