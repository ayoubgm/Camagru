<?php
	if ( !isset($_SESSION['userid']) ) {
		header("Location: /home");
	} else {
		$data = $this->view_data['data'];
		$userData = $data['userData'];
		$userGallery = $data['userGallery'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Change password</title>
	<link rel="icon" href="/public/images/logo.png">
	<link rel="stylesheet" href="/public/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="/public/css/change_password.css"/>
	<link rel="stylesheet" href="/public/css/_header.css"/>
	<link rel="stylesheet" href="/public/css/_footer.css"/>
</head>
<body>
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
					<form action="<?php echo SERVER; ?>/user/change_password" method="POST" onsubmit="return validateData();">
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
						<div class="form-row">
							<input
								type="submit"
								class="offset-2 col-8 btn btn-outline-danger w-50"
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
<script src="/public/js/_menu.js"></script>
<script src="/public/js/_userMenu.js"></script>
<script>
	const buttonSubmit = document.querySelector('#btn-submit');
	const msg = document.getElementById("msg");
	const btn_profile = document.querySelector("nav .btn-auth #profile-img");
	const oldpassword = document.getElementById('inputOldPass');
	const newpassword = document.getElementById('inputNewPass');
	const confirmationPassword = document.getElementById('inputConfirmationPass');

	const 			setError = ( target, msgerror ) => {
		target.style.border = "1px solid red";
		msg.classList.add("text-danger");
		msg.classList.remove("text-success");
		msg.innerHTML = msgerror;
	}
	const 			setSuccess = ( target ) => {
		msg.innerHTML = "";
		msg.classList.remove("text-danger");
		msg.classList.add("text-success");
		target.style.border = "1px solid green";
	}
	const 			validatePassword = ( password ) => {
		if ( !/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*_-]).{8,}$/.test( password.value ) ) {
			setError(password, "The password should be minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character !");
			return false;
		} else {
			setSuccess(password);
			return true;
		}
	}
	const			validateConfPass = ( ) => {
		if ( newpassword.value !== confirmationPassword.value ) {
			setError(confirmationPassword, "Passwords doesn't match");
			return false;
		} else {
			setSuccess(confirmationPassword);
			return true;
		}
	}
	
</script>
</html>
<?php
	}
?>