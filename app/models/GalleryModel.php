<?php
	class       GalleryModel extends DB
	{
		public function         addImage ( $data )
		{
			$query = "INSERT INTO gallery (userid, src) VALUES (?, ?)";
			$stt = $this->connect()->prepare($query);
			return $stt->execute([
			    $data['id'],
			    $data['src']
			]);
		}

		public function         userGallery ( $userid, $depart = 0, $imagePerPage = 6 )
		{
			$query = '
			    SELECT g.*, u.username, u.gender
			    FROM `gallery` g INNER JOIN `users` u
			    ON g.userid = u.id
			    WHERE userid = ?
			    ORDER BY createdat DESC LIMIT '.$depart.','.$imagePerPage;
			$stt = $this->connect()->prepare($query);
			$stt->execute([ $userid ]);
			return $stt->fetchAll(PDO::FETCH_ASSOC);
		}

		public function         getCountImages ()
		{
			$query = 'SELECT count(*) FROM `gallery`';
			$stt = $this->connect()->prepare($query);
			$stt->execute();
			return $stt->rowCount();
		}

		public function         getAllEditedImages ( $depart = 0, $imagePerPage = 5 )
		{
			$query = '
			    SELECT g.*, u.username, u.gender, g.createdat
			    FROM `gallery` g INNER JOIN `users` u
			    ON g.userid = u.id
			    ORDER BY g.createdat DESC LIMIT '.$depart.','.$imagePerPage;
			$stt = $this->connect()->prepare($query);
			$stt->execute();
			return $stt->fetchAll(PDO::FETCH_ASSOC);
		}

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

		public function          likeImage ( $imgid, $userid )
		{
			$query = '
			    INSERT INTO `likes` (userid, imgid) values (?, ?);
			    UPDATE `gallery` SET countlikes = countlikes + 1 WHERE id = ?;
			';
			$stt = $this->connect()->prepare($query);
			return $stt->execute([ $userid, $imgid, $imgid ]);
		}

		public function          unlikeImage ( $imgid, $userid )
		{
			$query = '
			    DELETE FROM likes WHERE imgid = ? AND userid = ?;
			    UPDATE `gallery` SET countlikes = countlikes - 1 WHERE id = ?;
			';
			$stt = $this->connect()->prepare($query);
			return $stt->execute([ $imgid, $userid, $imgid ]);
		}

		public function         deleteImage ( $imgid, $userid )
		{
			$query = "DELETE FROM `gallery` WHERE id = ? AND userid = ?";
			$stt = $this->connect()->prepare($query);
			return $stt->execute([ $imgid, $userid ]);
		}

	}
?>