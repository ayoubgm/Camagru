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
		<link rel="stylesheet" href="/public/css/home/new_password.css"/>
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
					<div class="col-md-12 px-2 py-4">
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
									class="offset-2 col-8 btn btn-outline-danger w-50 my-3"
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
	<script type="text/javascript" src="/public/js/home/newpassword.js"></script>
</html>