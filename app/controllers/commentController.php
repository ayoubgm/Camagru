<?php

	/**
	 * 	comment controller class
	 */
	class commentController extends Controller
	{

		private $viewData;
		private $commentModel;
		private $userMiddleware;
		private $galleryMiddleware;
		private $commentMiddleware;
		private $helper;
		
		public function				__construct()
		{
			session_start();
			$this->viewData = array();
			$this->commentModel = $this->call_model('CommentsModel');
			$this->userMiddleware = self::call_middleware('UserMiddleware');
			$this->galleryMiddleware = $this->call_middleware('GalleryMiddleware');
			$this->commentMiddleware = $this->call_middleware('CommentMiddleware');
			$this->helper = $this->call_helper();
		}

		static public function		validateData( $obj, $id )
		{
			if ( !isset( $_SESSION['userid'] ) ) {
				return "You need to login first !";
			} else if ( !$obj->galleryMiddleware->isImageExist( $id ) ) {
				return "The image is not found !";
			} else {
				return NULL;
			}
		}

		public function				add()
		{
			try {
				if ( !$this->userMiddleware->isSignin( $_SESSION ) ) {
					$this->viewData = [ "success" => "false", "msg" => "You need to login first !" ];	
				} else if ( ( isset( $_POST["token"] ) && !empty( $_POST["token"] ) ) && $this->userMiddleware->validateUserToken( $_POST["token"] ) ) {
					if ( !$this->galleryMiddleware->isImageExist( $_POST["id"] ) ) {
						$this->viewData = [ "success" => "false", "msg" => "The image is not found !" ];	
					} else if ( $error = $this->commentMiddleware->add( $_POST['comment'] ) ) {
						$this->viewData = [ "success" => "false", "msg" => $error ];
					} else if ( $id = $this->commentModel->save([ "content" => $_POST['comment'], "userid" => $_SESSION['userid'], "imgid" => $_POST["id"] ]) ) {
						$this->viewData = [
							"success" => "true",
							"msg" => "Added successfully !",
							"data" => $this->commentModel->getComment( $id )
						];
					} else {
						$this->viewData = ["success" => "false", "msg" => "Failed to save the comment !"];
					}
				}
			} catch ( Exception $e ) {
				$this->viewData = ["success" => "false", "msg" => "Something is wrong while save the comment !"];
			}
			die( json_encode( $this->viewData ) );
		}

		public function				commentsImg( $data )
		{
			try {
				if ( !isset( $data ) || ( !isset( $data[0] ) || $data[0] != "id" ) || ( !isset( $data[1] ) || empty( $data[1] ) ) ) {
					$this->viewData = [ "success" => "false", "msg" => "Something went wrong while validate the image !" ];
				} else if ( !$this->galleryMiddleware->isImageExist( $data[1] ) ) {
					$this->viewData = [ "success" => "false", "msg" => "The image is not found !" ];
				} else {
					$comments = $this->commentModel->getCommentsOfImg( $data[1] );
					foreach( $comments as $k => $v ) { $comments[ $k ] += [ "momments" => $this->helper->getMomentOfDate( $v["createdat"] ) ]; }
					$this->viewData = [ "success" => "true", "data" => $comments ];
				}
			} catch ( Exception $e ) {
				$this->viewData = ["success" => "false", "msg" => "Something is wrong while load comments of the image !"];
			}
			die( json_encode( $this->viewData ) );
		}

		public function				delete ()
		{
			if ( !$this->userMiddleware->isSignin( $_SESSION ) ) {
				$this->viewData = [ "success" => "false", "msg" => "You need to login first !" ];	
			} else {
				if ( ( isset( $_POST["token"] ) && !empty( $_POST["token"] ) ) && $this->userMiddleware->validateUserToken( $_POST["token"] ) ) {
					try {
						if ( $error = $this->commentMiddleware->delete( $_POST["id"] ) ) {
							$this->viewData = [ "success" => "false", "msg" => $error ];	
						} else if ( $this->commentModel->delete( $_POST["id"] ) ) {
							$this->viewData = [ "success" => "true", "msg" => "The comment deleted successfully !" ];
						} else {
							$this->viewData = [ "success" => "false", "msg" => "Failed to delete the comment successfully !" ];
						}
					} catch ( Exception $e ) {
						$this->viewData = ["success" => "false", "msg" => "Something is wrong while delete the comment !"];
					}
				}
			}
			die( json_encode( $this->viewData ) );
		}

	}

?>