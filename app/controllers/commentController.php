<?php

	/**
	 * 	comment controller class
	 */
	class commentController extends Controller
	{

		private $viewData;
		private $commentModel;
		private $galleryMiddleware;
		private $commentMiddleware;
		
		public function				__construct()
		{
			session_start();
			$this->viewData = array();
			$this->commentModel = $this->call_model('CommentsModel');
			$this->galleryMiddleware = $this->call_middleware('GalleryMiddleware');
			$this->commentMiddleware = $this->call_middleware('CommentMiddleware');
		}

		static public function		validateData( $obj, $data )
		{
			if ( !isset( $_SESSION['userid'] ) ) {
				return "You need to login first !";
			} else if ( !isset( $data ) || ( !isset( $data[0] ) || $data[0] != "id" ) || ( !isset( $data[1] ) || empty( $data[1] ) ) ) {
				return "Something went wrong while validate the image !";
			} else if ( !$obj->galleryMiddleware->isImageExist( $data[1] ) ) {
				return "The image is not found !";
			} else {
				return NULL;
			}
		}

		static public function			getMomentOfDate( $date )
		{
			$gmtTimezone = new DateTimeZone('GMT+1');
			$creatDate = new DateTime( $date, $gmtTimezone );
			$currDate = new DateTime("now", $gmtTimezone);
			$interval = date_diff( $currDate, $creatDate );
			$string = "";

			if ( $interval->format('%Y') > 0 ) {
				$string = $interval->format('%Y').", ".$interval->format('%d')." ".strtolower( $interval->format('%F') )." at ".$interval->format('%H:%m');
			} else if ( $interval->format('%m') > 0 && $interval->format('%m') > 7 ) {
				$string = $interval->format('%d')." ".strtolower( $interval->format('%F') )." at ".$interval->format('%H:%m');
			} else if ( $interval->format('%d') >= 1 ) {
				$string = $interval->format('%d')." d";
			} else if ( $interval->format('%H') >= 1 && $interval->format('%H') <= 24 ) {
				$string = $interval->format('%h')." h";
			} else if ( $interval->format('%i') >= 1 && $interval->format('%i') <= 60 ) {
				$string = $interval->format('%i')." min";
			} else if ( $interval->format('%s') >= 1 && $interval->format('%s') <= 60 ) {
				$string = $interval->format('%s')." sec";
			}
			return $string;
		}

		public function				add( $data )
		{
			try{
				if ( $error = $this->validateData( $this, $data ) ) {
					$this->viewData = [ "success" => "false", "msg" => $error ];
				} else {
					switch ( $_SERVER["REQUEST_METHOD"] ) {
						case "POST":
							if ( $error = $this->commentMiddleware->add( $_POST['comment'] ) ) {
								$this->viewData = [ "success" => "false", "msg" => $error ];
							} else if ( $id = $this->commentModel->save([
								"content" => $_POST['comment'],
								"userid" => $_SESSION['userid'],
								"imgid" => $data[1]
							]) ) {
								$this->viewData = [
									"success" => "true",
									"msg" => "Added successfully !",
									"data" => $this->commentModel->getComment( $id )
								];
							} else {
								$this->viewData = ["success" => "false", "msg" => "Failed to save the comment !"];
							}
						break;
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
				if ( $error = $this->validateData( $this, $data ) ) {
					$this->viewData = [ "success" => "false", "msg" => $error ];
				} else {
					$comments = $this->commentModel->getCommentsOfImg( $data[1] );
					foreach( $comments as $k => $v ) { $comments[ $k ] += [ "momments" => self::getMomentOfDate( $v["createdat"] ) ]; }
					$this->viewData = [ "success" => "true", "data" => $comments ];
				}
			} catch ( Exception $e ) {
				$this->viewData = ["success" => "false", "msg" => "Something is wrong while load comments of the image !"];
			}
			die( json_encode( $this->viewData ) );
		}

		public function				delete ( $data )
		{
			if ( !isset( $_SESSION['userid'] ) ) {
				return "You need to login first !";
			} else if ( !isset( $data ) || ( !isset( $data[0] ) || $data[0] != "id" ) || ( !isset( $data[1] ) || empty( $data[1] ) ) ) {
				return "Something went wrong while validate the comment !";
			} else {
				try {
					if ( $error = $this->commentMiddleware->delete( $data[1] ) ) {
						$this->viewData = [ "success" => "false", "msg" => $error ];	
					} else if ( $this->commentModel->delete( $data[1] ) ) {
						$this->viewData = [ "success" => "true", "msg" => "The comment deleted successfully !" ];
					} else {
						$this->viewData = [ "success" => "false", "msg" => "Failed to delete the comment successfully !" ];
					}
				} catch ( Exception $e ) {
					$this->viewData = ["success" => "false", "msg" => "Something is wrong while delete the comment !"];
				}
			}
			die( json_encode( $this->viewData ) );
		}

	}

?>