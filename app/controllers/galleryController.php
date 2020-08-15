<?php

	class galleryController extends Controller {

		private $userModel;
		private $galleryModel;

		public function         __construct()
		{
			session_start();
			$this->userModel = self::call_model('UserModel');
			$this->galleryModel = self::call_model('GalleryModel');
		}
		
		/* Load all images edited by users  */
		public function			index ( $data )
		{
			$viewData = array();
			$viewData['data'] = [];

			if ( isset( $_SESSION['userid'] ) && !empty( $_SESSION['userid'] ) ) {
				$viewData['data'][ 'userData'] = $this->userModel->findUserById( $_SESSION['userid'] );
				$viewData['data'][ 'userGallery'] = $this->galleryModel->userGallery( $_SESSION['userid'] );
			}
			$page = 1;
			$imagePerPage = 5;
			if ( isset( $data[0] ) && $data[0] === "page" && !empty( $data[1] ) && $data[1] > 0 ) { $page = intval($data[1]); }
			$depart = ( $page - 1 ) * $imagePerPage;
			$viewData['data']['gallery'] = $this->galleryModel->getAllEditedImages( $depart, $imagePerPage );
			$viewData['data']['totalImages'] = $this->galleryModel->getCountImages();
			$viewData['data']['page'] = $page ;

			$this->call_view( 'gallery' . DIRECTORY_SEPARATOR . 'gallery', $viewData )->render();
		}

		/* Load all images edited by a user with username  */
		public function			user ( $data )
		{
			$viewData = array();
			$viewData['data'] = [ 'gallery' => $this->galleryModel->getAllEditedImages() ];

			if ( isset( $_SESSION['userid'] ) && !empty( $_SESSION['userid'] ) ) {
				$viewData ['success'] = "true";
				$viewData['data']['userData'] = $this->userModel->findUserById( $_SESSION['userid'] );
			}
			try {
				if ( isset( $data[0] ) && $data[0] === "username" && !empty( $data[1] ) ) {
					$dataUser = $this->userModel->findUserByUsername( strtolower( $data[1] ) );

					if ( !isset( $dataUser ) || empty( $dataUser ) ) {
						$viewData['success'] = "false";
						$viewData['msg'] = "The user is not found !";
					} else {
						$viewData ['success'] = "true";
						$viewData ['data']['userGallery'] = $this->galleryModel->userGallery( $dataUser['id'] );
					}
				} else {
					$viewData['success'] = "false";
					$viewData['msg'] = "No username found !";
				}
			} catch ( Exception $e ) {	
				$viewData['success'] = "false";
				$viewData['msg'] = "Something goes wrong !";
			}
			$this->call_view( 'gallery' . DIRECTORY_SEPARATOR . 'user', $viewData)->render();
		}

		public function		notfound()
		{
			$this->call_view( 'notfound')->render();
		}

	}