<?php

	class galleryController extends Controller {

		public function         __construct()
		{
			session_start();
		}
		
		// public function			index ( $data )
		// {
		// 	/* Load all images edited by users  */
		// 	$userid = ( isset( $_SESSION['userid'] ) ) ? $_SESSION['userid'] : null;
		// 	$userData = ( $userid == null ) ? [] : $this->call_model('UserModel')->findUserById( $userid );
		// 	$page = 1;
		// 	$imagePerPage = 5;
		// 	if ( isset( $data[0] ) && $data[0] === "page" && !empty( $data[1] ) && $data[1] > 0 ) {
		// 		$page = intval($data[1]);
		// 	}
		// 	$depart = ( $page - 1 ) * $imagePerPage;
		// 	$gallery = $this->call_model('GalleryModel')->getAllEditedImages( $depart, $imagePerPage );
		// 	$totalImages = $this->call_model('GalleryModel')->getCountImages();

		// 	$this->call_view(
		// 		'gallery' . DIRECTORY_SEPARATOR . 'gallery',
		// 		[
		// 			'success' => "true",
		// 			'data' => [
		// 				'page' => $page,
		// 				'totalImages' => $totalImages,
		// 				'userData' => $userData,	
		// 				'gallery' => $gallery	
		// 			]
		// 		]
		// 	)->render();
		// }

		public function		notfound()
		{
			echo "404";
		}

	}