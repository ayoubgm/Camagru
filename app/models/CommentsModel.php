<?php

	/**
	 *	comments model class
	 */
	class 		CommentsModel extends DB
	{
		/* Get all comments of an image */
		public function 		getCommentsOfImg ( $imgid )
		{
			$query = '
				SELECT c.*, u.firstname, u.lastname, u.username, u.email, u.gender
				FROM `comments` c INNER JOIN `users` u
				ON c.userid = u.id
				WHERE c.imgid = ?
				ORDER BY c.createdat ASC
			';
			$stt = $this->connect()->prepare($query);
			$stt->execute([ $imgid ]);
			return $stt->fetchAll(PDO::FETCH_ASSOC);
		}

		public function			getComment ( $id )
		{
			$query = '
				SELECT c.*, u.firstname, u.lastname, u.username, u.email, u.gender
				FROM `comments` c INNER JOIN `users` u
				ON c.id = ?
			';
			$stt = $this->connect()->prepare($query);
			$stt->execute([ $id ]);
			return $stt->fetch(PDO::FETCH_ASSOC);
		}

		/* Save a comment */
		public function 		save ( $data )
		{
			$query = 'INSERT INTO `comments` (content, userid, imgid) values (?, ?, ?)';
			$db = $this->connect();
			$stt = $db->prepare($query);
			if ( $stt->execute( array_values( $data ) ) ) { return $db->lastInsertId(); }
			else { return NULL; }
		}

		/* Save a comment */
		public function 		edit ( $data )
		{
			$query = 'UPDATE `comments` SET content = ? WHERE userid = ? AND imgid = ?';
			$stt = $this->connect()->prepare( $query );
			return $stt->execute( array_values( $data ) );
		}

		public function			delete ( $id )
		{
			$query = 'DELETE FROM `comments` WHERE id = ?';
			$stt = $this->connect()->prepare( $query );
			return $stt->execute([ $id ]);
		}

	}

?>