<?php

	/**
	 * 	Comment controller class
	 */
	class commentController extends Controller
	{

		public function				__construct()
		{
			parent::__construct();
			session_start();
		}

		public function				add()
		{
			try {
				if ( !$this->user_middleware->isSignin( $_SESSION ) ) {
					$this->viewData = [ "success" => "false", "msg" => "You need to login first !" ];
				} else if ( $this->helper->isRequestPOST( $_SERVER["REQUEST_METHOD"] ) ) {
					if (
						$this->helper->validate_inputs([
							'token' => [ "REQUIRED" => true, "EMPTY" => false ],
							'id' => [ "REQUIRED" => true, "EMPTY" => false ],
							'comment' => [ "REQUIRED" => true, "EMPTY" => false ]
						], $_POST )
					) {
						if (
							(
								$_POST = $this->helper->filter_inputs( "POST", array(
									'token' => FILTER_SANITIZE_STRING,
									'id' => FILTER_SANITIZE_NUMBER_INT,
									'comment' => FILTER_SANITIZE_STRING
								))
							) &&
							$this->user_middleware->validateUserToken( $_POST["token"] )
						) {
							if ( !$this->gallery_middleware->isImageExist( $_POST["id"] ) ) {
								$this->viewData = [ "success" => "false", "msg" => "The image is not found !" ];	
							} else if ( $error = $this->comment_middleware->add( $_POST['comment'] ) ) {
								$this->viewData = [ "success" => "false", "msg" => $error ];
							} else if ( $id = $this->comment_model->save([ "content" => $_POST['comment'], "userid" => $_SESSION['userid'], "imgid" => $_POST["id"] ]) ) {
								$this->viewData = [
									"success" => "true",
									"data" => $this->comment_model->getComment( $id ),
									"msg" => "Added successfully !"
								];
							} else {
								$this->viewData = [ "success" => "false", "msg" => "Failed to save the comment !" ];
							}
						} else {
							$this->viewData = [ "success" => "false", "msg" => "Couldn't add the comment, try later !" ];
						}
					} else {
						$this->viewData = [ "success" => "false", "msg" => "Something is missing !" ];
					}
				}
			} catch ( Exception $e ) {
				$this->viewData = [ "success" => "false", "msg" => "Something is wrong while save the comment, try later !" ];
			}
			die( json_encode( $this->viewData ) );
		}

		public function				commentsImg()
		{
			try {
				if ( $this->helper->isRequestGET( $_SERVER["REQUEST_METHOD"] ) ) {
					if (
						( $this->helper->validate_inputs([ 'id' => [ "REQUIRED" => true, "EMPTY" => false ] ], $_GET ) ) &&
						( $_GET = $this->helper->filter_inputs( "GET", array( 'id' => FILTER_SANITIZE_NUMBER_INT )) )
					) {
						if ( !$this->gallery_middleware->isImageExist( $_GET['id'] ) ) {
							$this->viewData = [ "success" => "false", "msg" => "The image is not found !" ];
						} else {
							$comments = $this->comment_model->getCommentsOfImg( $_GET['id'] );
							foreach( $comments as $k => $v ) { $comments[ $k ] += [ "momments" => $this->helper->getMomentOfDate( $v["createdat"] ) ]; }
							$this->viewData = [ "success" => "true", "data" => $comments, "countlikes" => $this->like_model->getCountLikes( $_GET['id'] ) ];
						}
					} else {
						$this->viewData = [ "success" => "false", "msg" => "Couldn't load comments of the image, try later !" ];
					}
				}
			} catch ( Exception $e ) {
				$this->viewData = [ "success" => "false", "msg" => "Something is wrong while load comments of the image, try later !" ];
			}
			die( json_encode( $this->viewData ) );
		}

		public function				delete ()
		{
			try {
				if ( !$this->user_middleware->isSignin( $_SESSION ) ) {
					$this->viewData = [ "success" => "false", "msg" => "You need to login first !" ];
				} else if ( $this->helper->isRequestPOST( $_SERVER["REQUEST_METHOD"] ) ) {
					if (
						(
							$this->helper->validate_inputs([
								'token' => [ "REQUIRED" => true, "EMPTY" => false ],
								'id' => [ "REQUIRED" => true, "EMPTY" => false ]
							], $_POST )
						) &&
						(
							$_POST = $this->helper->filter_inputs( "POST", array(
								'token' => FILTER_SANITIZE_STRING,
								'id' => FILTER_SANITIZE_NUMBER_INT
							))
						) &&
						$this->user_middleware->validateUserToken( $_POST["token"] )
					) {
						if ( $error = $this->comment_middleware->delete( $_POST["id"] ) ) {
							$this->viewData = [ "success" => "false", "msg" => $error ];	
						} else if ( $this->comment_model->delete( $_POST["id"] ) ) {
							$this->viewData = [ "success" => "true", "msg" => "The comment deleted successfully !" ];
						} else {
							$this->viewData = [ "success" => "false", "msg" => "Failed to delete the comment successfully !" ];
						}
					} else {
						$this->viewData = [ "success" => "false", "msg" => "Couldn't delete the comment, try later !" ];
					}
				}
			} catch ( Exception $e ) {
				$this->viewData = [ "success" => "false", "msg" => "Something is wrong while delete the comment, try later !" ];
			}
			die( json_encode( $this->viewData ) );
		}

	}

?>