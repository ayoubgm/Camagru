<?php
	class User extends Controller {

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
								$this->call_view(
									'user' . DIRECTORY_SEPARATOR .'edit_infos',
									[ 'success' => "false", 'msg' => $error ]
								)->render();
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