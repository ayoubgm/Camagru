<?php

	/**
	 * 	Notification controller class
	 */

	class notificationController extends Controller {
		
		private $viewData;
		private $commentModel;

		public function 				__construct()
		{
			session_start();
			$this->viewData = array();
			$this->notificationModel = self::call_model("NotificationsModel");
		}

		// Get notfications of a user
		public function					user()
		{
			if ( !isset( $_SESSION['userid'] ) ) {
				$this->viewData = [ "success" => "false", "msg" => "You need to login first !" ];	
			} else {
				try {
					$this->viewData = [ "success" => "true", "data" => $this->notificationModel->getUserNotifications( $_SESSION ) ];
				} catch ( Exception $e ) {
					$this->viewData = [ "success" => "false", "msg" => "Something went wrong while load your notifications !" ];
				}
			}
			die( json_encode( $this->viewData ) );
		}
		
		// Read all notifactions of a user
		public function					read ( $data )
		{

		}
		
		// Delete all notifications of a user
		public function					delete ( $data )
		{

		}
		
	}
?>