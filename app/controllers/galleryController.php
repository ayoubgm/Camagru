<?php

	/**
	 * 	gallery controller class
	 */
	class galleryController extends Controller {

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
		
		/* Load all images edited by users  */
		public function 				index ( $data )
		{
			$viewData = array();
			$viewData['data'] = [];
			$page = 1;
			$imagePerPage = 5;

			if ( isset( $_SESSION['userid'] ) && !empty( $_SESSION['userid'] ) ) {
				$viewData['data'][ 'userData'] = $this->usersModel->findUserById( $_SESSION['userid'] );
				$viewData['data'][ 'userGallery'] = $this->galleryModel->userGallery( $_SESSION['userid'] );
			}
			if ( isset( $data[0] ) && $data[0] === "page" && !empty( $data[1] ) && $data[1] > 0 ) {
				$page = intval($data[1]);
			}
			$depart = ( $page - 1 ) * $imagePerPage;
			$viewData['data']['gallery'] = $this->galleryModel->getAllEditedImages( $depart, $imagePerPage );
			foreach ($viewData['data']['gallery'] as $value) { $viewData['data']['usersLikedImgs'][$value['id']] = $this->likesModel->getUsersLikeImage( $value['id'] ); }
			foreach ($viewData['data']['gallery'] as $value) { $viewData['data']['comments'][$value['id']] = $this->commentsModel->getCommentOfImg( $value['id'] ); }
			$viewData['data']['totalImages'] = $this->galleryModel->getCountImages();
			$viewData['data']['page'] = $page;

			$this->call_view( 'gallery' . DIRECTORY_SEPARATOR . 'gallery', $viewData )->render();
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
						$viewData['data']['userGallery'] = $this->galleryModel->userGallery( $dataUser['id'] );
					}
				} catch ( Exception $e ) {
					$viewData['success'] = "false";
					$viewData['msg'] = "Something goes wrong !";
				}
			} else {
				$viewData['success'] = "true";
				$viewData['username'] = $_SESSION['username'];
				$viewData['data']['userGallery'] = $this->galleryModel->userGallery( $_SESSION['userid'] );
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
					'userGallery' => $this->galleryModel->userGallery( $_SESSION['userid'] )
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
								$viewData['data']['userGallery'] = $this->galleryModel->userGallery( $_SESSION['userid'] );
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