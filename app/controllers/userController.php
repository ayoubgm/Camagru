<?php
	class userController extends Controller {

		public function		__construct ()
		{
			$session = session_start();
		}

		public function		profile ()
		{
			if ( isset( $_SESSION['userid'] ) ) {
				$userData = $this->call_model('UserModel')->findUserById( $_SESSION['userid'] );
				$gallery = $this->call_model('GalleryModel')->getAllEditedImages();

				$this->call_view( 'user' . DIRECTORY_SEPARATOR .'profile', ['success' => "true", 'data' => [ 'userData' => $userData, 'gallery' => $gallery ] ] );
				$this->view->render();
			} else {
				header("Location: /camagru_git/home");
			}
		}
		
		public function		edit ()
		{
			if ( isset( $_SESSION['userid'] ) ) {
				$userModel = $this->call_model('UserModel');
				$galleryModel = $this->call_model('GalleryModel');
				$userData = $userModel->findUserById( $_SESSION['userid'] );
				$gallery = $galleryModel->getAllEditedImages();
				switch( $_SERVER['REQUEST_METHOD'] ) {
					case 'GET':
						$this->call_view(
							'user' . DIRECTORY_SEPARATOR .'edit_infos',
							['success' => "true", 'data' => [ 'userData' => $userData, 'gallery' => $gallery ] ]
						);
					break;
					case 'POST':
						if ( isset($_POST['btn-edit']) ) {
							$userID = $userData['id'];
							unset( $_POST['btn-edit'] ); unset( $userData['id'] ); unset( $userData['createdat'] );
							$editedData = array_replace_recursive( $userData, $_POST );
							if ( ($error = $this->call_middleware('UserMiddleware')->edit( $userID, $editedData )) != null){
								$this->call_view(
									'user' . DIRECTORY_SEPARATOR .'edit_infos',
									[
										'success' => "false",
										'msg' => $error,
										'data' => [ 'userData' => $editedData, 'gallery' => $gallery ]
									]
								);
							} else {
								try {
									if ( $userModel->edit( $userID, $editedData ) ) {
										$newData = $userModel->findUserById( $_SESSION['userid'] );
										$this->call_view(
											'user' . DIRECTORY_SEPARATOR .'edit_infos',
											[
												'success' => "true",
												'msg' => "Your informations has been edited successfully !",
												'data' => [ 'userData' => $newData, 'gallery' => $gallery ]
											]
										);
									} else {
										$this->call_view(
											'user' . DIRECTORY_SEPARATOR .'edit_infos',
											[
												'success' => "false",
												'msg' => "Failed to update your informations !",
												'data' => [ 'userData' => $userData, 'gallery' => $gallery ]
											]
										);
									}
								} catch( Exception $e ) {
									$this->call_view(
										'user' . DIRECTORY_SEPARATOR .'edit_infos',
										[
											'success' => "false",
											'msg' => "Something goes wrong while edit informations, try later !",
											'data' => [ 'userData' => $userData, 'gallery' => $gallery ]
										]
									);
								}
							}
						}
					break;
				}
			} else {
				header("Location: /camagru_git/home");
			}
			$this->view->render();
		}

		public function		settings ()
		{
			if ( isset( $_SESSION['userid'] ) ) {
				$userData = $this->call_model('UserModel')->findUserById( $_SESSION['userid'] );
				$gallery = $this->call_model('GalleryModel')->getAllEditedImages();

				$this->call_view( 'user' . DIRECTORY_SEPARATOR .'settings', ['success' => "true", 'data' => [ 'userData' => $userData, 'gallery' => $gallery ] ] );
				$this->view->render();
			} else {
				header("Location: /camagru_git/home");
			}
		}

		public function		change_password ()
		{
			if ( isset( $_SESSION['userid'] ) ) {
				$userModel = $this->call_model('UserModel');
				$galleryModel = $this->call_model('GalleryModel');
				$userData = $userModel->findUserById( $_SESSION['userid'] );
				$gallery = $galleryModel->getAllEditedImages();
				switch( $_SERVER['REQUEST_METHOD'] ) {
					case 'GET':
						$this->call_view(
							'user' . DIRECTORY_SEPARATOR .'change_password',
							[ 'success' => "true", 'data' => [ 'userData' => $userData, 'gallery' => $gallery ] ]
							);
					break;
					case 'POST':
						if ( isset($_POST['btn-submit']) ) {
							unset( $_POST['btn-submit'] );
							if ( ( $error = $this->call_middleware('UserMiddleware')->change_password( $_SESSION['userid'], $_POST ) ) != null ) {
								$this->call_view(
									'user' . DIRECTORY_SEPARATOR .'change_password',
									[
										'success' => "false",
										'msg' => $error,
										'data' => [ 'userData' => $userData, 'gallery' => $gallery ]
									]
								);
							} else {
								try {
									if ( $userModel->change_password( $_SESSION['userid'], password_hash($_POST['newpassword'], PASSWORD_ARGON2I) ) ) {
										$this->call_view(
											'user' . DIRECTORY_SEPARATOR .'change_password',
											[
												'success' => "true",
												'msg' => "Your password has been changed successfully",
												'data' => [ 'userData' => $userData, 'gallery' => $gallery ]
											]
										);
									} else {
										$this->call_view(
											'user' . DIRECTORY_SEPARATOR .'change_password',
											[
												'success' => "false",
												'msg' => "Failed to change your password !",
												'data' => [ 'userData' => $userData, 'gallery' => $gallery ]
											]
										);
									}
								} catch ( Exception $e ) {
									$this->call_view(
										'user' . DIRECTORY_SEPARATOR .'change_password',
										[
											'success' => "false",
											'msg' => "Something goes wrong while changing your password !",
											'data' => [ 'userData' => $userData, 'gallery' => $gallery ]
										]
									);
								}
							}
						}
					break;
				}
			} else {
				header("Location: /camagru_git/home");
			}
			$this->view->render();
		}

		// public function		notifications_preferences ( $data )
		// {
		// 	if ( isset( $_SESSION['userid'] ) ) {
		// 		$userData = $this->call_model('UserModel')->findUserById( $_SESSION['userid'] );
		// 		$gallery = $this->call_model('GalleryModel')->getAllEditedImages();
				
		// 		if ( isset( $data[0] ) && isset( $data[1] ) ) {
		// 			if (
		// 				( isset( $data[0] ) && $data[0] === "notificationsemail" ) &&
		// 				( isset( $data[1] ) && ( $data[1] !== "1" || $data[1] !== "0" ) )
		// 			) {
		// 				switch( $_SERVER['REQUEST_METHOD'] ) {
		// 					case 'GET':
		// 						$this->call_view(
		// 							'user' . DIRECTORY_SEPARATOR .'notifications_preferences',
		// 							[
		// 								'success' => "true",
		// 								'data' => [ 'userData' => $userData, 'gallery' => $gallery ] 
		// 							]
		// 						)->render();
		// 					break;
		// 					case 'POST':
		// 						if ( isset( $_POST['btn-change-preference'] ) ) {
		// 							unset( $_POST['btn-change-preference'] );
		// 							try {
		// 								if ( $this->call_model('UserModel')->change_preference_email_notifs( $_SESSION['userid'], $data[1] ) ) {
		// 									$userData = $this->call_model('UserModel')->findUserById( $_SESSION['userid'] );
		// 									$this->call_view(
		// 										'user' . DIRECTORY_SEPARATOR .'notifications_preferences',
		// 										[
		// 											'success' => "false",
		// 											'data' => [ 'userData' => $userData, 'gallery' => $gallery ]
		// 										]
		// 									)->render();
		// 								} else {
		// 									$this->call_view(
		// 										'user' . DIRECTORY_SEPARATOR .'notifications_preferences',
		// 										[
		// 											'success' => "false",
		// 											'msg' => "Failed to change your notifications preference !",
		// 											'data' => [ 'userData' => $userData, 'gallery' => $gallery ]
		// 										]
		// 									)->render();
		// 								}
		// 							} catch ( Exception $e ) {
		// 								$this->call_view(
		// 									'user' . DIRECTORY_SEPARATOR .'notifications_preferences',
		// 									[
		// 										'success' => "false",
		// 										'msg' => "Something is wrong, try later !",
		// 										'data' => [ 'userData' => $userData, 'gallery' => $gallery ]
		// 									]
		// 								)->render();
		// 							}
		// 						}
		// 					break;
		// 				}
		// 			}
		// 			else {
		// 				$this->call_view(
		// 					'user' . DIRECTORY_SEPARATOR  . 'notifications_preferences',
		// 					[
		// 						'success' => "true",
		// 						'data' => [ 'userData' => $userData, 'gallery' => $gallery ] 
		// 					]
		// 				)->render();
		// 			}
		// 		} else {
		// 			$this->call_view( 'user' . DIRECTORY_SEPARATOR . 'notifications_preferences',
		// 				[
		// 					'success' => "true",
		// 					'data' => [ 'userData' => $userData, 'gallery' => $gallery ] 
		// 				]
		// 			)->render();
		// 		}
		// 	} else {
		// 		header("Location: /camagru_git/home");
		// 	}
		// }

		// private function makeMixedImage( $userData, $destPath, $srcPath, $xdest, $udest )
		// {
		// 	$dest = imagecreatefrompng( $destPath ); 
		// 	$src = imagecreatefrompng( $srcPath);

		// 	// Get new sizes
		// 	list($width, $height) = getimagesize($srcPath);
		// 	// Copy and merge 
		// 	imagecopyresized($dest, $src, $xdest, $udest, 0, 0, 150, 150, $width, $height);
		// 	// Output and free from memory 
		// 	imagejpeg($dest, EDITEDPICS .'IMG'.'_'.$userData['id'].'_'.$userData['username'].'_'.time().'.png'); 							  
		// 	imagedestroy($dest);
		// 	imagedestroy($src);
		// }

		// public function		editing ()
		// {
		// 	if ( isset( $_SESSION['userid'] ) ) {
		// 		$userData = $this->call_model('UserModel')->findUserById( $_SESSION['userid'] );
		// 		$userGallery = $this->call_model('GalleryModel')->userGallery( $_SESSION['userid'] );
		// 		$gallery = $this->call_model('GalleryModel')->getAllEditedImages();
		// 		switch( $_SERVER['REQUEST_METHOD'] ) {
		// 			case 'GET':
		// 				$this->call_view(
		// 					'user' . DIRECTORY_SEPARATOR .'editing',
		// 					[ 'data' => [ 'userData' => $userData, 'gallery' => $gallery, 'userGallery' => $userGallery ]  ]
		// 				)->render();
		// 			break;
		// 			case 'POST':
		// 				if ( isset( $_POST['btn-save'] ) ) {
		// 					$_POST['id'] = $userData['id'];
		// 					$imgWebcam = $_POST['dataimage'];
		// 					$imgWebcam = str_replace('data:image/png;base64', '', $imgWebcam);
		// 					$imgWebcam = str_replace(' ', '+', $imgWebcam);
		// 					$fileData = base64_decode( $imgWebcam );
		// 					$pathFile = EDITEDPICS .'IMG'.'_'.$userData['id'].'_'.$userData['username'].'_'.time().'.png';
		// 					file_put_contents($pathFile, $fileData);
		// 					$srcPath = $_POST['sticker'];
		// 					$destPath = str_replace('\\\\', '//', str_replace('\\', '/', str_replace( PUBLIC_DIR, PUBLIC_FOLDER . '/', $pathFile ) ) );
		// 					$this->makeMixedImage( $userData, $destPath, $srcPath, intval($_POST['x']), intval($_POST['y']) );
		// 					try {
		// 						if ( $this->call_model('GalleryModel')->addImage([ 'id' => $_SESSION['userid'], 'src' => $destPath ]) ) {
		// 							$userGallery = $this->call_model('GalleryModel')->userGallery( $_SESSION['userid'] );
		// 							$gallery = $this->call_model('GalleryModel')->getAllEditedImages();
		// 							$this->call_view(
		// 								'user' . DIRECTORY_SEPARATOR .'editing',
		// 								[
		// 									'success' => "true",
		// 									'msg' => "Image has been saved successfully !",
		// 									'data' => [ 'userData' => $userData, 'gallery' => $gallery, 'userGallery' => $userGallery ] 
		// 								]
		// 							)->render();
		// 						} else {
		// 							$this->call_view(
		// 								'user' . DIRECTORY_SEPARATOR .'editing',
		// 								[
		// 									'success' => "false",
		// 									'msg' => "Failed to edit snapchat !",
		// 									'data' => [ 'userData' => $userData, 'gallery' => $gallery, 'userGallery' => $userGallery ] 
		// 								]
		// 							)->render();
		// 						}
		// 					} catch ( Exception $e ) {
		// 						$this->call_view(
		// 							'user' . DIRECTORY_SEPARATOR .'editing',
		// 							[
		// 								'success' => "false",
		// 								'msg' => "Something goes wrong, try later !",
		// 								'data' => [ 'userData' => $userData, 'gallery' => $gallery, 'userGallery' => $userGallery ] 
		// 							]
		// 						)->render();
		// 					}
		// 				}
		// 			break;
		// 		}
		// 	} else {
		// 		header("Location: /camagru_git/signin");
		// 	}
		// }

		public function     logout ()
		{
			if ( isset( $_SESSION['userid'] ) ) {
				$this->call_view( 'user' . DIRECTORY_SEPARATOR .'logout', )->render();
			} else {
				header("Location: /camagru_git/home");
			}
		}

	}
?>