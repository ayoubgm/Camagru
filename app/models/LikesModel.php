<?php

	/**
	 *  Likes model class
	 */
	class		LikesModel extends DB
	{

		public function				likeImage ( $imgid, $userid )
		{
			$query = 'INSERT INTO `likes` (userid, imgid) values (?, ?);';
			$this->query( $query, [ $userid, $imgid ] );
		}

		public function				unlikeImage ( $imgid, $userid )
		{
			$query = 'DELETE FROM likes WHERE imgid = ? AND userid = ?;';
			$this->query( $query, [ $imgid, $userid ] );
		}

		public function				getCountLikes ( $imgid )
		{
			$query = 'SELECT count(*) AS `count` FROM `likes` WHERE imgid = ?;';
			$stt = $this->query( $query, [ $imgid ]);
			$data = $stt->fetch(PDO::FETCH_ASSOC);
			return $data["count"];
		}

	 	public function				getUsersLikeImage ( $imgid )
		{
			$query = '
				SELECT u.id, u.username, u.firstname, u.lastname
				FROM `likes` l inner join `users` u
				ON l.userid = u.id
				WHERE l.imgid = ?
				ORDER BY l.createdat DESC
			';
			$stt = $this->query( $query, [ $imgid ] );
			return $stt->fetchAll(PDO::FETCH_ASSOC);
		}

		public function				isLikeExists ( $imgid, $userid )
		{
			$query = 'SELECT count(*) AS `count` FROM `likes` WHERE imgid = ? AND userid = ?';
			$stt = $this->query( $query, [ $imgid, $userid ] );
			$data = $stt->fetch(PDO::FETCH_ASSOC);
			return ( $data['count'] == 0 ) ? false : true;
		}

	}

?>