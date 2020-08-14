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
		
		public function			index ( $data )
		{
			/* Load all images edited by users  */
			if ( isset( $_SESSION['userid'] ) && !empty( $_SESSION['userid'] ) ) {
				$userData = $this->userModel->findUserById( $_SESSION['userid'] );
				$userGallery = $this->userModel->findUserById( $_SESSION['userid'] );
			}
			$page = 1;
			$imagePerPage = 5;
			if ( isset( $data[0] ) && $data[0] === "page" && !empty( $data[1] ) && $data[1] > 0 ) { $page = intval($data[1]); }
			$depart = ( $page - 1 ) * $imagePerPage;
			$gallery = $this->galleryModel->getAllEditedImages( $depart, $imagePerPage );
			$totalImages = $this->galleryModel->getCountImages();

			$this->call_view(
				'gallery' . DIRECTORY_SEPARATOR . 'gallery',
				[
					'success' => "true",
					'data' => [
						'page' => $page,
						'totalImages' => $totalImages,
						'gallery' => $gallery,
						'userGallery' => $userGallery,
						'userData' => $userData
					]
				]
			)->render();
		}

		public function		notfound()
		{
			$this->call_view( 'notfound')->render();
		}

	}