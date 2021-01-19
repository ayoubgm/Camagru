<?php
	/**
	 * 	Gallery middlewares class 
	 */
	class GalleryMiddleware extends Middleware
	{

		public function					validateCoordinatesSticker ( $x, $y )
		{
			if ( ( isset( $_POST["x"] ) && intval( $_POST["x"] ) < 0 ) || ( isset( $_POST["y"] ) && intval( $_POST["y"] ) < 0 ) ) {
				return "Coordinates of the sticker must be positive";
			}
		}

		public function					validateDescription ( $description )
		{
			return $this->validateImageDescription( $description );
		}

		public function 				isImageOwnerExist( $userid, $imgid )
		{
			return ( $data = $this->isImageOwnerExists( $userid, $imgid ) ) ? $data : false;
		}

		public function 				isImageExist( $imgid )
		{
			return ( $data = $this->isImageExists( $imgid ) ) ? $data : false;
		}

	}
?>