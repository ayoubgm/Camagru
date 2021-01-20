<?php
	/**
	 * 	User controller class
	 */
	class userController extends Controller
	{

		public function 				__construct ()
		{
			parent::__construct();
			session_start();
		}

		public function 				profile( $data )
		{
			try {
				if ( !$this->user_middleware->isSignin( $_SESSION ) ) {
					header("Location: /signin");
				} else if ( $this->helper->isRequestGET( $_SERVER["REQUEST_METHOD"] ) ) {
					$this->viewData = [
						"success" => "true",
						"data" => [
							"gallery" => $this->gallery_model->getAllEditedImages(),
							"countUnreadNotifs" => $this->notifications_model->getCountUnreadNotifications( $_SESSION['userid'] )
						]
					];
					if ( ( isset( $data[0] ) && $data[0] == "username" ) && ( isset( $data[1] ) && !empty( $data[1] ) ) ) {
						if ( $data = $this->user_model->findUserByUsername( $data[1] ) ) {
							$this->viewData["data"][ "userData"] = $data;
						} else {
							$this->viewData["success"] = "false";
							$this->viewData["msg"] = "The profile of the username specified is not found !";
						}
					} else {
						$this->viewData["data"][ "userData"] = $this->user_model->findUserById( $_SESSION["userid"] );
					}
				}
			} catch ( Exception $e ) {
				$this->viewData["success"] = "false";
				$this->viewData["msg"] = "Something goes wrong while get your profile informations, try later !";
			}
			$this->call_view( "user" . DIRECTORY_SEPARATOR . "profile", $this->viewData )->render();
		}
		
		public function 				edit()
		{
			try {
				if ( !$this->user_middleware->isSignin( $_SESSION ) ) {
					header("Location: /signin");
				} else {
					$this->viewData["data"] = [
						"gallery" => $this->gallery_model->getAllEditedImages(),
						"userData" => $this->user_model->findUserById( $_SESSION["userid"] ),
						"countUnreadNotifs" => $this->notifications_model->getCountUnreadNotifications( $_SESSION['userid'] )
					];
					if ( $this->helper->isRequestGET( $_SERVER["REQUEST_METHOD"] ) ) {
						$this->viewData[ "success" ] = "true";
					} else if ( $this->helper->isRequestPOST( $_SERVER["REQUEST_METHOD"] ) ) {
						if (
							$this->helper->validate_inputs([
								'token' => [ "REQUIRED" => true, "EMPTY" => false ],
								'btn-edit' => [ "REQUIRED" => true, "EMPTY" => false ],
								'firstname' => [ "REQUIRED" => true, "EMPTY" => false ],
								'lastname' => [ "REQUIRED" => true, "EMPTY" => false ],
								'username' => [ "REQUIRED" => true, "EMPTY" => false ],
								'email' => [ "REQUIRED" => true, "EMPTY" => false ],
								'gender' => [ "REQUIRED" => true, "EMPTY" => false ],
								'address' => [ "REQUIRED" => true, "EMPTY" => true ],
							], $_POST )
						) {
							if (
								( 
									$_POST = $this->helper->filter_inputs( "POST", array(
										'token' => FILTER_SANITIZE_STRING,
										'btn-edit' => FILTER_SANITIZE_STRING,
										'firstname' => FILTER_SANITIZE_STRING,
										'lastname' => FILTER_SANITIZE_STRING,
										'username' => FILTER_SANITIZE_STRING,
										'email' => FILTER_SANITIZE_EMAIL,
										'gender' => FILTER_SANITIZE_STRING,
										'address' => FILTER_SANITIZE_STRING
									))
								) &&
								( $this->user_middleware->validateUserToken( $_POST["token"] ) )
							) {
								$oldData = $this->user_model->findUserById( $_SESSION["userid"] );
								unset( $_POST["btn-edit"] ); unset( $_POST["token"] );
								unset( $oldData["id"] ); unset( $oldData["createdat"] ); unset( $oldData["notifEmail"] );
								$editedData = array_replace_recursive( $oldData, $_POST );
								if ( $error = $this->user_middleware->edit( $_SESSION["userid"], $editedData ) ) {
									$this->viewData += [ "success" => "false", "msg" => $error ];
									$this->viewData["data"]["userData"] = $editedData;
								} else if ( $this->user_model->edit( $_SESSION["userid"], $editedData ) ) {
									$this->viewData += [ "success" => "true", "msg" => "Your informations has been edited successfully !" ];
									$this->viewData["data"]["userData"] = $this->user_model->findUserById( $_SESSION["userid"] );
								} else {
									$this->viewData += [ "success" => "false", "msg" => "Failed to change your informations !" ];
									$this->viewData["data"]["userData"] = $editedData;
								}
							} else {
								$this->viewData["success"] = "false";
								$this->viewData["msg"] = "Couldn't update your informations !";
							}
						} else {
							$this->viewData["success"] = "false";
							$this->viewData["msg"] = "Something is missing !";
						}
					}
				}
			} catch ( Exception $e ) {
				$this->viewData["success"] = "false";
				$this->viewData["msg"] = "Something goes wrong while update your informations, try later !";
			}
			$this->call_view( "user" . DIRECTORY_SEPARATOR ."edit_infos", $this->viewData )->render();
		}

		public function 				settings ()
		{
			try {
				if ( !$this->user_middleware->isSignin( $_SESSION ) ) {
					header("Location: /signin");
				} else if ( $this->helper->isRequestGET( $_SERVER["REQUEST_METHOD"] ) ) {
					$this->viewData = [
						"success" => "true",
						"data" => [
							"userData" => $this->user_model->findUserById( $_SESSION["userid"] ),
							"gallery" => $this->gallery_model->getAllEditedImages(),
							"countUnreadNotifs" => $this->notifications_model->getCountUnreadNotifications( $_SESSION['userid'] )
						]
					];
				}
			} catch ( Exception $e ) {
				$this->viewData["success"] = "false";
				$this->viewData["msg"] = "Something goes wrong, try later !";
			}
			$this->call_view( "user" . DIRECTORY_SEPARATOR ."settings", $this->viewData )->render();
		}

		public function 				change_password ()
		{
			try {
				if ( !$this->user_middleware->isSignin( $_SESSION ) ) {
					header("Location: /signin");
				} else {
					$this->viewData["data"] = [
						"gallery" => $this->gallery_model->getAllEditedImages(),
						"userData" => $this->user_model->findUserById( $_SESSION["userid"] ),
						"countUnreadNotifs" => $this->notifications_model->getCountUnreadNotifications( $_SESSION['userid'] )
					];
					if ( $this->helper->isRequestGET( $_SERVER["REQUEST_METHOD"] ) ) {
						$this->viewData["success"] = "true";
					} else if ( $this->helper->isRequestPOST( $_SERVER["REQUEST_METHOD"] ) ) {
						if (
							$this->helper->validate_inputs([
								'token' => [ "REQUIRED" => true, "EMPTY" => false ],
								'btn-submit' => [ "REQUIRED" => true, "EMPTY" => false ],
								'oldpassword' => [ "REQUIRED" => true, "EMPTY" => false ],
								'newpassword' => [ "REQUIRED" => true, "EMPTY" => false ],
								'confirmation_password' => [ "REQUIRED" => true, "EMPTY" => false ],
							], $_POST )
						) {
							if (
								(
									$_POST = $this->helper->filter_inputs( "POST", array (
										'token' => FILTER_SANITIZE_STRING,
										'btn-submit' => FILTER_SANITIZE_STRING,
										'oldpassword' => FILTER_SANITIZE_STRING,
										'newpassword' => FILTER_SANITIZE_STRING,
										'confirmation_password' => FILTER_SANITIZE_STRING
									))
								) &&
								( $this->user_middleware->validateUserToken( $_POST["token"] ) )
							) {
								unset( $_POST["btn-submit"] );
								if ( $error = $this->user_middleware->change_password( $_SESSION["userid"], $_POST ) ) {
									$this->viewData += [ "success" => "false", "msg" => $error ];
								} else if ( $this->user_model->change_password( $_SESSION["userid"], password_hash($_POST["newpassword"], PASSWORD_BCRYPT) ) ) {
									$this->viewData += [ "success" => "true", "msg" => "Your password has been changed successfully !" ];
								} else {
									$this->viewData += [ "success" => "false", "msg" => "Failed to change your password !" ];
								}
							} else {
								$this->viewData["success"] = "false";
								$this->viewData["msg"] = "Couldn't change your password !";
							}
						} else {
							$this->viewData += [ "success" => "false", "msg" => "Something is missing !" ];
						}
					}
				}
			} catch ( Exception $e ) {
				$this->viewData["success"] = "false";
				$this->viewData["msg"] = "Something goes wrong while changing your password !";
			}
			$this->call_view( "user" . DIRECTORY_SEPARATOR ."change_password", $this->viewData )->render();
		}

		public function 				notifications_preferences ( $data )
		{
			try {
				if ( !$this->user_middleware->isSignin( $_SESSION ) ) {
					header("Location: /signin");
				} else {
					$this->viewData["data"] = [
						"userData" => $this->user_model->findUserById( $_SESSION["userid"] ),
						"gallery" => $this->gallery_model->getAllEditedImages(),
						"countUnreadNotifs" => $this->notifications_model->getCountUnreadNotifications( $_SESSION['userid'] )
					];
					if (
						( isset( $data[0] ) && isset( $data[1] ) ) &&
						( isset( $data[0] ) && $data[0] === "notificationsemail" ) &&
						( isset( $data[1] ) && ( $data[1] === "1" || $data[1] === "0" ) )
					) {
						if ( $this->helper->isRequestGET( $_SERVER["REQUEST_METHOD"] ) ) {
							$this->viewData["success"] = "true";
						} else if ( $this->helper->isRequestPOST( $_SERVER["REQUEST_METHOD"] ) ) {
							if (
								$this->helper->validate_inputs([
									'token' => [ "REQUIRED" => true, "EMPTY" => false ],
									'btn-change-preference' => [ "REQUIRED" => true, "EMPTY" => false ]
								], $_POST )
							) {
								if (
									(
										$_POST = $this->helper->filter_inputs( "POST", array(
											'token' => FILTER_SANITIZE_STRING,
											'btn-change-preference' => FILTER_SANITIZE_STRING
										))
									) &&
									( $this->user_middleware->validateUserToken( $_POST["token"] ) )
								) {
									unset( $_POST["btn-change-preference"] );
									if ( $this->user_model->change_preference_email_notifs( $_SESSION["userid"], $data[1] ) ) {
										$this->viewData["success"] = "true";
										$this->viewData["data"]["userData"] = $this->user_model->findUserById( $_SESSION["userid"] );
									} else {
										$this->viewData["success"] = "false";
										$this->viewData["msg"] = "Failed to change your notifications preference !";
									}
								} else {
									$this->viewData["success"] = "false";
									$this->viewData["msg"] = "Couldn't ! change your notifications preference !";
								}
							} else {
								$this->viewData["success"] = "false";
								$this->viewData["msg"] = "Something is missing !";
							}
						}
					} else {
						$this->viewData["success"] = "false";
						$this->viewData["msg"] = "Something is missing !";
					}
				}
			} catch ( Exception $e ) {
				$this->viewData["success"] = "false";
				$this->viewData["msg"] = "Something is wrong change your notifications preference !, try later !";
			}
			$this->call_view( "user" . DIRECTORY_SEPARATOR . "notifications_preferences", $this->viewData)->render();
		}

		function 				makeMixedImage( $userData, $destPath, $srcPath, $xdest, $ydest )
		{
			$dest = imagecreatefrompng( $destPath ); 
			$src = imagecreatefrompng( $srcPath);
			// Get new sizes
			list($width, $height) = getimagesize($srcPath);
			// Copy and merge 
			imagecopyresized($dest, $src, $xdest, $ydest, 0, 0, 150, 150, $width, $height);
			// Output and free from memory
			$pathFinalImg = EDITEDPICS .'IMG'.'_'.time().'_'.$userData['id'].'_'.$userData['username'].'.jpeg';
			if ( imagejpeg($dest, $pathFinalImg) ) {
				imagedestroy($dest);
				imagedestroy($src);
				return $pathFinalImg;
			} else {
				return null;
			}
		}

		function				transformPathFileToUrl ( $path )
		{
			if ( isset( $path ) ) {
				return str_replace('\\\\', '//', str_replace('\\', '/', str_replace( PUBLIC_DIR, PUBLIC_FOLDER . '/', $path )) );
			}
		}

		public function 				editing ()
		{
			try {
				if ( !$this->user_middleware->isSignin( $_SESSION ) ) {
					header("Location: /signin");			
				} else {
					$this->viewData["data"] = [
						"gallery" => $this->gallery_model->getAllEditedImages(),
						"userData" => $this->user_model->findUserById( $_SESSION["userid"] ),
						"userGallery" => $this->gallery_model->userGallery( $_SESSION["userid"] ),
						"countUnreadNotifs" => $this->notifications_model->getCountUnreadNotifications( $_SESSION['userid'] )
					];
					if ( $this->helper->isRequestGET( $_SERVER["REQUEST_METHOD"] ) ) {
						$this->viewData["success"] = "true";
					} else if ( $this->helper->isRequestPOST( $_SERVER["REQUEST_METHOD"] ) ) {
						if (
							$this->helper->validate_inputs([
								'token' => [ "REQUIRED" => true, "EMPTY" => false ],
								'btn-save' => [ "REQUIRED" => true, "EMPTY" => false ],
								'dataimage' => [ "REQUIRED" => true, "EMPTY" => false ],
								'sticker' => [ "REQUIRED" => true, "EMPTY" => false ],
								'x' => [ "REQUIRED" => true, "EMPTY" => true ],
								'y' => [ "REQUIRED" => true, "EMPTY" => true ],
								'description' => [ "REQUIRED" => true, "EMPTY" => false ],
							], $_POST)
						) {
							if (
								(
									$_POST = $this->helper->filter_inputs( "POST", array(
										'token' => FILTER_SANITIZE_STRING,
										'btn-save' => FILTER_SANITIZE_STRING,
										'dataimage' => FILTER_SANITIZE_STRING,
										'sticker' => FILTER_SANITIZE_URL,
										'x' => FILTER_SANITIZE_NUMBER_INT,
										'y' => FILTER_SANITIZE_NUMBER_INT,
										'description' => FILTER_SANITIZE_STRING
									))
								) &&
								( $this->user_middleware->validateUserToken( $_POST["token"] ) )
							) {
								if (
									( $error = $this->gallery_middleware->validateSticker( $_POST["sticker"] ) ) ||
									( $error = $this->gallery_middleware->validateDescription( $_POST["description"] ) ) ||
									( $error = $this->gallery_middleware->validateCoordinatesSticker( $_POST["x"], $_POST["y"] ) )
								) {
									$this->viewData["success"] = "false";
									$this->viewData["msg"] = $error;
								} else {
									$imgCamBase64 = str_replace('data:image/png;base64', '', $_POST["dataimage"]);
									$finalImageCam = str_replace(' ', '+', $imgCamBase64);
									$fileData = base64_decode( $finalImageCam );
									$pathFile = EDITEDPICS.'IMG'.'_'.time().'_'.$_SESSION['userid'].'_'.$_SESSION['username'].'.png';
									file_put_contents( $pathFile, $fileData );
									$srcPath = $_POST["sticker"];
									$destPath = $this->transformPathFileToUrl( $pathFile );
									if ( $srcFinaleImg = $this->makeMixedImage( $this->viewData["data"]["userData"], $destPath, $srcPath, intval($_POST["x"]), intval($_POST["y"]) ) ) {
										if ( file_exists( $pathFile ) ) { unlink( $pathFile ); }
										$pathFinaleImg = $this->transformPathFileToUrl( $srcFinaleImg );
										if ( $this->gallery_model->addImage([ 'id' => $_SESSION['userid'], 'src' => $pathFinaleImg, 'description' => $_POST['description'] ]) ) {
											$this->viewData["success"] = "true";
											$this->viewData["msg"] = "Image has been saved successfully !";
											$this->viewData["data"]["gallery"] = $this->gallery_model->getAllEditedImages();
											$this->viewData["data"]["userGallery"] = $this->gallery_model->userGallery( $_SESSION["userid"] );
										} else {
											$this->viewData["success"] = "false";
											$this->viewData["msg"] = "Failed to create final image !";
											$this->viewData["data"]["gallery"] = $this->gallery_model->getAllEditedImages();
											$this->viewData["data"]["userGallery"] = $this->gallery_model->userGallery( $_SESSION["userid"] );
										}
									} else {
										if ( isset( $pathFile ) && file_exists( $pathFile ) ) { unlink( $pathFile ); }
										$this->viewData["success"] = "false";
										$this->viewData["msg"] = "Something goes wrong while create final image !";
									}
								}
							} else {
								$this->viewData = [ "success" => "false", "msg" => "Couldn't edit your picture !" ];
							}
						} else {
							$this->viewData = [ "success" => "false", "msg" => "Something is missing !" ];
						}
					}
				}
			} catch ( Exception $e ) {
				if ( isset( $pathFile ) && file_exists( $pathFile ) ) { unlink( $pathFile ); }
				$this->viewData["success"] = "false";
				$this->viewData["msg"] = "Something goes wrong while edit your picture, try later !";
			}
			$this->call_view( "user" . DIRECTORY_SEPARATOR ."editing", $this->viewData )->render();
		}

		public function					logout()
		{
			try {
				if ( !$this->user_middleware->isSignin( $_SESSION ) ) {
					header("Location: /signin");			
				} else if ( $this->helper->isRequestPOST( $_SERVER["REQUEST_METHOD"] ) ) {
					if (
						( $this->helper->validate_inputs([ 'token' =>  [ "REQUIRED" => true, "EMPTY" => false ] ], $_POST) ) &&
						( $_POST = $this->helper->filter_inputs( "POST", array( 'token' => FILTER_SANITIZE_STRING )) ) &&
						( $this->user_middleware->validateUserToken( $_POST["token"] ) )
					) {
						session_destroy();
						$this->viewData["success"] = "true";
					} else {
						$this->viewData = [ "success" => "false", "msg" => "Couldn't logout !" ];
					}
				}
			} catch ( Exception $e ) {
				$this->viewData["success"] = "false";
				$this->viewData["msg"] = "Something goes wrong while logout, try later !";
			}
			die( json_encode( $this->viewData ) );
		}

	}
?>