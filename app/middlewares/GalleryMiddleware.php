<?php
	class   	GalleryMiddleware  extends Middleware
	{

		public function		isImageOwnerExist( $userid, $imgid )
		{
			return ( $data = $this->isImageOwnerExists( $userid, $imgid ) ) ? $data : false;
		}

		public function		isImageExist( $imgid )
		{
			return ( $data = $this->isImageExists( $imgid ) ) ? $data : false;
		}

	}
?>