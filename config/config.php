<?php
	/**
	 *  Database configuration class
	 */
	class		DB {

		private static $HOST = "localhost";
		private static $USER = "root";
		private static $PASSWORD = "root";
		private static $DB_NAME = "db_camagru";
		protected $pdo;

		public function						__construct()
		{
			$this->connect();
		}

		public function						connect()
		{
			try
			{
				$this->pdo = new PDO("mysql:host=".self::$HOST.";dbname=".self::$DB_NAME.";charset=utf8", self::$USER, self::$PASSWORD);
				$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch( PDOException $e ) {
				echo "Something went wrong while connect to database (".$e->getMessage().")";
			}
		}

		protected function					query( $query, $params = [] )
		{
			$stt = $this->pdo->prepare( $query );
			return ( $stt->execute( $params ) ) ? $stt : NULL;
		}
		
	}
?>