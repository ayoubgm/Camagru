<?php

	/**
	 * 	Like controller class
	 */
	class likeController extends Controller {
		
		public function 				__construct()
		{
			parent::__construct();
			session_start();
		}
		
		public function 				add ()
		{
			if ( !$this->user_middleware->isSignin( $_SESSION ) ) {
				$this->viewData = [ "success"=> "false", "msg" => "You need to login first !" ];
			} else if ( ( isset( $_POST["token"] ) && !empty( $_POST["token"] ) ) && $this->user_middleware->validateUserToken( $_POST["token"] ) ) {
				try {
					if ( $this->gallery_middleware->isImageExist( $_POST["id"] ) ) {
						if ( !$this->like_model->isLikeExists( $_POST["id"], $_SESSION['userid'] ) ) {
							if ( !$this->like_model->likeImage( $_POST["id"], $_SESSION['userid'] ) ) {
								$this->viewData = [ "success" => "false", "msg" => "Failed to like the image !" ];
							} else {
								$this->viewData = [ "success" => "true" ];
							}
						} else {
							if ( !$this->like_model->unlikeImage( $_POST["id"], $_SESSION['userid'] ) ) {
								$this->viewData = [ "success" => "false", "msg" => "Failed to unlike the image !" ];
							} else {
								$this->viewData = [ "success" => "true" ];
							}
						}
					} else {
						$this->viewData = [ "success" => "false", "msg" => "The image is not found !" ];
					}
				} catch ( Exception $e ) {
					$this->viewData = [ "success" => "false", "msg" => "Something goes wrong while like the image !" ];
				}
			}
			die( json_encode($this->viewData, JSON_FORCE_OBJECT) );
		}

		public function					userswholikes ()
		{
			try {
				if ( !$this->gallery_middleware->isImageExist( $_GET["id"] ) ) {
					$this->viewData = [ "success" => "false", "msg" => "The image is not found !" ];
				} else {
					$this->viewData = [
						"success" => "true",
						"users" => $this->like_model->getUsersLikeImage( $_GET["id"] )
					];
				}
			} catch ( Exception $e ) {
				$this->viewData = [ "success" => "false", "msg" => "Something goes wrong while get users who liked the image !" ];
			}
			die( json_encode($this->viewData) );
		}
		

	}
?>