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
	<title>New password</title>
	<link rel="icon" href="/public/images/logo.png">
	<link rel="stylesheet" href="/public/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="/public/css/new_password.css"/>
	<link rel="stylesheet" href="/public/css/_footer.css"/>
</head>
<body>
	<div class="container">
		<div class="card offset-lg-2 col-lg-8 w-100">
			<div class="card-body">
				<p class="card-title">
					<span>New password</span>
				</p>
				<div id="icone" class="text-center">
					<img src="/public/images/change-password.png"/></br>
				</div>
				<hr/>
				<div class="col-md-12 px-5 py-4">
					<form action="/home/new_password/token/<?php echo $token; ?>" method="POST" onsubmit="return validateData();">
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
							<span id="msg" class="w-100 <?php echo ( isset( $this->view_data['success'] ) && $this->view_data['success'] == "true" ) ? "text-success" : "text-danger"; ?> ">
								<?php if ( isset($this->view_data['msg']) ) echo $this->view_data['msg'];?>
							</span>
						</div>
						<div class="form-row">
							<input
								type="submit"
								class="offset-2 col-8 btn btn-outline-danger w-50 mb-5"
								value="Submit"
								id="btn-submit"
								name="btn-submit"
							/>
						</div>
					</form>
				</div>
			</div>
			<div class="card-footer w-100">
				<a href="/signin" id="link-login">Back to login</a>
			</div>
		</div>
	</div>
	<?php require_once(VIEWS . "_footer.php"); ?>
</body>
<script>
	const msg = document.getElementById("msg");
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
		} else { setSuccess(password); return true; }
	}
	const			validateConfPass = ( ) => {
		if ( newpassword.value !== confirmationPassword.value ) { setError(confirmationPassword, "Passwords doesn't match"); return false; }
		else { setSuccess(confirmationPassword); return true; }
	}
	
</script>
</html>