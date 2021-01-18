<?php
	if ( isset( $this->view_data['data'] ) && !empty( $this->view_data['data'] ) ) {
		$token = ( isset( $this->view_data['data']['token'] ) ) ? $this->view_data['data']['token'] : null;
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" href="/public/images/logo.png">
		<link rel="stylesheet" href="/public/css/bootstrap.min.css"/>
		<link rel="stylesheet" href="/public/css/home/account_confirmation.css"/>
		<link rel="stylesheet" href="/public/css/_footer.css"/>
		<title>Account confirmation</title>
		<noscript>
			<p class="text-white">We're sorry but the application doesn't work properly without JavaScript enabled. Please enable it to continue.</p>
			<style>
				header { display: none; }
				div { display:none; }
				footer { display: none; }
			</style>
		</noscript>
	</head>
	<body>
		<div class="container">
			<div class="card offset-lg-2 col-lg-8">
				<div class="card-body">
					<p class="card-title">
						<span>Account confirmation</span>
					</p>
					<div id="icone" class="text-center">
						<img src="/public/images/verified-account.png"/></br>
					</div>
					<hr/>
					<div class="col-md-12 px-5 py-4 text-center" style="font-size: 16pt;">
						<span id="msg" class="w-100 <?php echo ( isset( $this->view_data['success'] ) && $this->view_data['success'] == "true" ) ? "text-success" : "text-danger"; ?>">
							<?php if ( isset($this->view_data['msg']) ) echo $this->view_data['msg']; ?>
						</span>
					</div>
				</div>
				<div class="card-footer w-100">
					<a href="/home" id="link-home" class="text-muted float-left">Home</a>
					<a href="/signin" id="link-signin" class="text-muted float-right">Sign in</a>
				</div>
			</div>
		</div>
		<?php require_once(VIEWS . "_footer.php"); ?>
	</body>
</html>