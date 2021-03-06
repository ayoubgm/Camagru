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
			try {
				if ( !$this->user_middleware->isSignin( $_SESSION ) ) {
					$this->viewData = [ "success" => "false", "msg" => "You need to login first !" ];	
				} else if ( $this->helper->isRequestGET( $_SERVER["REQUEST_METHOD"] ) ) {
					if (
						( $this->helper->validate_inputs([ 'token' => [ "REQUIRED" => true, "EMPTY" => false ] ], $_GET) ) &&
						( $_GET = $this->helper->filter_inputs( "GET", array( 'token' => FILTER_SANITIZE_STRING )) ) &&
						( $this->user_middleware->validateUserToken( $_GET["token"] ) )
					) {
						$notifs = $this->notifications_model->getUserNotifications( $_SESSION['userid'] );
						foreach ( $notifs as $key => $value ) { $notifs[ $key ] += [ "moments" => $this->helper->getMomentOfDate( $value["createdat"] ) ]; }
						$this->viewData = [ "success" => "true", "data" => $notifs ];
					} else {
						$this->viewData = [ "success" => "false", "msg" => "Couldn't load user notifications, try later !" ];
					}
				}
			} catch ( Exception $e ) {
				$this->viewData = [ "success" => "false", "msg" => "Something went wrong while load your notifications, try later !" ];
			}
			die( json_encode( $this->viewData ) );
		}

		public function					readnotifuser ()
		{
			try {
				if ( !$this->user_middleware->isSignin( $_SESSION ) ) {
					$this->viewData = [ "success" => "false", "msg" => "You need to login first !" ];	
				} else if ( $this->helper->isRequestPOST( $_SERVER["REQUEST_METHOD"] ) ) {
					if (
						(
							$this->helper->validate_inputs([
								'token' => [ "REQUIRED" => true, "EMPTY" => false ],
								'userid' => [ "REQUIRED" => true, "EMPTY" => false ],
								'notifid' => [ "REQUIRED" => true, "EMPTY" => false ]
							], $_POST)
						) &&
						(
							$_POST = $this->helper->filter_inputs( "POST", array(
								'token' => FILTER_SANITIZE_STRING,
								'userid' => FILTER_SANITIZE_NUMBER_INT,
								'notifid' => FILTER_SANITIZE_NUMBER_INT
							))
						) &&
						( $this->user_middleware->validateUserToken( $_POST["token"] ) )
					) {
						if ( !$this->notifications_model->readANotifUser( $_POST['userid'], $_POST["notifid"] ) ) {
							$this->viewData = [ "success" => "false", "msg" => "Failed to read your notification !" ];
						} else {
							$this->viewData = [ "success" => "true" ];
						}
					} else {
						$this->viewData = [ "success" => "false", "msg" => "Couldn't read the notification, try later !" ];
					}
				}
			} catch ( Exception $e ) {
				$this->viewData = [ "success" => "false", "msg" => "Something went wrong while read user notification, try later !" ];
			}
			die( json_encode( $this->viewData ) );
		}
		
		public function					readallnotifsuser ()
		{
			try {
				if ( !$this->user_middleware->isSignin( $_SESSION ) ) {
					$this->viewData = [ "success" => "false", "msg" => "You need to login first !" ];	
				} else if ( $this->helper->isRequestPOST( $_SERVER["REQUEST_METHOD"] ) ) {
					if (
						( $this->helper->validate_inputs( [ 'token' => [ "REQUIRED" => true, "EMPTY" => false ] ], $_POST ) ) &&
						( $_POST = $this->helper->filter_inputs( "POST", array( 'token' => FILTER_SANITIZE_STRING )) ) &&
						( $this->user_middleware->validateUserToken( $_POST["token"] ) )
					) {
						if ( !$this->notifications_model->readUserNotifications( $_SESSION['userid'] ) ) {
							$this->viewData = [ "success" => "false", "msg" => "Failed to read your notifications !" ];
						} else {
							$this->viewData = [ "success" => "true", "msg" => "Read all your notifications successfully !" ];
						}
					} else {
						$this->viewData = [ "success" => "false", "msg" => "Couldn't read the notifications, try later !" ];
					}
				}
			} catch ( Exception $e ) {
				$this->viewData = [ "success" => "false", "msg" => "Something went wrong while read your notifications, try later !" ];
			}
			die( json_encode( $this->viewData ) );
		}
		
		public function					deleteallnotifsuser ()
		{
			try {
				if ( !$this->user_middleware->isSignin( $_SESSION ) ) {
					$this->viewData = [ "success" => "false", "msg" => "You need to login first !" ];	
				} else if ( $this->helper->isRequestPOST( $_SERVER["REQUEST_METHOD"] ) ) {
					if (
						( $this->helper->validate_inputs( [ 'token' => [ "REQUIRED" => true, "EMPTY" => false ] ], $_POST ) ) &&
						( $_POST = $this->helper->filter_inputs( "POST", array( 'token' => FILTER_SANITIZE_STRING )) ) &&
						$this->user_middleware->validateUserToken( $_POST["token"] )
					) {
						if ( !$this->notifications_model->deleteUserNotifications( $_SESSION['userid'] ) ) {
							$this->viewData = [ "success" => "false", "msg" => "Failed to delete your notifications !" ];
						} else {
							$this->viewData = [ "success" => "true", "msg" => "Your notifications was deleted successfully !" ];
						}
					} else {
						$this->viewData = [ "success" => "false", "msg" => "Couldn't delete the notifications, try later !" ];
					}
				}
			} catch ( Exception $e ) {
				$this->viewData = [ "success" => "false", "msg" => "Something went wrong while delete your notifications, try later !" ];
			}
			die( json_encode( $this->viewData ) );
		}
		
	}
?>