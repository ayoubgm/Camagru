<?php

	/**
	 * 	like controller class
	 */
	class likeController extends Controller {
		
		private $viewData;
		private $userMiddleware;
		private $galleryMiddleware;
		private $likesModel;

		public function 				__construct()
		{
			session_start();
			$this->viewData = array();
			$this->likesModel = $this->call_model('LikesModel');
			$this->userMiddleware = $this->call_middleware('UserMiddleware');
			$this->galleryMiddleware = $this->call_middleware('GalleryMiddleware');
		}
		
		public function 				add ()
		{
			if ( !$this->userMiddleware->isSignin( $_SESSION ) ) {
				$this->viewData = [ "success"=> "false", "msg" => "You need to login first !" ];
			} else if ( ( isset( $_POST["token"] ) && !empty( $_POST["token"] ) ) && $this->userMiddleware->validateUserToken( $_POST["token"] ) ) {
				try {
					if ( $this->galleryMiddleware->isImageExist( $_POST["id"] ) ) {
						if ( !$this->likesModel->isLikeExists( $_POST["id"], $_SESSION['userid'] ) ) {
							if ( !$this->likesModel->likeImage( $_POST["id"], $_SESSION['userid'] ) ) {
								$this->viewData = [ "success" => "false", "msg" => "Failed to like the image !" ];
							} else {
								$this->viewData = [ "success" => "true" ];
							}
						} else {
							if ( !$this->likesModel->unlikeImage( $_POST["id"], $_SESSION['userid'] ) ) {
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
				if ( !$this->galleryMiddleware->isImageExist( $_GET["id"] ) ) {
					$this->viewData = [ "success" => "false", "msg" => "The image is not found !" ];
				} else {
					$this->viewData = [
						"success" => "true",
						"users" => $this->likesModel->getUsersLikeImage( $_GET["id"] )
					];
				}
			} catch ( Exception $e ) {
				$this->viewData = [ "success" => "false", "msg" => "Something goes wrong while get users who liked the image !" ];
			}
			die( json_encode($this->viewData) );
		}
		

	}
?>