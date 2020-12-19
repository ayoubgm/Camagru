<?php
	$data;
	if ( isset( $this->view_data ) ) { $data = $this->view_data; }
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="/public/css/bootstrap.min.css"/>
		<link rel="stylesheet" href="/public/css/signin.css"/>
		<link rel="stylesheet" href="/public/css/_footer.css"/>
		<title>Sign in</title>
	</head>
	<body>
		<div class="container">
			<div class="card w-100">
				<div class="row no-gutters">
					<div class="col-md-4">
						<img src="/public/images/background-img0.jpg" class="card-img h-100">
					</div>
					<div class="col-md-8">
						<div class="card-body">
							<p class="card-title">Sign in</p>
							<form method="POST" action="/signin">
								<div class="form-group row">
									<label for="InputUsername" class="col-lg-4 col-form-label">Username :</label>
									<div class="col-lg-8">
										<input
											type="text"
											name="username"
											class="form-control"
											id="InputUsername"
											placeholder="Enter your username"
											autocomplete="off"
										/>
									</div>
								</div>
								<div class="form-group row">
									<label for="InputPassword" class="col-lg-4 col-form-label">Password :</label>
									<div class="col-lg-8">
										<input
											type="password"
											name="password"
											class="form-control"
											id="InputPassword"
											placeholder="Password"
											autocomplete="off"
										/>
									</div>
								</div>
								<div class="text-center" id="area-msg">
									<span id="msg" class="<?php echo ( isset( $data['success'] ) && $data['success'] == "true" ) ? "text-success" : "text-danger"; ?>">
										<?php if ( isset($data['msg']) ) echo $data['msg']; ?>
									</span>
								</div>
								<div class="row">
									<input type="submit" class="btn btn-primary w-75 mb-3" id="btn-login" value="Log in" name="btn-signin"/></br>
									<span class="reset-pass">Forget your password ? <a href="/reset_password" id="link-reset"> Reset password</a></span>
								</div>
							</form>
						</div>
						<div class="card-footer text-muted w-100">
							<a href="/">Home</a>
							<a href="/signup" id="link-signup">Sign up</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php require_once(VIEWS . "_footer.php"); ?>
	</body>
</html>