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
				$this->call_view( 'user' . DIRECTORY_SEPARATOR .'profile', ['data' => $userData ])->render();
			} else {
				header("Location: /camagru_git/home");
			}
		}
		
		public function		edit ()
		{
			if ( isset( $_SESSION['userid'] ) ) {
				$userData = $this->call_model('UserModel')->findUserById( $_SESSION['userid'] );
				switch( $_SERVER['REQUEST_METHOD'] ) {
					case 'GET':
						$this->call_view( 'user' . DIRECTORY_SEPARATOR .'edit_infos', ['data' => $userData ])->render();
					break;
					case 'POST':
						if ( isset($_POST['btn-edit']) ) {
							$userID = $userData['id'];
							unset( $_POST['btn-edit'] ); unset( $userData['id'] ); unset( $userData['createdat'] );
							$editedData = array_replace_recursive( $userData, $_POST );
							if ( ($error = $this->call_middleware('UserMiddleware')->edit( $userID, $editedData )) != null){
								$this->call_view('user' . DIRECTORY_SEPARATOR .'edit_infos', [ 'success' => "false", 'msg' => $error, 'data' => $editedData ])->render();
							} else {
								try {
									if ( $this->call_model('UserModel')->edit( $userID, $editedData ) ) {
										$newData = $this->call_model('UserModel')->findUserById( $_SESSION['userid'] );
										$this->call_view(
											'user' . DIRECTORY_SEPARATOR .'edit_infos',
											[
												'success' => "true",
												'msg' => "Your informations has been edited successfully !",
												'data' => $newData
											]
										)->render();
									} else {
										$this->call_view(
											'user' . DIRECTORY_SEPARATOR .'edit_infos',
											[
												'success' => "false",
												'msg' => "Failed to update your informations !",
												'data' => $userData
											]
										)->render();
									}
								} catch( Exception $e ) {
									$this->call_view(
										'user' . DIRECTORY_SEPARATOR .'edit_infos',
										[
											'success' => "false",
											'msg' => "Something goes wrong while edit informations, try later !",
											'data' => $userData
										]
									)->render();
								}
							}
						}
					break;
				}
			} else {
				header("Location: /camagru_git/home");
			}
		}

		public function		settings ()
		{
			if ( isset( $_SESSION['userid'] ) ) {
				$this->call_view( 'user' . DIRECTORY_SEPARATOR .'settings', )->render();
			} else {
				header("Location: /camagru_git/home");
			}
		}

		public function		change_password ()
		{
			if ( isset( $_SESSION['userid'] ) ) {
				switch( $_SERVER['REQUEST_METHOD'] ) {
					case 'GET':
						$this->call_view( 'user' . DIRECTORY_SEPARATOR .'change_password', )->render();
					break;
					case 'POST':
						if ( isset($_POST['btn-submit']) ) {
							unset( $_POST['btn-submit'] );
							if ( ( $error = $this->call_middleware('UserMiddleware')->change_password( $_SESSION['userid'], $_POST ) ) != null ) {
								$this->call_view( 'user' . DIRECTORY_SEPARATOR .'change_password', [ 'success' => "false", 'msg' => $error ] )->render();
							} else {
								try {
									if ( $this->call_model('UserModel')->change_password( $_SESSION['userid'], password_hash($_POST['newpassword'], PASSWORD_ARGON2I) ) ) {
										$this->call_view('user' . DIRECTORY_SEPARATOR .'change_password', [ 'success' => "true", 'msg' => "Your password has been changed successfully" ] )->render();
									} else {
										$this->call_view('user' . DIRECTORY_SEPARATOR .'change_password', [ 'success' => "false", 'msg' => "Failed to change your password !" ])->render();
									}
								} catch ( Exception $e ) {
									$this->call_view('user' . DIRECTORY_SEPARATOR .'change_password', [ 'success' => "false", 'msg' => "Something goes wrong while changing your password !" ])->render();
								}
							}
						}
					break;
				}
			} else {
				header("Location: /camagru_git/home");
			}
		}

		public function		notifications_preferences ( $data )
		{
			if ( isset( $_SESSION['userid'] ) ) {
				$userData = $this->call_model('UserModel')->findUserById( $_SESSION['userid'] );
				
				if ( isset( $data[0] ) && isset( $data[1] ) ) {
					if (
						( isset( $data[0] ) && $data[0] === "notificationsemail" ) &&
						( isset( $data[1] ) && ( $data[1] !== "1" || $data[1] !== "0" ) )
					) {
						switch( $_SERVER['REQUEST_METHOD'] ) {
							case 'GET':
								$this->call_view( 'user' . DIRECTORY_SEPARATOR .'notifications_preferences', [ 'data' => $userData ])->render();
							break;
							case 'POST':
								if ( isset( $_POST['btn-change-preference'] ) ) {
									unset( $_POST['btn-change-preference'] );
									try {
										if ( $this->call_model('UserModel')->change_preference_email_notifs( $_SESSION['userid'], $data[1] ) ) {
											$userData = $this->call_model('UserModel')->findUserById( $_SESSION['userid'] );
											$this->call_view( 'user' . DIRECTORY_SEPARATOR .'notifications_preferences', [ 'data' => $userData ])->render();
										} else {
											$this->call_view(
												'user' . DIRECTORY_SEPARATOR .'notifications_preferences',
												[ 'success' => "false", 'msg' => "Failed to change your notifications preference !", 'data' => $userData ]
											)->render();
										}
									} catch ( Exception $e ) {
										$this->call_view(
											'user' . DIRECTORY_SEPARATOR .'notifications_preferences',
											[ 'success' => "false", 'msg' => "Something is wrong, try later !", 'data' => $userData ]
										)->render();
									}
								}
							break;
						}
					}
					else {
						$this->call_view( 'user' . DIRECTORY_SEPARATOR .'notifications_preferences', [ 'data' => $userData ])->render();
					}
				} else {
					$this->call_view( 'user' . DIRECTORY_SEPARATOR .'notifications_preferences', [ 'data' => $userData ])->render();
				}
			} else {
				header("Location: /camagru_git/home");
			}
		}

		public function		editing ()
		{
			if ( isset( $_SESSION['userid'] ) ) {
				$userData = $this->call_model('UserModel')->findUserById( $_SESSION['userid'] );
				$userGallery = $this->call_model('GalleryModel')->userGallery( $userData['id'] );
				switch( $_SERVER['REQUEST_METHOD'] ) {
					case 'GET':
						$this->call_view(
							'user' . DIRECTORY_SEPARATOR .'editing',
							[ 'data' => [ 'userGallery' => $userGallery ] ]
						)->render();
					break;
					case 'POST':
						if ( isset( $_POST['btn-save'] ) ) {
							$_POST['id'] = $userData['id'];
							$width = 640;
							$height = 480;
							$x = intval($_POST);
							$y = 480;
							$imgWebcam = $_POST['dataimage'];
							$imgWebcam = str_replace('data:image/png;base64', '', $imgWebcam);
							$imgWebcam = str_replace(' ', '+', $imgWebcam);
							$fileData = base64_decode( $imgWebcam );
							$pathFile = EDITEDPICS .'IMG'.'_'.$userData['id'].'_'.$userData['username'].'_'.time().'.png';
							file_put_contents($pathFile, $fileData);
							$srcPath = $_POST['sticker'];
							$destPath = str_replace('\\\\', '//', str_replace('\\', '/', str_replace( PUBLIC_DIR, PUBLIC_FOLDER . '/', $pathFile ) ) );
							$dest = imagecreatefrompng( $destPath ); 
							$src = imagecreatefrompng( $srcPath);
							// Get new sizes
							list($width, $height) = getimagesize($srcPath);
							// Copy and merge 
							imagecopyresized($dest, $src, intval($_POST['x']), intval($_POST['y']), 0, 0, 150, 150, $width, $height);
							// Output and free from memory 
							imagejpeg($dest, EDITEDPICS .'IMG'.'_'.$userData['id'].'_'.$userData['username'].'_'.time().'.png'); 							  
							imagedestroy($dest);
							imagedestroy($src);
							try {
								if ( $this->call_model('GalleryModel')->addImage([ 'id' => $_SESSION['userid'], 'src' => $destPath ]) ) {
									$userGallery = $this->call_model('GalleryModel')->userGallery( $userData['id'] );
									$this->call_view(
										'user' . DIRECTORY_SEPARATOR .'editing',
										[
											'success' => "true",
											'msg' => "Image has been saved successfully !",
											'data' => [ 'userGallery' => $userGallery ]
										]
									)->render();
								} else {
									$this->call_view(
										'user' . DIRECTORY_SEPARATOR .'editing',
										[ 'success' => "false", 'msg' => "Failed to edit snapchat !", 'data' => [ 'userGallery' => $userGallery ] ]
									)->render();
								}
							} catch ( Exception $e ) {
								$this->call_view(
									'user' . DIRECTORY_SEPARATOR .'editing',
									[ 'success' => "false", 'msg' => "Something goes wrong, try later !", 'data' => [ 'userGallery' => $userGallery ] ]
								)->render();
							}
						}
					break;
				}
			} else {
				header("Location: /camagru_git/signin");
			}
		}

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