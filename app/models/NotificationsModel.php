<?php

	/**
	 * Notifications model class
	 */

	class           NotificationsModel extends DB
	{

		// Get all notifications of a user
		public function				getUserNotifications ( $id )
		{
			$query = '
				SELECT * FROM `notifications`
				WHERE userid = ?
				ORDER BY createdat DESC
			';
			$stt = $this->connect()->prepare( $query );
			$stt->execute([ $id ]);
			return $stt->fetchAll( PDO::FETCH_ASSOC );
		}

		// Get all count unread notifications
		public function				getCountUnreadNotifications ( $id )
		{
			$query = 'SELECT count(*) AS `count` FROM `notifications` WHERE userid = ? AND seen = false ';
			$stt = $this->connect()->prepare( $query );
			$stt->execute([ $id ]);
			$data = $stt->fetch(PDO::FETCH_ASSOC);
			return $data['count'];
		}

		// Read all user notications
		public function				readUserNotifications ( $id )
		{
			$query = 'UPDATE `notifications` SET seen = true WHERE userid = ?';
			$stt = $this->connect()->prepare( $query );
			return $stt->execute([ $id ]);
		}

		// Delete all user notifications
		public function				deleteUserNotifications ( $id )
		{
			$query = 'DELETE FROM `notifications` WHERE userid = ?';
			$stt = $this->connect()->prepare( $query );
			return $stt->execute([ $id ]);
		}

	}

?>