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
			if ( $stt = $this->query( $query ) ) {
				return $stt->fetchAll( PDO::FETCH_ASSOC );
			}
		}

		public function				getCountImages ()
		{
			if ( $stt = $this->query("SELECT count(*) AS `count` FROM `gallery`") ) {
				$data = $stt->fetch(PDO::FETCH_ASSOC);
				return $data['count'];
			}
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
			if ( $stt = $this->query( $query, [ strtolower( $username ) ] ) ) {
				return $stt->fetchAll(PDO::FETCH_ASSOC);
			}
		}

		public function				addImage ( $data )
		{
			$this->query(
				"INSERT INTO gallery (userid, src, `description`) VALUES (?, ?, ?)",
				[ $data['id'], $data['src'], $data['description'] ]
			);
		}

		public function				deleteImage ( $imgid, $userid )
		{
			$this->query( "DELETE FROM `gallery` WHERE id = ? AND userid = ?", [ $imgid, $userid ] );
		}

	}
?>