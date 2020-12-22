<?php

	/**
	 *  likes model class
	 */
	class 		LikesModel extends DB
	{
		/* Like an image by a user */
		public function			likeImage ( $imgid, $userid )
		{
			$query1 = 'INSERT INTO `likes` (userid, imgid) values (?, ?);';
			$stt1 = $this->connect()->prepare( $query1 );
			return $stt1->execute([ $userid, $imgid ]);
		}

		/* unlike an image by a user */
		public function			unlikeImage ( $imgid, $userid )
		{
			$query = 'DELETE FROM likes WHERE imgid = ? AND userid = ?;';
			$stt = $this->connect()->prepare($query);
			return $stt->execute([ $imgid, $userid ]);
		}

		public function			getCountLikes ( $imgid )
		{
			$query = 'SELECT count(*) AS `count` FROM `likes` WHERE imgid = ?;';
			$stt = $this->connect()->prepare($query);
			$stt->execute([ $imgid ]);
			$data = $stt->fetch(PDO::FETCH_ASSOC);
			return $data["count"];
		}

		/* Get all users who liked a picture */
	 	public function			getUsersLikeImage ( $imgid )
		{
			$query = '
				SELECT u.id, u.username, u.firstname, u.lastname
				FROM `likes` l inner join `users` u
				ON l.userid = u.id
				WHERE l.imgid = ?
				ORDER BY l.createdat DESC
			';
			$stt = $this->connect()->prepare($query);
			$stt->execute([ $imgid ]);
			return $stt->fetchAll(PDO::FETCH_ASSOC);
		}

		/* is like exists of an image by a user */
		public function 		isLikeExists ( $imgid, $userid )
		{
			$query0 = 'SELECT count(*) AS `count` FROM `likes` WHERE imgid = ? AND userid = ?';
			$stt0 = $this->connect()->prepare( $query0 );
			$stt0->execute([ $imgid, $userid ]);
			$data = $stt0->fetch(PDO::FETCH_ASSOC);
			
			return ( $data['count'] == 0 ) ? false : true;
		}

	}

?>