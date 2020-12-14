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

			if ( isset( $_SESSION["userid"] ) && !empty( $_SESSION["userid"] ) ) {
				$this->viewData = [
					"userData" => $this->usersModel->findUserById( $_SESSION["userid"] ),
					"userGallery" => $this->galleryModel->userGallery( $_SESSION["username"] )
				];
			}
			if ( isset( $data[0] ) && $data[0] === "page" && !empty( $data[1] ) && $data[1] > 0 ) {
				$page = intval( $data[1] );
			}
			$depart = ( $page - 1 ) * $imagePerPage;
			$this->viewData += [ "gallery" => $this->galleryModel->getAllEditedImages( $depart, $imagePerPage ) ];
			foreach ( $this->viewData["gallery"] as $key => $value ) {
				$this->viewData["gallery"][ $key ] += [ "momemnts" => self::getMomentOfDate( $value["createdat"] ) ];
				$this->viewData["gallery"][ $key ] += [ "usersWhoLike" => $this->likesModel->getUsersLikeImage( $value["id"] ) ];
				$this->viewData["gallery"][ $key ] += [ "comments" => $this->commentsModel->getCommentOfImg( $value["id"] ) ];
			}
			$this->viewData += [
				"totalImages" => $this->galleryModel->getCountImages(),
				"page" => $page
			];
			var_dump( $this->viewData );
			// $this->call_view( 'gallery' . DIRECTORY_SEPARATOR . 'gallery', $viewData )->render();
		}

		/* Load all images edited by a user  */
		public function 				user ( $data )
		{
			$viewData = array();
			$viewData['data'][ 'gallery'] = $this->galleryModel->getAllEditedImages();

			if ( isset( $_SESSION['userid'] ) && !empty( $_SESSION['userid'] ) ) {
				$viewData['success'] = "true";
				$viewData['data']['userData'] = $this->usersModel->findUserById( $_SESSION['userid'] );
			}
			if ( isset( $data[0] ) && $data[0] === "username" && !empty( $data[1] ) ) {
				try {
					$dataUser = $this->usersModel->findUserByUsername( strtolower( $data[1] ) );

					if ( !isset( $dataUser ) || empty( $dataUser ) ) {
						$viewData['success'] = "false";
						$viewData['msg'] = "The user is not found !";
					} else {
						$viewData['success'] = "true";
						$viewData['username'] = $data[1];
						$viewData['data']['userGallery'] = $this->galleryModel->userGallery( $dataUser['username'] );
					}
				} catch ( Exception $e ) {
					$viewData['success'] = "false";
					$viewData['msg'] = "Something goes wrong !";
				}
			} else {
				$viewData['success'] = "true";
				$viewData['username'] = $_SESSION['username'];
				$viewData['data']['userGallery'] = $this->galleryModel->userGallery( $_SESSION['username'] );
			}
			$this->call_view( 'gallery' . DIRECTORY_SEPARATOR . 'user', $viewData)->render();
		}
		
		/* Delete an image by author of image */
		public function 				delete ( $data )
		{
			if ( isset( $_SESSION['userid'] ) && !empty( $_SESSION['userid'] ) ) {
				$viewData = array();
				$viewData['data'] = [
					'userData' => $this->usersModel->findUserById( $_SESSION['userid'] ),
					'gallery' => $this->galleryModel->getAllEditedImages(),
					'userGallery' => $this->galleryModel->userGallery( $_SESSION['username'] )
				];

				if ( isset( $data[0] ) && $data[0] === "id" && !empty( $data[1] ) ) {
					if ( !( $imgdata = $this->galleryMiddleware->isImageOwnerExist( $_SESSION['userid'], $data[1] ) ) ) {
						$viewData['success'] = "false";
						$viewData['msg'] = "No image exists with this id in your gallery !";

					} else {
						try {
							// DELETE IMAGE UPLOADED ON THE SERVER
							$pathimg = str_replace('\\\\\\', '\\', str_replace('/', '\\', str_replace( PUBLIC_FOLDER, PUBLIC_DIR . '/', $imgdata['src'] ) ) );;
							// unset( $pathimg );
							if ( $this->galleryModel->deleteImage( $data[1], $_SESSION['userid'] ) ) {
								$viewData['success'] = "true";
								$viewData['msg'] = "Your image has been deleted successfully !";
								$viewData['data']['userGallery'] = $this->galleryModel->userGallery( $_SESSION['username'] );
								$viewData['data']['gallery'] = $this->galleryModel->getAllEditedImages();
							} else {
								$viewData['success'] = "false";
								$viewData['msg'] = "Failed to delete your image !";
							}
						} catch ( Exception $e ) {
							$viewData['success'] = "false";
							$viewData['msg'] = "Something goes wrong while delete your image !";
						}
					}
				} else {
					$viewData['success'] = "false";
					$viewData['msg'] = "No id found !";
				}
				$this->call_view( 'gallery' . DIRECTORY_SEPARATOR . 'delete', $viewData)->render();
			} else {
				header("Location: /camagru/signin");
			}
		}

	}