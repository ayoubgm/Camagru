<?php

	/**
	 *	Comments model class
	 */
	class 		CommentsModel extends database
	{

		public function				getCommentsOfImg ( $imgid )
		{
			$query = "
				SELECT c.*, u.firstname, u.lastname, u.username, u.email, u.gender
				FROM `comments` c INNER JOIN `users` u
				ON c.userid = u.id
				WHERE c.imgid = ?
				ORDER BY c.createdat ASC
			";
			if ( $stt = $this->query( $query, [ $imgid ]) ) {
				return $stt->fetchAll(PDO::FETCH_ASSOC);
			}
		}

		public function				getComment ( $id )
		{
			$query = "
				SELECT c.*, u.firstname, u.lastname, u.username, u.email, u.gender
				FROM `comments` c INNER JOIN `users` u
				ON c.id = ?
			";
			if ( $stt = $this->query( $query, [ $id ] ) ) {
				return $stt->fetch(PDO::FETCH_ASSOC);
			}
		}

		public function				save ( $data )
		{
			if ( $this->query( "INSERT INTO `comments` (content, userid, imgid) values (?, ?, ?)", array_values( $data ) ) ) {
				return $this->pdo->lastInsertId();
			}
		}

		public function				edit ( $data )
		{
			return $this->query( "UPDATE `comments` SET content = ? WHERE userid = ? AND imgid = ?", array_values( $data ) );
		}

		public function				delete ( $id )
		{
			return$this->query( "DELETE FROM `comments` WHERE id = ?", [ $id ] );
		}

	}

?>