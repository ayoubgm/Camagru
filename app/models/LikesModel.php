<?php

	/**
	 *  Like model
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

		/* Like an image by a user */
		public function          likeImage ( $imgid, $userid )
		{
			$query = '
			    INSERT INTO `likes` (userid, imgid) values (?, ?);
			    UPDATE `gallery` SET countlikes = countlikes + 1 WHERE id = ?;
			';
			$stt = $this->connect()->prepare($query);
			return $stt->execute([ $userid, $imgid, $imgid ]);
		}

		/* unlike an image by a user */
		public function          unlikeImage ( $imgid, $userid )
		{
			$query = '
			    DELETE FROM likes WHERE imgid = ? AND userid = ?;
			    UPDATE `gallery` SET countlikes = countlikes - 1 WHERE id = ?;
			';
			$stt = $this->connect()->prepare($query);
			return $stt->execute([ $imgid, $userid, $imgid ]);
		}

	}

?>