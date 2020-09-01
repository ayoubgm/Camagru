<?php

	/**
	 * Comments model
	 */
	class 		CommentsModel extends DB
	{

		/* Save a comment */
		public function 		save ( $data )
		{
			$query = 'INSERT INTO `comments` (content, userid, imgid) values (?, ?, ?)';
			$stt = $this->connect()->prepare($query);
			return $stt->execute( array_values( $data ) );
		}

		/* Get all comments of an image */
		public function 		getCommentOfImg ( $imgid )
		{
			$query = '
				SELECT c.*, u.firstname, u.lastname, u.username, u.email, u.gender 
				FROM `comments` c INNER JOIN `users` u
				ON c.userid = u.id
				WHERE c.imgid = ?
			';
			$stt = $this->connect()->prepare($query);
			$stt->execute([ $imgid ]);
			return $stt->fetchAll(PDO::FETCH_ASSOC);
		}

	}

?>