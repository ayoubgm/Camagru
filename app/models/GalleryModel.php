<?php
	
	/**
	 *  Gallery model
	 */
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

		public function         deleteImage ( $imgid, $userid )
		{
			$query = "DELETE FROM `gallery` WHERE id = ? AND userid = ?";
			$stt = $this->connect()->prepare($query);
			return $stt->execute([ $imgid, $userid ]);
		}

	}
?>