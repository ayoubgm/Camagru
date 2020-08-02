<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/signup.css"/>
    <title>Registration</title>
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
						<form>
							<div class="form-row">
								<div class="form-group col-lg-6">
									<label for="inputFirstName">Firstname :</label>
									<input type="text" class="form-control" id="inputFirstName" placeholder="firstname">
								</div>
								<div class="form-group col-lg-6">
									<label for="inputLastName">Lastname :</label>
									<input type="text" class="form-control" id="inputLastName" placeholder="lastname">
								</div>
							</div>
							<div class="form-row">
								<div class="form-group col-lg-4">
									<label for="inputUsername">Username :</label>
									<input type="text" class="form-control" id="inputUsername" placeholder="username">
								</div>
								<div class="form-group col-lg-8">
									<label for="inputEmail">Email :</label>
									<input type="email" class="form-control" id="inputEmail" placeholder="email">
								</div>
							</div>
							<div class="form-row">
								<div class="form-group col-lg-12">
									<label for="inputAddress">Address :</label>
									<input type="text" class="form-control" id="inputAddress" placeholder="address">
								</div>
							</div>
							<div class="form-row">
								<div class="form-group col-lg-6">
									<label for="inputPassword">Password :</label>
									<input type="password" class="form-control" id="inputPassword" placeholder="password">
								</div>
								<div class="form-group col-lg-6">
									<label for="inputConfirmationPass">Confirmation password :</label>
									<input type="password" class="form-control" id="inputConfirmationPass" placeholder="Confirmation password">
								</div>
							</div>
							<div class="form-row register">
								<button type="submit" class="col-lg-10 btn btn-outline-primary w-50" id="btn-Signup">Register</button>
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
</html>