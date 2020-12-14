 				<?php

	/**
	 * 	like controller class
	 */
	class likeController extends Controller {
		
		private $viewData;
		private $galleryMiddleware;
		private $usersModel;
		private $galleryModel;
		private $likesModel;
		private $commentsModel;

		public function 				__construct()
		{
			session_start();
			$this->viewData = array();
			$this->usersModel = self::call_model('UsersModel');
			$this->galleryModel = self::call_model('GalleryModel');
			$this->likesModel = self::call_model('LikesModel');
			$this->commentsModel = self::call_model('CommentsModel');
			$this->galleryMiddleware = self::call_middleware('GalleryMiddleware');
		}
		

		/* Like an image */
		public function 				add ( $data ) {
			if ( isset( $_SESSION['userid'] ) ) {
				if ( isset( $data ) && ( isset( $data[0] ) && $data[0] == "id" ) && ( isset( $data[1] ) && !empty( $data[1] ) ) ) {
					try {
						if ( $this->galleryMiddleware->isImageExist( $data[1] ) ) {
							if ( !$this->likesModel->isLikeExists( $data[1], $_SESSION['userid'] ) ) {
								if ( !$this->likesModel->likeImage( $data[1], $_SESSION['userid'] ) ) {
									$this->viewData = [ "success" => "false", "msg" => "Failed to like the image !" ];
								} else {
									$this->viewData = [ "success" => "true" ];
								}
							} else {
								if ( !$this->likesModel->unlikeImage( $data[1], $_SESSION['userid'] ) ) {
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
				} else {
					$this->viewData = [ "success"=> "false", "msg" => "Something went wrong while validate image !" ];
				}	
			} else {
				$this->viewData = [ "success"=> "false", "msg" => "You need to login first !" ];
			}
			die( json_encode($this->viewData, JSON_FORCE_OBJECT) );
		}

	}
?>