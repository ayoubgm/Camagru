<?php
	class DB {

		private static $HOST = "localhost";
		private static $USER = "root";
		private static $PASSWORD = "root";
		private static $DB_NAME = "db_camagru";

		public static function		connect()
		{
			try
			{
				$pdo = new PDO("mysql:host=".self::$HOST.";dbname=".self::$DB_NAME.";charset=utf8", self::$USER, self::$PASSWORD);
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
				return $pdo;
			}
			catch( PDOException $e ) {
				echo 'Connection failed: ' . $e->getMessage();
			}
		}

		public static function		query( $query, $params )
		{
			try
			{
				$statement = self::connect()->prepare( $query );
				$statement->execute($params);
				if ( explode(' ', $query)[0] == 'SELECT' ) { return $statement->fetchAll(); }
			}
			catch ( PDOException $e ) {
				echo 'Query failed: ' . $e->getMessage();
			}
		}
		
	}
?>