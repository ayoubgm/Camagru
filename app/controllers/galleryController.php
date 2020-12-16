<?php

	/**
	 * 	gallery controller class
	 */
	class galleryController extends Controller {

		private $viewData;
		private $galleryMiddleware;
		private $usersModel;
		private $galleryModel;
		private $likesModel;
		private $commentsModel;
		private $notificationsModel;

		public function 				__construct()
		{
			session_start();
			$this->viewData = array();
			$this->galleryMiddleware = self::call_middleware('GalleryMiddleware');
			$this->usersModel = self::call_model('UsersModel');
			$this->galleryModel = self::call_model('GalleryModel');
			$this->likesModel = self::call_model('LikesModel');
			$this->commentsModel = self::call_model('CommentsModel');
			$this->notificationsModel = self::call_model('NotificationsModel');
		}
		
		static public function			getMomentOfDate( $date )
		{
			$gmtTimezone = new DateTimeZone('GMT+1');
			$creatDate = new DateTime( $date, $gmtTimezone );
			$currDate = new DateTime("now", $gmtTimezone);
			$interval = date_diff( $currDate, $creatDate );
			$string = "";

			if ( $interval->format('%Y') > 0 ) {
				$string = $interval->format('%Y').", ".$interval->format('%d')." ".strtolower( $interval->format('%F') )." at ".$interval->format('%H:%m');
			} else if ( $interval->format('%m') > 0 && $interval->format('%m') > 7 ) {
				$string = $interval->format('%d')." ".strtolower( $interval->format('%F') )." at ".$interval->format('%H:%m');
			} else if ( $interval->format('%d') >= 1 ) {
				$string = $interval->format('%d')." d";
			} else if ( $interval->format('%H') >= 1 && $interval->format('%H') <= 24 ) {
				$string = $interval->format('%h')." h";
			} else if ( $interval->format('%i') >= 1 && $interval->format('%i') <= 60 ) {
				$string = $interval->format('%i')." min";
			} else if ( $interval->format('%s') >= 1 && $interval->format('%s') <= 60 ) {
				$string = $interval->format('%s')." sec";
			}
			return $string;
		}

		/* Load all images edited by users  */
		public function 				index ( $data )
		{
			$page = 1;
			$imagePerPage = 5;

			try {
				$this->viewData["data"] = array();

				if ( isset( $_SESSION["userid"] ) && !empty( $_SESSION["userid"] ) ) {
					$this->viewData[ "data" ] += [
						"userData" => $this->usersModel->findUserById( $_SESSION["userid"] ),
						"userGallery" => $this->galleryModel->userGallery( $_SESSION["username"] ),
						"countUnreadNotifs" => $this->notificationsModel->getCountUnreadNotifications( $_SESSION['userid'] )
					];
				}
				if ( isset( $data[0] ) && $data[0] === "page" && !empty( $data[1] ) && $data[1] > 0 ) {
					$page = intval( $data[1] );
				}
				$depart = ( $page - 1 ) * $imagePerPage;
				$this->viewData["data"] += [ "gallery" => $this->galleryModel->getAllEditedImages( $depart, $imagePerPage ) ];
				foreach ( $this->viewData["data"]["gallery"] as $key => $value ) {
					$this->viewData["data"]["gallery"][ $key ] += [ "moments" => self::getMomentOfDate( $value["createdat"] ) ];
					$this->viewData["data"]["gallery"][ $key ] += [ "usersWhoLike" => $this->likesModel->getUsersLikeImage( $value["id"] ) ];
					$this->viewData["data"]["gallery"][ $key ] += [ "comments" => $this->commentsModel->getCommentsOfImg( $value["id"] ) ];
				}
				$this->viewData["data"] += [
					"totalImages" => $this->galleryModel->getCountImages(),
					"page" => $page
				];
			} catch ( Exception $e ) {
				$viewData['success'] = "false";
				$viewData['msg'] = "Something goes wrong !";
			}
			$this->call_view( 'gallery' . DIRECTORY_SEPARATOR . 'gallery', $this->viewData )->render();
		}
		
		/* Delete an image by author of image */
		public function 				delete ( $data )
		{
			try {
				if ( !isset( $_SESSION['userid'] ) ) {
					$this->viewData = [ "success" => "false", "msg" => "You need to login first !" ];	
				} else if ( !isset( $data ) || ( !isset( $data[0] ) || $data[0] != "id" ) || ( !isset( $data[1] ) || empty( $data[1] ) ) ) {
					$this->viewData = [ "success" => "false", "msg" => "Something went wrong while validate the comment !" ];	
				} else if ( ! $this->galleryMiddleware->isImageOwnerExist( $_SESSION['userid'], $data[1] ) ) {
					$this->viewData = [ "success" => "false", "msg" => "The image is not found !" ];	
				} else if ( $this->galleryModel->deleteImage( $data[1], $_SESSION['userid'] ) ) {
					$this->viewData = [ "success" => "true", "msg" => "Your image has been deleted successfully !" ];
				} else {
					$this->viewData = [ "success" => "true", "msg" => "Failed to delete your image !" ];
				}
			} catch ( Exception $e ) {
				$this->viewData = [ "success" => "false", "msg" => "Something went wrong while delete the image  !" ];
			}
			die( json_encode( $this->viewData ) );
		}

	}