<?php
	$data;
	if ( isset( $this->view_data ) ) { $data = $this->view_data; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Registration</title>
	<link rel="icon" href="/public/images/logo.png">
	<link rel="stylesheet" href="/public/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="/public/css/signup.css"/>
</head>
<body>
	<div class="container col-md-7 p-0 h-100">
		<div class="card">
			<div class="row no-gutters">
				<div class="col-md-4 d-none d-lg-block">
					<img src="/public/images/background-img4.jpg" class="card-img">
				</div>
				<div class="col-lg-8">
					<div class="card-body">
						<p class="card-title">Registration</p>
						<form action="/signup" method="POST" onsubmit="validateRegisterData(this)">
							<div class="form-row m-0 mb-1">
								<div class="form-group col-lg-6">
									<label for="inputFirstName">Firstname <span class="text-danger">*</span>:</label>
									<input
										type="text"
										class="form-control"
										id="inputFirstName"
										name="firstname"
										placeholder="firstname"
										value="<?php if ( (isset($data['success']) && $data['success'] == "false") && isset($_POST['firstname']) ){
											echo $_POST['firstname'];
										} ?>"
										oninput="validateFirstName(this)"
									/>
								</div>
								<div class="form-group col-lg-6">
									<label for="inputLastName">Lastname <span class="text-danger">*</span>:</label>
									<input
										type="text"
										class="form-control"
										name="lastname"
										id="inputLastName"
										placeholder="lastname"
										value="<?php if ( (isset($data['success']) && $data['success'] == "false") && isset($_POST['lastname']) ){ echo $_POST['lastname']; } ?>"
										oninput="validateLastName(this)"
									/>
								</div>
							</div>
							<div class="form-row m-0 mb-1">
								<div class="form-group col-lg-4">
									<label for="inputUsername">Username <span class="text-danger">*</span>:</label>
									<input
										type="text"
										class="form-control"
										name="username"
										id="inputUsername"
										placeholder="username"
										value="<?php if ( (isset($data['success']) && $data['success'] == "false") && isset($_POST['username']) ){ echo $_POST['username']; } ?>"
										oninput="validateUsername(this)"
									/>
								</div>
								<div class="form-group col-lg-8">
									<label for="inputEmail">Email <span class="text-danger">*</span>:</label>
									<input
										type="email"
										class="form-control"
										name="email"
										id="inputEmail"
										placeholder="email"
										value="<?php if ( (isset($data['success']) && $data['success'] == "false") && isset($_POST['email']) ){ echo $_POST['email']; } ?>"
										oninput="validateEmail(this)"
									/>
								</div>
							</div>
							<div class="form-row m-0 mb-1">
								<div class="form-group col-lg-2">
									<label for="inputGender">Gender <span class="text-danger">*</span>:</label>
									<select
										id="choice-gender"
										class="custom-select"
										name="gender"
										onchange="validateGender(this)"
									>
										<option <?php if ( (isset($data['success']) && $data['success'] == "false") && isset($_POST['gender']) && $_POST['gender'] == "male") { echo " Selected "; } ?> value="male">Male</option>
										<option <?php if ( (isset($data['success']) && $data['success'] == "false") && isset($_POST['gender']) && $_POST['gender'] == "female") { echo "Selected"; } ?> value="female">Female</option>
									</select>
								</div>	
								<div class="form-group col-lg-10">
									<label for="inputAddress">Address :</label>
									<input
										type="text"
										class="form-control"
										name="address"
										id="inputAddress"
										placeholder="address"
										value="<?php if ( (isset($data['success']) && $data['success'] == "false") && isset($_POST['address']) ){ echo $_POST['address']; } ?>"
										oninput="validateAddress(this)"
									/>
								</div>
							</div>
							<div class="form-row mb-2 m-0">
								<div class="form-group col-lg-6">
									<label for="inputPassword">Password <span class="text-danger">*</span>:</label>
									<input
										type="password"
										class="form-control"
										name="password"
										id="inputPassword"
										placeholder="password"
										value="<?php if ( (isset($data['success']) && $data['success'] == "false") && isset($_POST['password']) ){ echo $_POST['password']; } ?>"
										oninput="validatePassword(this)"
									/>
								</div>
								<div class="form-group col-lg-6">
									<label for="inputConfirmationPass">Confirmation password <span class="text-danger">*</span>:</label>
									<input
										type="password"
										class="form-control"
										name="confirmation_password"
										id="inputConfirmationPass"
										placeholder="Confirmation password"
										value="<?php if ( (isset($data['success']) && $data['success'] == "false") && isset($_POST['confirmation_password']) ){ echo $_POST['confirmation_password']; } ?>"
										oninput="validateConfPass(this)"
									/>
								</div>
							</div>
							<div class="row text-center px-5 py-4">
								<span id="msg" class="w-100 <?php echo ( isset( $data['success'] ) && $data['success'] == "true" ) ? "text-success" : "text-danger"; ?>">
									<?php if ( isset($data['msg']) ) echo $data['msg'];?>
								</span>
							</div>
							<div class="form-row register my-5">
								<input
									type="submit"
									class="offset-2 col-8 btn btn-primary w-50"
									value="Register"
									id="btn-signup"
									name="btn-signup"
								/>
							</div>
						</form>
					</div>
					<div class="card-footer text-muted w-100">
						<a href="/">Home</a>
						<a href="/signin" id="link-signin">Log in</a>
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
	const gender = document.getElementById('choice-gender');
	const password = document.getElementById('inputPassword');
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

	// Validate firstname by run some regex
	const 			validateFirstName = ( firstname ) => {
		if ( !/^[a-zA-Z]{3,30}$/.test( firstname.value ) ) {
			setError(firstname, "The firstname must contains letters only ( between 3 and 30 ) !");
			return false;
		} else {
			setSuccess( firstname );
			return true;
		}
	}
	// Validate lastname by run some regex
	const 			validateLastName = ( lastname ) => {
		if ( !/^[a-zA-Z]{3,30}$/.test( lastname.value ) ) {
			setError(lastname, "The lastname must contains letters only ( between 3 and 30 ) !");
			return false;
		} else {
			setSuccess( lastname );
			return true;
		}
	}
	// Validate lastname by run some regex
	const 			validateUsername = ( username ) => {
		if ( !/^(?=.{3,20}$)(?![-_.])(?!.*[-_.]{2})[a-zA-Z0-9._-]+(?<![-_.])$/.test( username.value ) ) {
			setError(username, "The username should contain between 3 and 20 letters or numbers ( -, _ or . ) !");
			return false;
		} else {
			setSuccess( username );
			return true;
		}
	}
	// Validate email by run some regex
	const			validateEmail = ( email ) => {
		if ( !/[a-zA-Z0-9-_.]{1,50}@[a-zA-Z0-9-_.]{1,50}\.[a-zA-Z0-9]{2,10}$/.test( email.value ) ) {
			setError(email, "Invalid email address !");
			return false;
		} else {
			setSuccess( email );
			return true;
		}
	}
	// Validate address by run adress
	const 			validateAddress = ( address ) => {
		if ( !/^[a-zA-Z0-9\s,'-]*$/.test( address.value ) ) {
			setError(address, "The address should be contains letters or numbers ( ',', ' or - ) !");
			return false;
		} else {
			setSuccess( address );
			return true;
		}
	}
	// Validate gender
	const			validateGender = ( gender ) => {
		if ( ![ "male", "female" ].includes( gender.value ) ) {
			setError( gender, "The gender must either male or female" );
			return false;
		} else {
			setSuccess( gender );
			return true;
		}
	}
	// Validate password by run some regex
	const 			validatePassword = ( password ) => {
		if ( !/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*_-]).{8,}$/.test( password.value ) ) {
			setError(password, "The password should be minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character !");
			return false;
		} else {
			setSuccess( password );
			return true;
		}
	}

	const			validateConfPass = ( confirmationPassword ) => {
		if ( password.value != confirmationPassword.value ) {
			setError(confirmationPassword, "Passwords doesn't match");
			return false;
		} else {
			setSuccess( confirmationPassword );
			return true;
		}
	}

	const validateRegisterData = () => {
		if ( !firstname.value || !lastname.value || !username.value || !email.value || !gender.value || !password.value || !confirmationPassword.value ) {
			setError(firstname, "Invalid data provided");
			return false;
		} else if (
			!validateFirstName( firstname ) ||
			!validateLastName( lastname ) ||
			!validateUsername( username ) ||
			!validateEmail( email ) ||
			!validateAddress( address ) ||
			!validateGender( gender ) ||
			!validatePassword( password ) ||
			!validateConfPass( confirmationPassword )
		) { return false; }
		else { return true; }
	}
</script>
</html>