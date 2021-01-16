<?php

	/**
	 *  Likes model class
	 */
	class LikesModel extends Model
	{

		public function				likeImage ( $imgid, $userid )
		{
			return $this->query( "INSERT INTO `likes` (userid, imgid) values (?, ?);", [ $userid, $imgid ] );
		}

		public function				unlikeImage ( $imgid, $userid )
		{
			return $this->query( "DELETE FROM likes WHERE imgid = ? AND userid = ?;", [ $imgid, $userid ] );
		}

		public function				getCountLikes ( $imgid )
		{
			if ( $stt = $this->query( "SELECT count(*) AS `count` FROM `likes` WHERE imgid = ?;", [ $imgid ]) ) {
				$data = $stt->fetch(PDO::FETCH_ASSOC);
				return $data["count"];
			}
		}

	 	public function				getUsersLikeImage ( $imgid )
		{
			$query = "
				SELECT u.id, u.username, u.firstname, u.lastname
				FROM `likes` l inner join `users` u
				ON l.userid = u.id
				WHERE l.imgid = ?
				ORDER BY l.createdat DESC
			";
			if ( $stt = $this->query( $query, [ $imgid ] ) ) {
				return $stt->fetchAll(PDO::FETCH_ASSOC);
			}
		}

		public function				isLikeExists ( $imgid, $userid )
		{
			if ( $stt = $this->query( "SELECT count(*) AS `count` FROM `likes` WHERE imgid = ? AND userid = ?", [ $imgid, $userid ] ) ) {
				$data = $stt->fetch(PDO::FETCH_ASSOC);
				return ( $data['count'] == 0 ) ? false : true;
			}
		}

	}

?>