<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Registration</title>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/signup.css"/>
</head>
<body>
	<div class="container">
		<div class="card w-100">
			<div class="row no-gutters">
				<div class="col-md-4">
					<img src="<?php echo PUBLIC_FOLDER; ?>/images/background-img4.jpg" class="card-img">
				</div>
				<div class="col-md-8">
					<div class="card-body">
						<p class="card-title">Registration</p>
						<form method="POST" action="<?php echo SERVER; ?>/signup" onsubmit="return validateRegisterData();">
							<div class="form-row">
								<div class="form-group col-lg-6 m-0">
									<label for="inputFirstName">Firstname :</label>
									<input
										type="text"
										class="form-control"
										id="inputFirstName"
										name="firstname"
										placeholder="firstname"
										oninput="validateFirstName(this)"
									/>
								</div>
								<div class="form-group m-0 col-lg-6">
									<label for="inputLastName">Lastname :</label>
									<input type="text" class="form-control" name="lastname" id="inputLastName" placeholder="lastname">
								</div>
							</div>
							<div class="form-row">
								<div class="form-group m-0 col-lg-4">
									<label for="inputUsername">Username :</label>
									<input type="text" class="form-control" name="username" id="inputUsername" placeholder="username">
								</div>
								<div class="form-group m-0 col-lg-8">
									<label for="inputEmail">Email :</label>
									<input type="email" class="form-control" name="email" id="inputEmail" placeholder="email">
								</div>
							</div>
							<div class="form-row">
								<div class="form-group m-0 col-lg-12">
									<label for="inputAddress">Address :</label>
									<input type="text" class="form-control" name="address" id="inputAddress" placeholder="address">
								</div>
							</div>
							<div class="form-row">
								<div class="form-group m-0 col-lg-6">
									<label for="inputPassword">Password :</label>
									<input type="password" class="form-control" name="password" id="inputPassword" placeholder="password">
								</div>
								<div class="form-group m-0 col-lg-6">
									<label for="inputConfirmationPass">Confirmation password :</label>
									<input type="password" class="form-control" name="confirmation_password" id="inputConfirmationPass" placeholder="Confirmation password">
								</div>
							</div>
							<div class="form-row register m-4">
								<input type="submit" class="offset-2 col-lg-8 btn btn-outline-primary w-50" value="Register" id="btn-signup"/>
							</div>
							<div class="offset-2 col-lg-8 w-100 m-4">
								<span id="msg"></span>
							</div>
						</form>
					</div>
					<div class="card-footer text-muted w-100">
						<a href="<?php echo SERVER; ?>">Home</a>
						<a href="<?php echo SERVER; ?>/signin" id="link-signin">Log in</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<script>
		const buttonRegister = document.querySelector('#btn-signup');
		const msg = document.getElementById("msg");
		const firstname = document.getElementById('inputFirstName');
		const lastname = document.getElementById('inputLastName');
		const username = document.getElementById('inputUsername');
		const email = document.getElementById('inputEmail');
		const address = document.getElementById('inputAddress');
		const password = document.getElementById('inputPassword');
		const confirmationPassword = document.getElementById('inputConfirmationPass');

		const 		setError = ( target, msgerror ) => {
			target.style.border = "1px solid red";
			msg.innerHTML = msgerror;
			msg.style.color = "red";
			msg.style.fontSize = "9pt";
			msg.style.fontWeight = "bold";
		}

		const 		setSuccess = ( target ) => {
			msg.innerHTML = "";
			target.style.border = "1px solid green";
		}

		const 	validateFirstName = ( firstname ) => {
			if ( firstname.value === "" ) { setError(firstname, "Firstname can't be empty !"); }
			else if ( /^.{0,3}$/.test( firstname.value ) ) { setError(firstname, "Firstname is too short !"); }
			else if ( /^[a-z]$/.test( firstname.value ) ) { setError(firstname, "Firstname must contains only lowercase letters !"); }
			else { setSuccess(firstname) }
		}
		const validateRegisterData = () => {
			let errorValidateFN;

			if ( errorValidateFN = validateFirstName( firstname.value ) ) {
				firstname.style.border = "1px solid red";
				console.log(errorValidateFN);
				return false;
			} else {
				return true;
			}
		}
	</script>
</html>