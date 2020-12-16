<?php

	/**
	 * 	gallery middlewares class 
	 */
	class CommentMiddleware extends Middleware
	{

		public function         add ( $comment )
		{
			if ( empty( $comment ) ) { return "Should not be empty !"; }
			else if ( strlen( $comment ) > 255 ) { return "The comment is too long !"; }
			else if ( !preg_match( "/^[a-zA-Z0-9 ]{1,255}$/", $comment ) ) { return "The comment should contains only letters or numbers !"; }
			else { return NULL; }
		}

		public function			delete ( $id )
		{
			return ( ! $this->isCommentExists( $id ) )
			? "The comment is not found !"
			: NULL ;
		}

	}

?>