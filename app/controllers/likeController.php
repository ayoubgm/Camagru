 				<?php

	/**
	 * 	like controller class
	 */
	class likeController extends Controller {
		
		private $galleryMiddleware;
		private $usersModel;
		private $galleryModel;
		private $likesModel;
		private $commentsModel;

		public function 				__construct()
		{
			session_start();
			$this->usersModel = self::call_model('UsersModel');
			$this->galleryModel = self::call_model('GalleryModel');
			$this->likesModel = self::call_model('LikesModel');
			$this->commentsModel = self::call_model('CommentsModel');
			$this->galleryMiddleware = self::call_middleware('GalleryMiddleware');
		}
		

		/* Like an image */
		public function 				add ( $data ) {
			if ( isset( $_SESSION['userid'] ) && !empty( $_SESSION['userid'] ) ) {
				$viewData = array();
				$page = 1;
				$imagePerPage = 5;
				if ( isset( $data[0] ) && $data[0] === "page" && !empty( $data[1] ) && $data[1] > 0 ) { $page = intval($data[1]); }
				$depart = ( $page - 1 ) * $imagePerPage;
				$viewData['data'] = [
					'userData' => $this->usersModel->findUserById( $_SESSION['userid'] ),
					'gallery' => $this->galleryModel->getAllEditedImages( $depart, $imagePerPage ),
					'userGallery' => $this->galleryModel->userGallery( $_SESSION['userid'] ),
					'totalImages' => $this->galleryModel->getCountImages(),
					'page' => $page
				];
				foreach ($viewData['data']['gallery'] as $value) { $viewData['data']['usersLikedImgs'][$value['id']] = $this->likesModel->getUsersLikeImage( $value['id'] ); }

				if ( isset( $data[0] ) && $data[0] === "id" && !empty( $data[1] ) ) {
					if ( !$this->galleryMiddleware->isImageExist( $data[1] ) ) {
						$viewData['success'] = "false";
						$viewData['msg'] = "The image is not found !";
					} else {
						try {
							if ( !$this->likesModel->likeImage( $data[1], $_SESSION['userid'] ) ) {
								$viewData['success'] = "false";
								$viewData['msg'] = "Failed to submit your like !";
							} else {
								header("Location: /camagru_git/gallery/index");
							}
						} catch ( Exception $e ) {
							$viewData['success'] = "false";
							$viewData['msg'] = "Something goes wrong while like an image !";
						}
					}
				} else {
					$viewData['success'] = "false";
					$viewData['msg'] = "No id found !";
				}
				$this->call_view( 'gallery' . DIRECTORY_SEPARATOR . 'gallery', $viewData)->render();
			} else {
				header("Location: /camagru_git/signin");
			}
		}

		/* Unlike an image */
		public function 				remove ( $data ) {
			if ( isset( $_SESSION['userid'] ) && !empty( $_SESSION['userid'] ) ) {
				$viewData = array();
				$page = 1;
				$imagePerPage = 5;
				if ( isset( $data[0] ) && $data[0] === "page" && !empty( $data[1] ) && $data[1] > 0 ) { $page = intval($data[1]); }
				$depart = ( $page - 1 ) * $imagePerPage;
				$viewData['data'] = [
					'userData' => $this->usersModel->findUserById( $_SESSION['userid'] ),
					'gallery' => $this->galleryModel->getAllEditedImages( $depart, $imagePerPage ),
					'userGallery' => $this->galleryModel->userGallery( $_SESSION['userid'] ),
					'totalImages' => $this->galleryModel->getCountImages(),
					'page' => $page
				];
				foreach ($viewData['data']['gallery'] as $value) { $viewData['data']['usersLikedImgs'][$value['id']] = $this->likesModel->getUsersLikeImage( $value['id'] ); }

				if ( isset( $data[0] ) && $data[0] === "id" && !empty( $data[1] ) ) {
					if ( !$this->galleryMiddleware->isImageExist( $data[1] ) ) {
						$viewData['success'] = "false";
						$viewData['msg'] = "The image is not found !";
					} else {
						try {
							if ( !$this->likesModel->unlikeImage( $data[1], $_SESSION['userid'] ) ) {
								$viewData['success'] = "false";
								$viewData['msg'] = "Failed to submit your unlike !";
							} else {
								header("Location: /camagru_git/gallery/index");
							}
						} catch ( Exception $e ) {
							$viewData['success'] = "false";
							$viewData['msg'] = "Something goes wrong while like an image !";
						}
					}
				} else {
					$viewData['success'] = "false";
					$viewData['msg'] = "No id found !";
				}
				$this->call_view( 'gallery' . DIRECTORY_SEPARATOR . 'gallery', $viewData)->render();
			} else {
				header("Location: /camagru_git/signin");
			}
		}
		
		public function 				notfound()
		{
			$this->call_view( 'notfound')->render();
		}

	}
?>