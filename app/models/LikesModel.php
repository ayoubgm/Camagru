<?php

	/**
	 *  likes model class
	 */
	class 		LikesModel extends DB
	{
		
		/* Get all users who liked a picture */
	 	public function          getUsersLikeImage ( $imgid )
		{
			$query = '
				SELECT u.id, u.username
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

		/* Like an image by a user */
		public function          likeImage ( $imgid, $userid )
		{
			if ( !self::isLikeExists( $imgid, $userid ) ) {
				$query1 = '
				    INSERT INTO `likes` (userid, imgid) values (?, ?);
				    UPDATE `gallery` SET countlikes = countlikes + 1 WHERE id = ?;
				';
				$stt1 = $this->connect()->prepare( $query1 );
				return $stt1->execute([ $userid, $imgid, $imgid ]);
			}
			return true;
		}

		/* unlike an image by a user */
		public function          unlikeImage ( $imgid, $userid )
		{
			if ( self::isLikeExists( $imgid, $userid ) ) {
				$query = '
				    DELETE FROM likes WHERE imgid = ? AND userid = ?;
				    UPDATE `gallery` SET countlikes = countlikes - 1 WHERE id = ?;
				';
				$stt = $this->connect()->prepare($query);
				return $stt->execute([ $imgid, $userid, $imgid ]);
			}
			return true;
		}

	}

?>