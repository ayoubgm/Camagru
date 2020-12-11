<?php

	/**
	 * 	user controller class
	 */
	class userController extends Controller {

		private $viewData;
		private $userMiddleware;
		private $userModel;
		private $galleryModel;

		public function 				__construct ()
		{
			session_start();
			$this->viewData = array();
			$this->userMiddleware = self::call_middleware('UserMiddleware');
			$this->userModel = self::call_model('UsersModel');
			$this->galleryModel = self::call_model('GalleryModel');
		}

		public function 				profile( $data )
		{
			$redirect = null;

			if ( !isset( $_SESSION["userid"] ) || empty( $_SESSION["userid"] ) ) {
				$redirect = "signin";
			} else {
				$redirect = "profile";
				try {
					$this->viewData = [
						"success" => "true",
						"data" => [ "gallery" => $this->galleryModel->getAllEditedImages() ]
					];
					if ( ( isset( $data[0] ) && $data[0] === "username" ) && ( isset( $data[1] ) && !empty( $data[1] ) ) ) {
						$this->viewData["data"] += [
							"userData" => $this->userModel->findUserByUsername( strtolower( $data[1] ) ),
							"userGallery" => $this->galleryModel->userGallery( strtolower( $data[1] ) )
						];
					} else {
						$this->viewData["data"] += [
							"userData" => $this->userModel->findUserById( $_SESSION["userid"] ),
							"userGallery" => $this->galleryModel->userGallery( $_SESSION["username"] )
						];
					}
				} catch ( Exception $e ) {
					$this->viewData["success"] = "false";
					$this->viewData["msg"] = "Something goes wrong, try later !";
				}
			}
			if ( $redirect == "signin" ) { $this->call_view( "home" . DIRECTORY_SEPARATOR ."signin" )->render(); }
			else { $this->call_view( "user" . DIRECTORY_SEPARATOR ."profile", $this->viewData )->render(); }
		}
		
		public function 				edit()
		{
			if ( isset( $_SESSION["userid"] ) && !empty( $_SESSION["userid"] ) ) {
				try {
					$this->viewData["data"] = [
						"gallery" => $this->galleryModel->getAllEditedImages(),
						"userData" => $this->userModel->findUserById( $_SESSION["userid"] ),
						"userGallery" => $this->galleryModel->userGallery( $_SESSION["username"] )
					];
					$oldData = $this->userModel->findUserById( $_SESSION["userid"] );
					switch( $_SERVER["REQUEST_METHOD"] ) {
						case "GET":
							$this->viewData[ "success" ] = "true";
						break;
						case "POST":
							if ( isset($_POST["btn-edit"]) ) {
								unset( $_POST["btn-edit"] ); unset( $oldData["id"] ); unset( $oldData["createdat"] ); unset( $oldData["notifEmail"] );
								$editedData = array_replace_recursive( $oldData, $_POST );
								if ( ($error = $this->userMiddleware->edit( $_SESSION["userid"], $editedData )) != null) {
									$this->viewData += [ "success" => "false", "msg" => $error ];
									$this->viewData["data"]["userData"] = $editedData;
								} else {
									if ( $this->userModel->edit( $_SESSION["userid"], $editedData ) ) {
										$this->viewData += [ "success" => "true", "msg" => "Your informations has been edited successfully !" ];
										$this->viewData["data"]["userData"] = $this->userModel->findUserById( $_SESSION["userid"] );
									} else {
										$this->viewData += [ "success" => "false", "msg" => "Failed to update your informations !" ];
									}
								}
							}
						break;
					}
				} catch ( Exception $e ) {
					$this->viewData["success"] = "false";
					$this->viewData["msg"] = "Something goes wrong while editing your informations, try later !";
				}
				$this->call_view( "user" . DIRECTORY_SEPARATOR ."edit_infos", $this->viewData )->render();
			} else {
				header("Location: /home");
			}
		}

		public function 				settings ()
		{
			if ( isset( $_SESSION["userid"] ) && !empty( $_SESSION["userid"] ) ) {
				try {
					$this->viewData["data"] = [
						"success" => "true",
						"userData" => $this->userModel->findUserById( $_SESSION["userid"] ),
						"userGallery" => $this->galleryModel->userGallery( $_SESSION["username"] ),
						"gallery" => $this->galleryModel->getAllEditedImages()
					];
				} catch ( Exception $e ) {
					$this->viewData["success"] = "false";
					$this->viewData["msg"] = "Something goes wrong, try later !";
				}
				$this->call_view( "user" . DIRECTORY_SEPARATOR ."settings", $this->viewData )->render();
			} else {
				header("Location: /home");
			}
		}

		public function 				change_password ()
		{
			if ( isset( $_SESSION["userid"] ) && !empty( $_SESSION["userid"] ) ) {
				try {
					$this->viewData["data"] = [
						"gallery" => $this->galleryModel->getAllEditedImages(),
						"userData" => $this->userModel->findUserById( $_SESSION["userid"] ),
						"userGallery" => $this->galleryModel->userGallery( $_SESSION["username"] )
					];
					switch( $_SERVER["REQUEST_METHOD"] ) {
						case "GET":
							$this->viewData["success"] = "true";
						break;
						case "POST":
							if ( isset($_POST["btn-submit"]) && !empty($_POST["btn-submit"]) ) {
								unset( $_POST["btn-submit"] );
								if ( $error = $this->userMiddleware->change_password( $_SESSION["userid"], $_POST ) ) {
									$this->viewData += [ "success" => "false", "msg" => $error ];
								} else {
									if ( $this->userModel->change_password( $_SESSION["userid"], password_hash($_POST["newpassword"], PASSWORD_ARGON2I) ) ) {
										$this->viewData += [ "success" => "true", "msg" => "Your password has been changed successfully" ];
									} else {
										$this->viewData += [ "success" => "false", "msg" => "Failed to change your password !" ];
									}
								}
							}
						break;
					}
				} catch ( Exception $e ) {
					$this->viewData["success"] = "false";
					$this->viewData["msg"] = "Something goes wrong while changing your password !";
				}
				$this->call_view( "user" . DIRECTORY_SEPARATOR ."change_password", $this->viewData )->render();
			} else {
				header("Location: /home");
			}
		}

		public function 				notifications_preferences ( $data )
		{
			if ( isset( $_SESSION["userid"] ) && !empty( $_SESSION["userid"] ) ) {
				try {
					$this->viewData["data"] = [
						"userData" => $this->userModel->findUserById( $_SESSION["userid"] ),
						"userGallery" => $this->galleryModel->userGallery( $_SESSION["username"] ),
						"gallery" => $this->galleryModel->getAllEditedImages()
					];
					if ( isset( $data[0] ) && isset( $data[1] ) && ( isset( $data[0] ) && $data[0] === "notificationsemail" ) && ( isset( $data[1] ) && ( $data[1] === "1" || $data[1] === "0" ) ) ) {
						switch( $_SERVER["REQUEST_METHOD"] ) {
							case "GET":
								$this->viewData["success"] = "true";
							break;
							case "POST":
								if ( isset( $_POST["btn-change-preference"] ) ) {
									unset( $_POST["btn-change-preference"] );
									if ( $this->userModel->change_preference_email_notifs( $_SESSION["userid"], $data[1] ) ) {
										$this->viewData["success"] = "true";
										$this->viewData["data"]["userData"] = $this->userModel->findUserById( $_SESSION["userid"] );
									} else {
										$this->viewData["success"] = "false";
										$this->viewData["msg"] = "Failed to change your notifications preference !";
									}						
								}
							break;
						}
					} else {
						$this->viewData["success"] = "false";
						$this->viewData["msg"] = "Something is wrong !";
					}
				} catch ( Exception $e ) {
					$this->viewData["success"] = "false";
					$this->viewData["msg"] = "Something is wrong, try later !";
				}
				$this->call_view( "user" . DIRECTORY_SEPARATOR . "notifications_preferences", $this->viewData)->render();
			} else {
				header("Location: /home");
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
				$viewData['data']['userGallery'] = $this->galleryModel->userGallery( $_SESSION['username'] );

				switch( $_SERVER['REQUEST_METHOD'] ) {
					case 'GET':
						$viewData['success'] = true;
					break;
					case 'POST':
						if ( isset( $_POST['btn-save'] ) ) {
							try {
								$imgWebcam = $_POST['dataimage'];
								$imgCamBase64 = str_replace('data:image/png;base64', '', $imgWebcam);
								$finalImageCam = str_replace(' ', '+', $imgCamBase64);
								$fileData = base64_decode( $finalImageCam );
								$pathFile = EDITEDPICS.'IMG'.'_'.time().'_'.$_SESSION['userid'].'_'.$_SESSION['username'].'.png';
								file_put_contents($pathFile, $fileData);
								$srcPath = $_POST['sticker'];
								$destPath = str_replace('\\\\', '//', str_replace('\\', '/', str_replace( PUBLIC_DIR, PUBLIC_FOLDER . '/', $pathFile ) ) );
								$this->makeMixedImage( $viewData['data']['userData'], $destPath, $srcPath, intval($_POST['x']), intval($_POST['y']) );
								if ( $this->galleryModel->addImage([ 'id' => $_SESSION['userid'], 'src' => $destPath ]) ) {
									$viewData['success'] = "true";
									$viewData['msg'] = "Image has been saved successfully !";
									$viewData['data']['gallery'] = $this->galleryModel->getAllEditedImages();
									$viewData['data']['userGallery'] = $this->galleryModel->userGallery( $_SESSION['username'] );
								} else {
									$viewData['success'] = "false";
									$viewData['msg'] = "Failed to edit snapchat !";
									$viewData['data']['gallery'] = $this->galleryModel->getAllEditedImages();
									$viewData['data']['userGallery'] = $this->galleryModel->userGallery( $_SESSION['username'] );
								}
							} catch ( Exception $e ) {
								$viewData['success'] = "false";
								$viewData['msg'] = "Something goes wrong, try later !";
								$viewData['data']['gallery'] = $this->galleryModel->getAllEditedImages();
								$viewData['data']['userGallery'] = $this->galleryModel->userGallery( $_SESSION['username'] );
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
			if ( isset( $_SESSION['userid'] ) ) { $this->call_view( 'user' . DIRECTORY_SEPARATOR .'logout' )->render(); }
			else { header("Location: /signin"); }
		}

	}
?>