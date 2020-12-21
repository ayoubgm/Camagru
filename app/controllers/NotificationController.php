<?php

	/**
	 * 	Notification controller class
	 */

	class notificationController extends Controller {
		
		private $viewData;
		private $userMiddleware;
		private $notificationModel;
		private $helper;

		public function 				__construct()
		{
			session_start();
			$this->viewData = array();
			$this->notificationModel = self::call_model("NotificationsModel");
			$this->userMiddleware = self::call_middleware('UserMiddleware');
			$this->helper = self::call_helper();
		}

		// Get notfications of a user
		public function					user()
		{
			if ( !isset( $_SESSION['userid'] ) ) {
				$this->viewData = [ "success" => "false", "msg" => "You need to login first !" ];	
			} else {
				try {
					$notifs = $this->notificationModel->getUserNotifications( $_SESSION['userid'] );
					foreach ( $notifs as $key => $value ) {
						$notifs[ $key ] += [ "moments" => $this->helper->getMomentOfDate( $value["createdat"] ) ]; 
					}
					$this->viewData = [ "success" => "true", "data" => $notifs ];
				} catch ( Exception $e ) {
					$this->viewData = [ "success" => "false", "msg" => "Something went wrong while load your notifications !" ];
				}
			}
			die( json_encode( $this->viewData ) );
		}
		
		// Read all notifactions of a user
		public function					readallnotifsuser ()
		{
			if ( !isset( $_SESSION['userid'] ) ) {
				$this->viewData = [ "success" => "false", "msg" => "You need to login first !" ];	
			} else {
				if ( ( isset( $_POST["token"] ) && !empty( $_POST["token"] ) ) && $this->userMiddleware->validateUserToken( $_POST["token"] ) ) {
					try {
						if ( !$this->notificationModel->readUserNotifications( $_SESSION['userid'] ) ) {
							$this->viewData = [ "success" => "false", "msg" => "Failed to read your notifications !" ];
						} else {
							$this->viewData = [ "success" => "true", "msg" => "Read all your notifications successfully !" ];
						}
					} catch ( Exception $e ) {
						$this->viewData = [ "success" => "false", "msg" => "Something went wrong while read your notifications !" ];
					}
				}
			}
			die( json_encode( $this->viewData ) );
		}
		
		// Delete all notifications of a user
		public function					deleteallnotifsuser ()
		{
			if ( !isset( $_SESSION['userid'] ) ) {
				$this->viewData = [ "success" => "false", "msg" => "You need to login first !" ];	
			} else {
				if ( ( isset( $_POST["token"] ) && !empty( $_POST["token"] ) ) && $this->userMiddleware->validateUserToken( $_POST["token"] ) ) {
					try {
						if ( !$this->notificationModel->deleteUserNotifications( $_SESSION['userid'] ) ) {
							$this->viewData = [ "success" => "false", "msg" => "Failed to delete your notifications !" ];
						} else {
							$this->viewData = [ "success" => "true", "msg" => "Your notifications was deleted successfully !" ];
						}
					} catch ( Exception $e ) {
						$this->viewData = [ "success" => "false", "msg" => "Something went wrong while delete your notifications !" ];
					}
				}
			}
			die( json_encode( $this->viewData ) );
		}
		
	}
?>