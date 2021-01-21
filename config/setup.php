<?php
	class	setup extends database {

		protected $dbname; 
		protected $dsnHost;
		protected $user;
		protected $password;

		public function		__construct()
		{
			$this->dbname = "db_camagru";
			$this->dsnHost = "mysql:host=" . $_SERVER["HTTP_HOST"];
			$this->user = "root";
			$this->password = "tiger";
		}

		public function 		setupDatabase()
		{
			try {
				$pdoInstance = new PDO( $this->dsnHost, $this->user, $this->password );
				$pdoInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $pdoInstance->prepare( "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME='$this->dbname'" );
				$stmt->execute();

				if ( !$stmt->fetch() ) {
					$queries = file_get_contents(CONFIG . 'queries.sql');
					$stmt = $pdoInstance->prepare( $queries );
					$stmt->execute();
				}
				return true;
			} catch ( PDOException $e ) {
				return null;
			}
		}

	}
?>