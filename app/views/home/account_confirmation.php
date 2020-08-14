<?php
	if ( isset( $this->view_data['data'] ) && !empty( $this->view_data['data'] ) ) {
		$data = $this->view_data['data'];
		$token = ( isset( $data['token'] ) ) ? $data['token'] : null;
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Account confirmation</title>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/account_confirmation.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/_header.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/_footer.css"/>
</head>
<body>
	<?php require_once(VIEWS . "_header.php");?>
	<div class="container">
		<div class="card offset-lg-2 col-lg-8">
			<div class="card-body">
				<p class="card-title">
					<span>Account confirmation</span>
				</p>
				<div id="icone" class="text-center">
					<img src="<?php echo PUBLIC_FOLDER; ?>/images/verified-account.png"/></br>
				</div>
				<hr/>
				<div class="col-md-12 px-5 py-4 text-center" style="font-size: 16pt;">
					<span id="msg" class="w-100 
							<?php 
								if ( isset( $this->view_data['success'] ) && $this->view_data['success'] == "true" ) { echo "text-success"; }
								else { echo "text-danger"; }
							?>
						">
						<?php if ( isset($this->view_data['msg']) ) echo $this->view_data['msg'];?>
					</span>
				</div>
			</div>
		</div>
	</div>
	<?php require_once(VIEWS . "_footer.php"); ?>
</body>
<script>
	const msg = document.getElementById("msg");
	const menu = document.querySelector("nav .btn-auth .dropdown");
	const newpassword = document.getElementById('inputNewPass');
	const confirmationPassword = document.getElementById('inputConfirmationPass');
    
    const showMenu = () => {
		if ( menu.style.display == "none" ) { menu.style.display = "block"; }
		else { menu.style.display = "none"; }
	};
	
</script>
</html>