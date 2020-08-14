<?php
	if ( !isset($_SESSION['userid']) ) {
		header("Location: /camagru_git/home");
	} else {
		$data = $this->view_data['data'];
		$userData = $data['userData'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Edit informations</title>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/editinfos.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/_header.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/_footer.css"/>
</head>
<body>
	<?php require_once(VIEWS . "_header.php");?>
	<div class="container">
		<div class="card offset-lg-2 col-lg-8">
			<div class="card-body">
				<p class="card-title">
					<span>Edit informations</span>
				</p>
				<div class="full-name text-center">
					<?php if ( $userData['gender'] == "male" ) { ?>
						<img
							id="icone-male"
							src="<?php echo PUBLIC_FOLDER . "/images/user-edit-male.png"; ?>"
						/>
					<?php } else { ?>
						<img
							id="icone-female"
							src="<?php echo PUBLIC_FOLDER . "/images/user-edit-female.png"; ?>"
						/>
					<?php } ?>
				</div>
				<hr/>
				<div class="col-md-12 user-infos text-left">
					<form action="<?php echo SERVER; ?>/user/edit" method="POST" onsubmit="return validateEditData();">
						<div class="form-row">
							<div class="form-group col-lg-6 m-0 mb-1">
								<label for="inputFirstName">Firstname <span class="text-danger">*</span>:</label>
								<input
									type="text"
									class="form-control"
									id="inputFirstName"
									name="firstname"
									placeholder="firstname"
									value="<?php print( $userData['firstname'] ); ?>"
									oninput="validateFirstName(this)"
								/>
							</div>
							<div class="form-group col-lg-6 m-0 mb-1">
								<label for="inputLastName">Lastname <span class="text-danger">*</span>:</label>
								<input
									type="text"
									class="form-control"
									name="lastname"
									id="inputLastName"
									placeholder="lastname"
									value="<?php print( $userData['lastname'] ); ?>"
									oninput="validateLastName(this)"
								/>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-lg-4 m-0 mb-1">
								<label for="inputUsername">Username <span class="text-danger">*</span>:</label>
								<input
									type="text"
									class="form-control"
									name="username"
									id="inputUsername"
									placeholder="username"
									value="<?php print( $userData['username'] ); ?>"
									oninput="validateUsername(this)"
								/>
							</div>
							<div class="form-group col-lg-8 m-0 mb-1">
								<label for="inputEmail">Email <span class="text-danger">*</span>:</label>
								<input
									type="email"
									class="form-control"
									name="email"
									id="inputEmail"
									placeholder="email"
									value="<?php print( $userData['email'] ); ?>"
									oninput="validateEmail(this)"
								/>
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-lg-2 m-0 mb-1">
								<label for="inputGender">Gender <span class="text-danger">*</span>:</label>
								<select class="custom-select" name="gender">
									<option
									<?php 
										if ( $userData['gender'] == "male"){
											echo "Selected";
										}
									?> value="male">Male</option>
									<option
									<?php 
										if ( $userData['gender'] == "female" ){
											echo "Selected";
										}
									?> value="female">Female</option>
								</select>
							</div>	
							<div class="form-group col-lg-10 m-0 mb-1">
								<label for="inputAddress">Address :</label>
								<input
									type="text"
									class="form-control"
									name="address"
									id="inputAddress"
									placeholder="address"
									value="<?php if ( isset($userData['address']) ) { print( $userData['address'] ); } ?>"
									oninput="validateAddress(this)"
								/>
							</div>
						</div>
						<div class="row text-center px-3 py-2" style="height: 50px;">
							<span id="msg" class="w-100 
									<?php 
										if ( isset( $this->view_data['success'] ) && $this->view_data['success'] == "true" ) { echo "text-success"; }
										else { echo "text-danger"; }
									?>
								">
								<?php if ( isset($this->view_data['msg']) ) echo $this->view_data['msg'];?>
							</span>
						</div>
						<div class="form-row edit">
							<input
								type="submit"
								class="offset-2 col-8 btn btn-outline-warning w-50"
								value="Edit"
								id="btn-edit"
								name="btn-edit"
							/>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php require_once(VIEWS . "_footer.php"); ?>
<script src="<?php echo PUBLIC_FOLDER; ?>/js/_menu.js"></script>
<script>
	const menu = document.querySelector("nav .btn-auth .dropdown");
	const msg = document.getElementById("msg");
	const firstname = document.getElementById('inputFirstName');
	const lastname = document.getElementById('inputLastName');
	const username = document.getElementById('inputUsername');
	const email = document.getElementById('inputEmail');
	const address = document.getElementById('inputAddress');
	const showMenu = () => {
		if ( menu.style.display == "none" ) { menu.style.display = "block"; }
		else { menu.style.display = "none"; }
	};
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
		if ( !/^[a-zA-Z]{3,30}$/.test( firstname.value ) ) { setError(firstname, "The firstname must contains letters only ( between 3 and 30 ) !"); return false; }
		else { setSuccess(firstname); return true;}
	}
	// Validate lastname by run some regex
	const 			validateLastName = ( lastname ) => {
		if ( !/^[a-zA-Z]{3,30}$/.test( lastname.value ) ) { setError(lastname, "The lastname must contains letters only ( between 3 and 30 ) !"); return false; }
		else { setSuccess(lastname); return true; }
	}
	// Validate lastname by run some regex
	const 			validateUsername = ( username ) => {
		if ( !/^(?=.{3,20}$)(?![-_.])(?!.*[-_.]{2})[a-zA-Z0-9._-]+(?<![-_.])$/.test( username.value ) ) {
			setError(username, "The username should contain between 3 and 20 letters or numbers ( -, _ or . ) !"); return false;
		} else { setSuccess(username); return true; }
	}
	// Validate email by run some regex
	const			validateEmail = ( email ) => {
		if ( !/[a-zA-Z0-9-_.]{1,50}@[a-zA-Z0-9-_.]{1,50}\.[a-z0-9]{2,10}$/.test( email.value ) ) { setError(email, "Invalid email address !"); return false; }
		else { setSuccess(email); return true; }
	}
	// Validate address by run adress
	const 			validateAddress = ( address ) => {
		if ( !/^[a-zA-Z0-9\s,'-]*$/.test( address.value ) ) {
			setError(address, "The address should be contains letters or numbers ( ',', ' or - ) !");
			return false;
		} else { setSuccess(address); return true; }
	}
	
	const validateEditData = () => {
		if ( firstname.value === "" || lastname.value === "" || username.email === "" || email.value === "" ) { setError(firstname, "Invalid data provided"); return false; }
		else if ( !validateFirstName( firstname ) ) { return false; }
		else if ( !validateLastName( lastname ) ) { return false; }
		else if ( !validateUsername( username ) ) { return false; }
		else if ( !validateEmail( email ) ) { return false; }
		else if ( !validateAddress( address ) ) { return false; }
		else if ( !validatePassword( password ) ) { return false; }
		else if ( !validateConfPass( confirmationPassword ) ) { return false; }
		else { return true; }
	}
</script>
</body>
</html>
<?php
	}
?>