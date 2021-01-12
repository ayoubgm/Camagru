<?php
	/**
	 *  Database configuration class
	 */
	class		database {

		private $DSN;
		private $USER;
		private $PASSWORD;
		private $DB_NAME;
		protected $pdo;

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
				echo "Something went wrong while connect to database (".$e->getMessage().")";
			}
		}

		protected function					query( $query, $params = [] )
		{
			try {
				$stmt = $this->pdo->prepare( $query );
				if ( $stmt->execute( $params ) ) {
					return $stmt;
				}
			} catch( PDOException $e ) {
				echo "Something went wrong while execute the query (".$e->getMessage().")";
			}
		}
		
	}
?>