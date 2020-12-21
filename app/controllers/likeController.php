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
			$this->likesModel = self::call_model('LikesModel');
			$this->userMiddleware = self::call_middleware('UserMiddleware');
			$this->galleryMiddleware = self::call_middleware('GalleryMiddleware');
		}
		
		/* Like an image */
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
			var_dump( $_GET );
			// try {
			// 	if ( !$this->galleryMiddleware->isImageExist( $data[1] ) ) {
			// 		$this->viewData = [ "success" => "false", "msg" => "The image is not found !" ];
			// 	}
			// } catch ( Exception $e ) {
			// 	$this->viewData = [ "success" => "false", "msg" => "Something goes wrong while get users who liked the image !" ];
			// }
		}
		

	}
?>