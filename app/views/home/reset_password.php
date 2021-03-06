<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" href="/public/images/logo.png">
		<link rel="stylesheet" href="/public/css/bootstrap.min.css"/>
		<link rel="stylesheet" href="/public/css/home/reset_password.css"/>
		<link rel="stylesheet" href="/public/css/_header.css"/>
		<link rel="stylesheet" href="/public/css/_footer.css"/>
		<title>Reset password</title>
		<noscript>
			<p class="text-white">We're sorry but the application doesn't work properly without JavaScript enabled. Please enable it to continue.</p>
			<style>
				header { display: none; }
				div { display:none; }
				footer { display: none; }
			</style>
		</noscript>
	</head>
	<body>
		<div class="container">
			<div class="card offset-lg-1 col-lg-10">
				<div class="card-body">
					<p class="card-title">
						<span>Reset password</span>
					</p>
					<div id="icone" class="text-center">
						<img src="/public/images/forgot-password.png"/></br>
					</div>
					<hr/>
					<div class="p-4">
						<form method="POST" action="/reset_password">
							<div class="row offset-md-2">
								<label for="inputEmail" class="col-md-2">Email : </label>
								<input
									type="text"
									class="form-control col-md-6"
									name="email"
									id="inputEmail"
									placeholder="enter your email"
									oninput="validateEmail( this );"
								/>
							</div>
							<div class="row text-center mt-4" style="height: 30px;">
								<span id="msg" class="w-100 <?php echo ( isset( $this->view_data['success'] ) && $this->view_data['success'] == "true" ) ? "text-success" : "text-danger"; ?> ">
									<?php if ( isset($this->view_data['msg']) ) echo $this->view_data['msg']; ?>
								</span>
							</div>
							<div class="text-center">
								<div class="form-row">
									<input
										type="submit"
										class="offset-2 col-8 btn btn-outline-dark w-50 my-3"
										value="Reset password"
										name="btn-reset"
									/>
								</div> 
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
	<script type="text/javascript" src="/public/js/home/resetpassword.js"></script>
</html>