<?php
	$data = $this->view_data['data'];
	$userData = $data['userData'];
	$countUnreadNotifs = $data["countUnreadNotifs"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Change password</title>
	<link rel="icon" href="/public/images/logo.png">
	<link rel="stylesheet" href="/public/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="/public/css/user/change_password.css"/>
	<link rel="stylesheet" href="/public/css/_header.css"/>
	<link rel="stylesheet" href="/public/css/_footer.css"/>
</head>
<body onload="getNotifications();">
	<?php require_once(VIEWS . "_header.php");?>
	<div class="container">
		<div class="card offset-lg-2 col-lg-8">
			<div class="card-body">
				<p class="card-title">
					<span>Change password</span>
				</p>
				<div id="icone" class="text-center">
					<img src="/public/images/change-password.png"/></br>
				</div>
				<hr/>
				<div class="col-md-12 px-5 py-4">
					<form action="/user/change_password" method="POST" onsubmit="return validateData();">
						<div class="form-group row m-0 mb-1">
							<label class="col-lg-4" for="inputOldPass">Old password :</label>
							<div class="col-lg-8">
								<input
									type="password"
									class="form-control"
									id="inputOldPass"
									name="oldpassword"
									placeholder="old password"
									autocomplete="off"
									oninput="validatePassword(this)"
								/>
							</div>
						</div>
						<div class="form-group row m-0 mb-1">
							<label class="col-lg-4" for="inputNewPass">New password :</label>
							<div class="col-lg-8">
								<input
									type="password"
									class="form-control"
									id="inputNewPass"
									name="newpassword"
									placeholder="new password"
									autocomplete="off"
									oninput="validatePassword(this)"
								/>
							</div>
						</div>
						<div class="form-group row m-0 mb-1">
							<label class="col-lg-4" for="inputConfirmationPass">Confirmation password :</label>
							<div class="col-lg-8">
								<input
									type="password"
									class="form-control"
									id="inputConfirmationPass"
									name="confirmation_password"
									placeholder="repeat new password"
									autocomplete="off"
									oninput="validateConfPass()"
								/>
							</div>
						</div>
						<div class="row text-center py-2" style="height: 70px;">
							<span id="msg" class="w-100 
									<?php 
										if ( isset( $this->view_data['success'] ) && $this->view_data['success'] == "true" ) { echo "text-success"; }
										else { echo "text-danger"; }
									?>
								">
								<?php if ( isset($this->view_data['msg']) ) echo $this->view_data['msg'];?>
							</span>
						</div>
						<input type="hidden" name="token" value="<?php echo $_SESSION["token"] ?>"/>
						<div class="form-row">
							<input
								type="submit"
								class="offset-2 col-8 btn btn-outline-danger w-50 mt-5"
								value="Submit"
								id="btn-submit"
								name="btn-submit"
							/>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php require_once(VIEWS . "_footer.php"); ?>
</body>
<script type="text/javascript" src="/public/js/_header.js"></script>
<script type="text/javascript" src="/public/js/user/changepassword.js"></script>
</html>