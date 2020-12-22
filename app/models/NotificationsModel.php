<?php

	/**
	 * Notifications model class
	 */

	class           NotificationsModel extends DB
	{

		public function				getUserNotifications ( $id )
		{
			$query = '
				SELECT * FROM `notifications`
				WHERE userid = ?
				ORDER BY createdat DESC
			';
			$stt = $this->query( $query, [ $id ] );
			return $stt->fetchAll( PDO::FETCH_ASSOC );
		}

		public function				getCountUnreadNotifications ( $id )
		{
			$query = 'SELECT count(*) AS `count` FROM `notifications` WHERE userid = ? AND seen = false ';
			$stt = $this->query( $query, [ $id ] );
			$data = $stt->fetch(PDO::FETCH_ASSOC);
			return $data['count'];
		}

		public function				readANotifUser ( $userid, $notifid )
		{
			$query = 'UPDATE `notifications` SET seen = true WHERE userid = ? AND id = ?';
			$this->query( $query, [ $userid, $notifid ] );
		}

		public function				readUserNotifications ( $id )
		{
			$query = 'UPDATE `notifications` SET seen = true WHERE userid = ?';
			$this->query( $query, [ $id ] );
		}

		public function				deleteUserNotifications ( $id )
		{
			$query = 'DELETE FROM `notifications` WHERE userid = ?';
			$this->query( $query, [ $id ] );
		}

	}

?>