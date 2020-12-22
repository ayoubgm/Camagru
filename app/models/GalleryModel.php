<?php
	
	/**
	 *  Gallery model class
	 */
	class		GalleryModel extends DB
	{
		public function				getAllEditedImages ( $depart = 0, $imagePerPage = 5 )
		{
			$query = '
			    SELECT g.*, u.username, u.gender
			    FROM `gallery` g INNER JOIN `users` u
			    ON g.userid = u.id
				ORDER BY g.createdat DESC
				LIMIT '.$depart.','.$imagePerPage;
			$stt = $this->query( $query );
			return $stt->fetchAll(PDO::FETCH_ASSOC);
		}


		public function				userGallery ( $username, $depart = 0, $imagePerPage = 6 )
		{
			$query = '
				SELECT g.*, u.username, u.gender
				FROM `gallery` g INNER JOIN `users` u
				ON g.userid = u.id
				WHERE u.username = ?
				ORDER BY g.createdat DESC
				LIMIT '.$depart.','.$imagePerPage;
			$stt = $this->query( $query, [ strtolower( $username ) ] );
			return $stt->fetchAll(PDO::FETCH_ASSOC);
		}

		public function				addImage ( $data )
		{
			$query = "INSERT INTO gallery (userid, src, `description`) VALUES (?, ?, ?)";
			$this->query($query, [ $data['id'], $data['src'], $data['description'] ]);
		}


		public function				getCountImages ()
		{
			$query = 'SELECT count(*) AS `count` FROM `gallery`';
			$stt = $this->query($query);
			$data = $stt->fetch(PDO::FETCH_ASSOC);
			return $data['count'];
		}

		

		public function				deleteImage ( $imgid, $userid )
		{
			$query = "DELETE FROM `gallery` WHERE id = ? AND userid = ?";
			$this->query( $query, [ $imgid, $userid ]);
		}

	}
?>