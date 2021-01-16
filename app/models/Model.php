<?php
	/**
	 *	Model model class
	 */
	class Model extends database
	{

		public function			__construct()
		{
			parent::__construct();
			$this->connect();
		}

	}

?>