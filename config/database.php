<?php
	/**
	 *  Database configuration class
	 */
	class		database {

		private $DB_NAME;
		private $DSN;
		private $USER;
		private $PASSWORD;
		public $pdo;

		public function						__construct()
		{
			$this->DB_NAME = "db_camagru";
			$this->DSN = "mysql:host=" . $_SERVER["HTTP_HOST"] . ";dbname=". $this->DB_NAME . ";charset=utf8";
			$this->USER = "root";
			$this->PASSWORD = "tiger";
		}

		public function						connect()
		{
			try
			{
				if ( $this->pdo ) {
					return $this->pdo;
				} else {
					$this->pdo = new PDO( $this->DSN, $this->USER, $this->PASSWORD );
					$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					return $this->pdo;
				}
			} catch( PDOException $e ) {
				die($e->getMessage());
				die("Something went wrong while connect to database ".$e->getMessage());
			}
		}

		public function					query( $query, $params = [] )
		{
			try {
				$stmt = $this->pdo->prepare( $query );
				if ( $stmt->execute( $params ) ) {
					return $stmt;
				}
			} catch( PDOException $e ) {
				die("Something went wrong while execute the query ".$e->getMessage());
			}
		}
		
	}
?>