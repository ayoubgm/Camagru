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

		static public function		validateData( $obj, $id )
		{
			if ( !isset( $_SESSION['userid'] ) ) {
				return "You need to login first !";
			} else if ( !$obj->gallery_middleware->isImageExist( $id ) ) {
				return "The image is not found !";
			} else {
				return NULL;
			}
		}

		public function				add()
		{
			try {
				if ( !$this->user_middleware->isSignin( $_SESSION ) ) {
					$this->viewData = [ "success" => "false", "msg" => "You need to login first !" ];
				} else if ( $this->helper->isRequestPOST( $_SERVER["REQUEST_METHOD"] ) ) {
					if ( $_POST = $this->helper->filter_inputs( "POST", array(
							'token' => FILTER_SANITIZE_STRING,
							'id' => FILTER_SANITIZE_NUMBER_INT,
							'comment' => FILTER_SANITIZE_STRING
						))
					) {
						if ( ( isset( $_POST["token"] ) && !empty( $_POST["token"] ) ) && $this->user_middleware->validateUserToken( $_POST["token"] ) ) {
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
						}
					} else {
						$this->viewData = [ "success" => "false", "msg" => "Couldn't add the comment, try later !" ];
					}
				}
			} catch ( Exception $e ) {
				$this->viewData = [ "success" => "false", "msg" => "Something is wrong while save the comment, try later !" ];
			}
			die( json_encode( $this->viewData ) );
		}

		public function				commentsImg( $data )
		{
			try {
				if ( !isset( $data ) || ( !isset( $data[0] ) || $data[0] != "id" ) || ( !isset( $data[1] ) || empty( $data[1] ) ) ) {
					$this->viewData = [
						"success" => "false",
						"msg" => "Something went wrong while validate the image !"
					];
				} else if ( !$this->gallery_middleware->isImageExist( $data[1] ) ) {
					$this->viewData = [
						"success" => "false",
						"msg" => "The image is not found !"
					];
				} else {
					$comments = $this->comment_model->getCommentsOfImg( $data[1] );
					foreach( $comments as $k => $v ) {
						$comments[ $k ] += [ "momments" => $this->helper->getMomentOfDate( $v["createdat"] ) ];
					}
					$this->viewData = [
						"success" => "true",
						"data" => $comments,
						"countlikes" => $this->like_model->getCountLikes( $data[1] )
					];
				}
			} catch ( Exception $e ) {
				$this->viewData = [
					"success" => "false",
					"msg" => "Something is wrong while load comments of the image !"
				];
			}
			die( json_encode( $this->viewData ) );
		}

		public function				delete ()
		{
			try {
				if ( !$this->user_middleware->isSignin( $_SESSION ) ) {
					$this->viewData = [ "success" => "false", "msg" => "You need to login first !" ];
				} else if ( $this->helper->isRequestPOST( $_SERVER["REQUEST_METHOD"] ) ) {
					if ( $_POST = $this->helper->filter_inputs( "POST", array(
							'token' => FILTER_SANITIZE_STRING,
							'id' => FILTER_SANITIZE_NUMBER_INT
						))
					) {
						if ( ( isset( $_POST["token"] ) && !empty( $_POST["token"] ) ) && $this->user_middleware->validateUserToken( $_POST["token"] ) ) {
							if ( $error = $this->comment_middleware->delete( $_POST["id"] ) ) {
								$this->viewData = [ "success" => "false", "msg" => $error ];	
							} else if ( $this->comment_model->delete( $_POST["id"] ) ) {
								$this->viewData = [ "success" => "true", "msg" => "The comment deleted successfully !" ];
							} else {
								$this->viewData = [ "success" => "false", "msg" => "Failed to delete the comment successfully !" ];
							}
						}
					}
				} else {
					$this->viewData = [ "success" => "false", "msg" => "Couldn't delete the comment, try later !" ];
				}
			} catch ( Exception $e ) {
				$this->viewData = [ "success" => "false", "msg" => "Something is wrong while delete the comment, try later !" ];
			}
			die( json_encode( $this->viewData ) );
		}

	}

?>