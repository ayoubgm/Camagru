<?php

	/**
	 * 	Like controller class
	 */
	class likeController extends Controller
	{
		
		public function 				__construct()
		{
			parent::__construct();
			session_start();
		}
		
		public function 				add ()
		{
			try {
				if ( !$this->user_middleware->isSignin( $_SESSION ) ) {
					$this->viewData = [ "success"=> "false", "msg" => "You need to login first !" ];
				} else if ( $this->helper->isRequestPOST( $_SERVER["REQUEST_METHOD"] ) ) {
					if ( $_POST = $this->helper->filter_inputs( "POST", array(
							'token' => FILTER_SANITIZE_STRING,
							'id' => FILTER_SANITIZE_STRING
						))
					) {
						if ( ( isset( $_POST["token"] ) && !empty( $_POST["token"] ) ) && $this->user_middleware->validateUserToken( $_POST["token"] ) ) {
							if ( !$this->gallery_middleware->isImageExist( $_POST["id"] ) ) {
								$this->viewData = [ "success" => "false", "msg" => "The image is not found !" ];
							} else if ( $this->like_model->isLikeExists( $_POST["id"], $_SESSION['userid'] ) ) {
								if ( $this->like_model->unlikeImage( $_POST["id"], $_SESSION['userid'] ) ) {
									$this->viewData = [ "success" => "true" ];
								} else {
									$this->viewData = [ "success" => "false", "msg" => "Failed to unlike the image !" ];
								}
							} else {
								if ( $this->like_model->likeImage( $_POST["id"], $_SESSION['userid'] ) ) {
									$this->viewData = [ "success" => "true" ];
								} else {
									$this->viewData = [ "success" => "false", "msg" => "Failed to like the image !" ];
								}
							}
						}
					} else {
						$this->viewData = [ "success" => "false", "msg" => "Couldn't like or unlike the image, try later !" ];
					}
				}
			} catch ( Exception $e ) {
				$this->viewData = [ "success" => "false", "msg" => "Something goes wrong while like or un like the image, try later !" ];
			}
			die( json_encode($this->viewData, JSON_FORCE_OBJECT) );
		}

		public function					userswholikes ()
		{
			try {
				if ( $this->helper->isRequestGET( $_SERVER["REQUEST_METHOD"] ) ) {
					if ( $_GET = $this->helper->filter_inputs( "GET", array( 'id' => FILTER_SANITIZE_NUMBER_INT )) ) {
						if ( !$this->gallery_middleware->isImageExist( $_GET["id"] ) ) {
							$this->viewData = [ "success" => "false", "msg" => "The image is not found !" ];
						} else {
							$this->viewData = [ "success" => "true", "users" => $this->like_model->getUsersLikeImage( $_GET["id"] ) ];
						}
					} else {
						$this->viewData = [ "success" => "false", "msg" => "Couldn't load users who like the specified image, try later !" ];
					}
				}
			} catch ( Exception $e ) {
				$this->viewData = [ "success" => "false", "msg" => "Something goes wrong while get users who liked the image, try later !" ];
			}
			die( json_encode($this->viewData) );
		}

	}
?>