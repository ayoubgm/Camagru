<?php

	/**
	 * 	Gallery controller class
	 */
	class galleryController extends Controller
	{

		public function				__construct()
		{
			parent::__construct();
			session_start();
		}

		public function				index ( $data )
		{
			$page = 1;
			$depart = 0;
			$imagePerPage = 5;

			try {
				$this->viewData["data"] = array();

				if ( $this->helper->isRequestGET( $_SERVER["REQUEST_METHOD"] ) ) {
					if ( isset( $_SESSION["userid"] ) && !empty( $_SESSION["userid"] ) ) {
						$this->viewData[ "data" ] += [
							"userData" => $this->user_model->findUserById( $_SESSION["userid"] ),
							"countUnreadNotifs" => $this->notifications_model->getCountUnreadNotifications( $_SESSION['userid'] )
						];
					}
					if ( ( isset( $data[0] ) && $data[0] == "page" ) && ( isset( $data[1] ) && $data[1] > 0 ) ) {
						$page = intval( $data[1] );
						$depart = ( $page - 1 ) * $imagePerPage;	
					}
					$this->viewData["data"] += [ "gallery" => $this->gallery_model->getAllEditedImages( $depart, $imagePerPage ) ];
					foreach ( $this->viewData["data"]["gallery"] as $key => $value ) {
						$this->viewData["data"]["gallery"][ $key ] += [
							"moments" => $this->helper->getMomentOfDate( $value["createdat"] ),
							"usersWhoLike" => $this->like_model->getUsersLikeImage( $value["id"] ),
							"comments" => $this->comment_model->getCommentsOfImg( $value["id"] )
						];
					}
					$this->viewData["data"] += [
						"totalImages" => intval( $this->gallery_model->getCountImages() ),
						"page" => intval( $page )
					];
				}
			} catch ( Exception $e ) {
				$viewData["success"] = "false";
				$viewData["msg"] = "Something goes wrong while load images, try later !";
			}
			$this->call_view( 'gallery' . DIRECTORY_SEPARATOR . 'gallery', $this->viewData )->render();
		}
		
		public function				delete ()
		{
			try {
				if ( !$this->user_middleware->isSignin( $_SESSION ) ) {
					$this->viewData = [ "success" => "false", "msg" => "You need to login first !" ];
				} else if ( $this->helper->isRequestPOST( $_SERVER["REQUEST_METHOD"] ) ) {
					if (
						(
							$this->helper->validate_inputs([
								'token' => [ "REQUIRED" => true, "EMPTY" => false ],
								'id' => [ "REQUIRED" => true, "EMPTY" => false ]
							], $_POST )
						) &&
						(
							$_POST = $this->helper->filter_inputs( "POST", array(
								'token' => FILTER_SANITIZE_STRING,
								'id' => FILTER_SANITIZE_NUMBER_INT
							))
						) &&
						( $this->user_middleware->validateUserToken( $_POST["token"] ) )
					) {
						if ( ! $this->gallery_middleware->isImageOwnerExist( $_SESSION['userid'], $_POST['id'] ) ) {
							$this->viewData = [ "success" => "false", "msg" => "The image is not found !" ];
						} else if ( $this->gallery_model->deleteImage( $_POST['id'], $_SESSION['userid'] ) ) {
							$this->viewData = [ "success" => "true", "msg" => "Your image has been deleted successfully !" ];
						} else {
							$this->viewData = [ "success" => "true", "msg" => "Failed to delete your image !" ];
						}
					} else {
						$this->viewData = [ "success" => "false", "msg" => "Couldn't delete the image, try later !" ];
					}
				}
			} catch ( Exception $e ) {
				$this->viewData = [ "success" => "false", "msg" => "Something went wrong while delete the image, try later !" ];
			}
			die( json_encode( $this->viewData ) );
		}

	}