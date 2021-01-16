<?php
	/**
	 * Notifications model class
	 */
	class NotificationsModel extends Model
	{

		public function				getUserNotifications ( $id )
		{
			$query = "
				SELECT * FROM `notifications`
				WHERE userid = ?
				ORDER BY createdat DESC
			";
			if ( $stt = $this->query( $query, [ $id ] ) ) {
				return $stt->fetchAll( PDO::FETCH_ASSOC );
			}
		}

		public function				getCountUnreadNotifications ( $id )
		{
			if ( $stt = $this->query( "SELECT count(*) AS `count` FROM `notifications` WHERE userid = ? AND seen = false ", [ $id ] ) ) {
				$data = $stt->fetch(PDO::FETCH_ASSOC);
				return $data['count'];
			}
		}

		public function				readANotifUser ( $userid, $notifid )
		{
			return $this->query( "UPDATE `notifications` SET seen = true WHERE userid = ? AND id = ?", [ $userid, $notifid ] );
		}

		public function				readUserNotifications ( $id )
		{
			return $this->query( "UPDATE `notifications` SET seen = true WHERE userid = ?", [ $id ] );
		}

		public function				deleteUserNotifications ( $id )
		{
			return $this->query( "DELETE FROM `notifications` WHERE userid = ?", [ $id ] );
		}

	}

?>