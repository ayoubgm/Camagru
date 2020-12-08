<?php

	/**
	 * 	user controller class
	 */
	class userController extends Controller {

		private $userMiddleware;
		private $userModel;
		private $galleryModel;

		public function 				__construct ()
		{
			session_start();
			$this->userMiddleware = self::call_middleware('UserMiddleware');
			$this->userModel = self::call_model('UsersModel');
			$this->galleryModel = self::call_model('GalleryModel');
		}

		public function 				profile ( $data )
		{
			$viewData = array();
			
			if ( !isset( $_SESSION['userid'] ) && empty( $_SESSION['userid'] ) ) {
				$this->call_view( 'home' . DIRECTORY_SEPARATOR .'signin')->render();
				header("Location: /camagru/home/signin");
			} else if ( ( isset( $data[0] ) && $data[0] === "username" ) && ( isset( $data[1] ) && !empty( $data[1] ) ) ) {
				$viewData['success'] = "true";
				$viewData['data']['userData'] = $this->userModel->findUserByUsername( strtolower( $data[1] ) );
				$viewData['data']['userGallery'] = $this->galleryModel->userGallery( $_SESSION['userid'] );
				$viewData['data'][ 'gallery'] = $this->galleryModel->getAllEditedImages();
				$this->call_view( 'user' . DIRECTORY_SEPARATOR .'profile', $viewData)->render();
			} else {
				$viewData['success'] = "true";
				$viewData['data']['userData'] = $this->userModel->findUserById( $_SESSION['userid'] );
				$viewData['data'][ 'gallery'] = $this->galleryModel->getAllEditedImages();
				$viewData['data']['userGallery'] = $this->galleryModel->userGallery( $_SESSION['userid'] );
				$this->call_view( 'user' . DIRECTORY_SEPARATOR .'profile', $viewData)->render();
			}
		}
		
		public function 				edit ()
		{
			$viewData = array();
			$viewData['data'] = [ 'gallery' => $this->galleryModel->getAllEditedImages() ];

			if ( isset( $_SESSION['userid'] ) && !empty( $_SESSION['userid'] ) ) {
				$viewData['data']['userData'] = $this->userModel->findUserById( $_SESSION['userid'] );
				$viewData['data']['userGallery'] = $this->galleryModel->userGallery( $_SESSION['userid'] );
				$oldData = $this->userModel->findUserById( $_SESSION['userid'] );

				switch( $_SERVER['REQUEST_METHOD'] ) {
					case 'GET':
						$viewData[ 'success' ] = "true";
					break;
					case 'POST':
						if ( isset($_POST['btn-edit']) ) {
							$userID = $oldData['id'];
							unset( $_POST['btn-edit'] ); unset( $oldData['id'] ); unset( $oldData['createdat'] ); unset( $oldData['notifEmail'] );
							$editedData = array_replace_recursive( $oldData, $_POST );
							if ( ($error = $this->userMiddleware->edit( $userID, $editedData )) != null){
								$viewData[ 'success' ] = "false";
								$viewData[ 'msg' ] = $error;
								$viewData['data']['userData'] = $editedData;
							} else {
								try {
									if ( $this->userModel->edit( $userID, $editedData ) ) {
										$newData = $this->userModel->findUserById( $_SESSION['userid'] );
										$viewData[ 'success' ] = "true";
										$viewData[ 'msg' ] = "Your informations has been edited successfully !";
										$viewData['data']['userData'] = $newData;
									} else {
										$viewData[ 'success' ] = "false";
										$viewData[ 'msg' ] = "Failed to update your informations !";
									}
								} catch( Exception $e ) {
									$viewData[ 'success' ] = "false";
									$viewData[ 'msg' ] = "Something goes wrong while edit informations, try later !";
								}
							}
						}
					break;
				}
				$this->call_view( 'user' . DIRECTORY_SEPARATOR .'edit_infos', $viewData )->render();
			} else {
				header("Location: /camagru/home");
			}
		}

		public function 				settings ()
		{
			$viewData = array();
			
			if ( isset( $_SESSION['userid'] ) && !empty( $_SESSION['userid'] ) ) {
				$viewData['data'] = [
					'userData' => $this->userModel->findUserById( $_SESSION['userid'] ),
					'userGallery' => $this->galleryModel->userGallery( $_SESSION['userid'] ),
					'gallery' => $this->galleryModel->getAllEditedImages()
				];
				$viewData['success'] = "true";
				$this->call_view( 'user' . DIRECTORY_SEPARATOR .'settings', $viewData )->render();
			} else {
				header("Location: /camagru/home");
			}
		}

		public function 				change_password ()
		{
			$viewData = array();
			$viewData['data'] = [ 'gallery' => $this->galleryModel->getAllEditedImages() ];

			if ( isset( $_SESSION['userid'] ) && !empty( $_SESSION['userid'] ) ) {
				$viewData['data']['userData'] = $this->userModel->findUserById( $_SESSION['userid'] );
				$viewData['data']['userGallery'] = $this->galleryModel->userGallery( $_SESSION['userid'] );
				
				switch( $_SERVER['REQUEST_METHOD'] ) {
					case 'GET':
						$viewData['success'] = "true";
					break;
					case 'POST':
						if ( isset($_POST['btn-submit']) && !empty($_POST['btn-submit']) ) {
							unset( $_POST['btn-submit'] );
							if ( ( $error = $this->userMiddleware->change_password( $_SESSION['userid'], $_POST ) ) != null ) {
								$viewData['success'] = "false";
								$viewData['msg'] = $error;
							} else {
								try {
									if ( $this->userModel->change_password( $_SESSION['userid'], password_hash($_POST['newpassword'], PASSWORD_ARGON2I) ) ) {
										$viewData['success'] = "true";
										$viewData['msg'] = "Your password has been changed successfully";
									} else {
										$viewData['success'] = "false";
										$viewData['msg'] = "Failed to change your password !";
									}
								} catch ( Exception $e ) {
									$viewData['success'] = "false";
									$viewData['msg'] = "Something goes wrong while changing your password !";
								}
							}
						}
					break;
				}
				$this->call_view( 'user' . DIRECTORY_SEPARATOR .'change_password', $viewData )->render();
			} else {
				header("Location: /camagru/home");
			}
		}

		public function 				notifications_preferences ( $data )
		{
			$viewData = array();
			$viewData['data'] = [ 'gallery' => $this->galleryModel->getAllEditedImages() ];
			
			if ( isset( $_SESSION['userid'] ) && !empty( $_SESSION['userid'] ) ) {
				$viewData['data']['userData'] = $this->userModel->findUserById( $_SESSION['userid'] );
				$viewData['data']['userGallery'] = $this->galleryModel->userGallery( $_SESSION['userid'] );
				
				if ( isset( $data[0] ) && isset( $data[1] ) ) {
					if ( ( isset( $data[0] ) && $data[0] === "notificationsemail" ) && ( isset( $data[1] ) && ( $data[1] === "1" || $data[1] === "0" ) ) ) {
						switch( $_SERVER['REQUEST_METHOD'] ) {
							case 'GET':
								$viewData ['success'] = "true";
							break;
							case 'POST':
								if ( isset( $_POST['btn-change-preference'] ) && !empty($_POST['btn-change-preference']) ) {
									unset( $_POST['btn-change-preference'] );
									try {
										if ( $this->userModel->change_preference_email_notifs( $_SESSION['userid'], $data[1] ) ) {
											$userData = $this->userModel->findUserById( $_SESSION['userid'] );
											
											$viewData['success'] = "true";
											$viewData['data']['userData'] = $userData;
										} else {
											$viewData['success'] = "false";
											$viewData['msg'] = "Failed to change your notifications preference !";
										}
									} catch ( Exception $e ) {
										$viewData['success'] = "false";
										$viewData['msg'] = "Something is wrong, try later !";
									}
								}
							break;
						}
					} else {
						$viewData['success'] = "false";
						$viewData['msg'] = "Something is wrong !";
					}
				} else {
					$viewData['success'] = "false";
					$viewData['msg'] = "Something is wrong !";
				}
				$this->call_view( 'user' . DIRECTORY_SEPARATOR . 'notifications_preferences', $viewData)->render();
			} else {
				header("Location: /camagru/home");
			}
		}

		private function 				makeMixedImage( $userData, $destPath, $srcPath, $xdest, $udest )
		{
			$dest = imagecreatefrompng( $destPath ); 
			$src = imagecreatefrompng( $srcPath);

			// Get new sizes
			list($width, $height) = getimagesize($srcPath);
			// Copy and merge 
			imagecopyresized($dest, $src, $xdest, $udest, 0, 0, 150, 150, $width, $height);
			// Output and free from memory 
			imagejpeg($dest, EDITEDPICS .'IMG'.'_'.time().'_'.$userData['id'].'_'.$userData['username'].'.png'); 							  
			imagedestroy($dest);
			imagedestroy($src);
		}

		public function 				editing ()
		{
			$viewData = array();
			$viewData['data'] = [ 'gallery' => $this->galleryModel->getAllEditedImages() ];

			if ( isset( $_SESSION['userid'] ) ) {
				$viewData['data']['userData'] = $this->userModel->findUserById( $_SESSION['userid'] );
				$viewData['data']['userGallery'] = $this->galleryModel->userGallery( $_SESSION['userid'] );

				switch( $_SERVER['REQUEST_METHOD'] ) {
					case 'GET':
						$viewData['success'] = true;
					break;
					case 'POST':
						if ( isset( $_POST['btn-save'] ) ) {
							$_POST['id'] = $viewData['data']['userData']['id'];
							$imgWebcam = $_POST['dataimage'];
							$imgWebcam = str_replace('data:image/png;base64', '', $imgWebcam);
							$imgWebcam = str_replace(' ', '+', $imgWebcam);
							$fileData = base64_decode( $imgWebcam );
							$pathFile = EDITEDPICS .'IMG'.'_'.time().'_'.$viewData['data']['userData']['id'].'_'.$viewData['data']['userData']['username'].'.png';
							file_put_contents($pathFile, $fileData);
							$srcPath = $_POST['sticker'];
							$destPath = str_replace('\\\\', '//', str_replace('\\', '/', str_replace( PUBLIC_DIR, PUBLIC_FOLDER . '/', $pathFile ) ) );
							$this->makeMixedImage( $viewData['data']['userData'], $destPath, $srcPath, intval($_POST['x']), intval($_POST['y']) );
							try {
								if ( $this->galleryModel->addImage([ 'id' => $_SESSION['userid'], 'src' => $destPath ]) ) {
									$viewData['success'] = "true";
									$viewData['msg'] = "Image has been saved successfully !";
									$viewData['data']['gallery'] = $this->galleryModel->getAllEditedImages();
									$viewData['data']['userGallery'] = $this->galleryModel->userGallery( $_SESSION['userid'] );
								} else {
									$viewData['success'] = "false";
									$viewData['msg'] = "Failed to edit snapchat !";
									$viewData['data']['gallery'] = $this->galleryModel->getAllEditedImages();
									$viewData['data']['userGallery'] = $this->galleryModel->userGallery( $_SESSION['userid'] );
								}
							} catch ( Exception $e ) {
								$viewData['success'] = "false";
								$viewData['msg'] = "Something goes wrong, try later !";
								$viewData['data']['gallery'] = $this->galleryModel->getAllEditedImages();
								$viewData['data']['userGallery'] = $this->galleryModel->userGallery( $_SESSION['userid'] );
							}
						}
					break;
				}
				$this->call_view( 'user' . DIRECTORY_SEPARATOR .'editing', $viewData )->render();
			} else {
				header("Location: /signin");
			}
		}

		public function 				logout ()
		{
			if ( isset( $_SESSION['userid'] ) ) {
				$this->call_view( 'user' . DIRECTORY_SEPARATOR .'logout' )->render();
			} else {
				header("Location: /home");
			}
		}

	}
?>