<?php

	/**
	 * 	Notification controller class
	 */
	class notificationController extends Controller
	{

		public function 				__construct()
		{
			parent::__construct();
			session_start();
		}

		public function					user()
		{
			if ( !isset( $_SESSION['userid'] ) ) {
				$this->viewData = [
					"success" => "false",
					"msg" => "You need to login first !"
				];	
			} else {
				try {
					$notifs = $this->notifications_model->getUserNotifications( $_SESSION['userid'] );
					foreach ( $notifs as $key => $value ) {
						$notifs[ $key ] += [ "moments" => $this->helper->getMomentOfDate( $value["createdat"] ) ]; 
					}
					$this->viewData = [
						"success" => "true",
						"data" => $notifs
					];
				} catch ( Exception $e ) {
					$this->viewData = [
						"success" => "false",
						"msg" => "Something went wrong while load your notifications !"
					];
				}
			}
			die( json_encode( $this->viewData ) );
		}

		public function					readnotifuser ()
		{
			if ( !isset( $_SESSION['userid'] ) ) {
				$this->viewData = [
					"success" => "false",
					"msg" => "You need to login first !"
				];	
			} else if ( ( isset( $_POST["token"] ) && !empty( $_POST["token"] ) ) && $this->user_middleware->validateUserToken( $_POST["token"] ) ) {
				try {
					if ( !$this->notifications_model->readANotifUser( $_POST['userid'], $_POST["notifid"] ) ) {
						$this->viewData = [
							"success" => "false",
							"msg" => "Failed to read your notification !"
						];
					} else {
						$this->viewData = [ "success" => "true" ];
					}
				} catch ( Exception $e ) {
					$this->viewData = [
						"success" => "false",
						"msg" => "Something went wrong while read your notifications !"
					];
				}
			}
			die( json_encode( $this->viewData ) );
		}
		
		public function					readallnotifsuser ()
		{
			if ( !isset( $_SESSION['userid'] ) ) {
				$this->viewData = [
					"success" => "false",
					"msg" => "You need to login first !"
				];	
			} else {
				if ( ( isset( $_POST["token"] ) && !empty( $_POST["token"] ) ) && $this->user_middleware->validateUserToken( $_POST["token"] ) ) {
					try {
						if ( !$this->notifications_model->readUserNotifications( $_SESSION['userid'] ) ) {
							$this->viewData = [
								"success" => "false",
								"msg" => "Failed to read your notifications !"
							];
						} else {
							$this->viewData = [
								"success" => "true",
								"msg" => "Read all your notifications successfully !"
							];
						}
					} catch ( Exception $e ) {
						$this->viewData = [
							"success" => "false",
							"msg" => "Something went wrong while read your notifications !"
						];
					}
				}
			}
			die( json_encode( $this->viewData ) );
		}
		
		public function					deleteallnotifsuser ()
		{
			if ( !isset( $_SESSION['userid'] ) ) {
				$this->viewData = [
					"success" => "false",
					"msg" => "You need to login first !"
				];	
			} else {
				if ( ( isset( $_POST["token"] ) && !empty( $_POST["token"] ) ) && $this->user_middleware->validateUserToken( $_POST["token"] ) ) {
					try {
						if ( !$this->notifications_model->deleteUserNotifications( $_SESSION['userid'] ) ) {
							$this->viewData = [
								"success" => "false",
								"msg" => "Failed to delete your notifications !"
							];
						} else {
							$this->viewData = [
								"success" => "true",
								"msg" => "Your notifications was deleted successfully !"
							];
						}
					} catch ( Exception $e ) {
						$this->viewData = [
							"success" => "false",
							"msg" => "Something went wrong while delete your notifications !"
						];
					}
				}
			}
			die( json_encode( $this->viewData ) );
		}
		
	}
?>