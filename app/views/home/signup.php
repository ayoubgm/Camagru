<?php
	if ( isset( $this->view_data ) ) {
		$data = $this->view_data;
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Registration</title>
		<link rel="icon" href="/public/images/logo.png">
		<link rel="stylesheet" href="/public/css/bootstrap.min.css"/>
		<link rel="stylesheet" href="/public/css/home/signup.css"/>
		<link rel="stylesheet" href="/public/css/_footer.css"/>
	</head>
	<body>
		<div class="container col-md-7 p-0 h-100">
			<div class="card">
				<div class="row no-gutters">
					<div class="col-lg-4">
						<img src="/public/images/background-img4.jpg" class="card-img">
					</div>
					<div class="col-12 col-xl-8">
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
							<a href="/signin" id="link-signin">Sign in</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php require_once(VIEWS . "_footer.php"); ?>
	</body>
	<script type="text/javascript" src="/public/js/home/signup.js"></script>
</html>