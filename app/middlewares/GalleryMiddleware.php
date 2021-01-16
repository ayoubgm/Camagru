<?php
	/**
	 * 	Gallery middlewares class 
	 */
	class GalleryMiddleware extends Middleware
	{

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