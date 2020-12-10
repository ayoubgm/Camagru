<?php
	$data;
	if ( isset( $this->view_data ) ) { $data = $this->view_data; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Reset password</title>
	<link rel="icon" href="/public/images/logo.png">
	<link rel="stylesheet" href="/public/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="/public/css/reset_password.css"/>
	<link rel="stylesheet" href="/public/css/_header.css"/>
	<link rel="stylesheet" href="/public/css/_footer.css"/>
</head>
<body>
	<div class="container">
		<div class="card offset-lg-2 col-lg-8">
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
							<span id="msg" class="w-100 <?php echo ( isset( $data['success'] ) && $data['success'] == "true" ) ? "text-success" : "text-danger"; ?> ">
								<?php if ( isset($data['msg']) ) echo $data['msg'];?>
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
<script>
	const msg = document.getElementById("msg");
	
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
	
	// Validate email by run some regex
	const			validateEmail = ( email ) => {
		if ( email.value == "" ) {
			setError( email, "the email can't be empty !" ); return false;
		} else if ( !/[a-zA-Z0-9-_.]{1,50}@[a-zA-Z0-9-_.]{1,50}\.[a-z0-9]{2,10}$/.test( email.value ) ) {
			setError(email, "Invalid email address !"); return false;
		} else {
			setSuccess(email); return true;
		}
	}
</script>
</html>