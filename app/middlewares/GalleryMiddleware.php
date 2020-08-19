<?php
	class   	GalleryMiddleware  extends Middleware
	{

		public function		isImageOwnerExist( $userid, $imgid )
		{
			return ( $data = $this->isImageOwnerExists( $userid, $imgid ) ) ? $data : false;
		}

	}
?>