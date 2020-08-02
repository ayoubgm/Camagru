<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/signin.css"/>
    <title>Sign in</title>
</head>
<body>
    <div class="container">
		<div class="card w-100">
			<div class="row no-gutters">
				<div class="col-md-4">
					<img src="<?php echo PUBLIC_FOLDER; ?>/images/background-img0.jpg" class="card-img">
				</div>
				<div class="col-md-8">
					<div class="card-body">
						<p class="card-title">Sign in</p>
						<form class="offset-lg-2">
							<div class="form-group row">
								<label for="InputUsername" class="col-lg-3 col-form-label">Username :</label>
								<div class="col-lg-7">
									<input type="text" class="form-control" id="InputUsername" placeholder="Enter your username">
								</div>
							</div>
							<div class="form-group row">
								<label for="InputPassword" class="col-lg-3 col-form-label">Password :</label>
								<div class="col-lg-7">
									<input type="password" class="form-control" id="InputPassword" placeholder="Password">
								</div>
							</div>
							<div class="row mb-5">
								<button type="submit" class="btn btn-primary w-50" id="btn-login">Log in</button>
							</div>
							<span class="reset-pass">
								Forget your password ?</br>
								<a href="<?php echo SERVER; ?>/reset-password" id="link-reset"> Reset password</a>
							</span>
						</form>
					</div>
					<div class="card-footer text-muted w-100">
						<a href="<?php echo SERVER; ?>">Home</a>
						<a href="<?php echo SERVER; ?>/signup" id="link-signup">Sign up</a>
					</div>
				</div>
			</div>
		  </div>
      </div>
</body>
</html>