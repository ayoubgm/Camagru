<?php

	class helper {

		public function 	sendMail ( $subject, $to, $token )
		{
			$headers = 'Content-type: text/html;';
			$headers .= 'From: admin'."\r\n".
						'Reply-To: admin'."\r\n" .
						'X-Mailer: PHP/' . phpversion();
			$message = '<html><body>';
			switch ( $subject ) {
				case "Confirmation mail":
					$message = "<span>Hello, </span></br>";
					$message .= "<p>almost done, we're happy you're here, to complete the signup process let's get your email address verified </p></br>";
					$message .= "<p>Click on the direct link: <a href='" . SERVER . "/account_confirmation/token/" . $token."'>Confirm your account</a></p>";
				break;
				case "Reset password":
					$message =  "<span>Hello, </span></br>";
					$message .= "<p>Its seems that your request a reset password link, you can change your password with the link below: </p></br>";
					$message .= "<p><a href='" . SERVER . "/new_password/token/" . $token."'>Change your password</a></p>";
				break;
			}
			$message .= '</body></html>';
			mail($to, $subject, $message, $headers);
		}

		public function			getMomentOfDate ( $date )
		{
			$gmtTimezone = new DateTimeZone('GMT+1');
			$creatDate = new DateTime( $date, $gmtTimezone );
			$currDate = new DateTime("now", $gmtTimezone);
			$interval = date_diff( $currDate, $creatDate );
			$string = "0 sec";

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

	}

?>