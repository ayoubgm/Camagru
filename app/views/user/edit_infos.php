<?php
	if ( isset( $this->view_data['data'] ) ) {
		$gallery = ( isset( $this->view_data['data']['gallery'] ) ) ? $this->view_data['data']['gallery'] : null;
		$userData = ( isset( $this->view_data['data']['userData'] ) ) ? $this->view_data['data']['userData'] : null;
		$countUnreadNotifs = ( isset( $this->view_data['data']["countUnreadNotifs"] ) ) ? $this->view_data['data']["countUnreadNotifs"] : 0 ;
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" href="/public/images/logo.png">
		<link rel="stylesheet" href="/public/css/bootstrap.min.css"/>
		<link rel="stylesheet" href="/public/css/user/editinfos.css"/>
		<link rel="stylesheet" href="/public/css/_header.css"/>
		<link rel="stylesheet" href="/public/css/_footer.css"/>
		<title>Edit informations</title>
		<noscript>
			<p class="text-white">We're sorry but the application doesn't work properly without JavaScript enabled. Please enable it to continue.</p>
			<style>
				header { display: none; }
				div { display:none; }
				footer { display: none; }
			</style>
		</noscript>
	</head>
	<body onload="getNotifications();">
		<?php require_once(VIEWS . "_header.php");?>
		<div class="container">
			<div class="card offset-lg-2 col-lg-8">
				<div class="card-body">
					<p class="card-title">
						<span>Edit informations</span>
					</p>
					<div class="full-name text-center">
						<img src="<?php echo ( $userData['gender'] == "male" ) ? "/public/images/user-edit-male.png" : "/public/images/user-edit-female.png" ;  ?>">
					</div>
					<hr/>
					<div class="col-md-12 user-infos text-left">
						<form action="/user/edit" method="POST" oninput="">
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
										oninput="validateFirstName(this);"
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
										oninput="validateLastName(this);"
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
										oninput="validateUsername(this);"
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
										oninput="validateEmail(this);"
									/>
								</div>
							</div>
							<div class="form-row">
								<div class="form-group col-lg-2 m-0 mb-1">
									<label for="inputGender">Gender <span class="text-danger">*</span>:</label>
									<select
										id="choice-gender"
										class="custom-select"
										name="gender"
										onchange="validateGender(this);"
									>
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
										oninput="validateAddress(this);"
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
							<input type="hidden" name="token" value="<?php echo $_SESSION["token"] ?>"/>
							<div class="form-row edit">
								<input
									type="submit"
									class="offset-2 col-8 btn btn-warning w-50 text-white mt-5"
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
	</body>
	<script type="text/javascript" src="/public/js/_header.js"></script>
	<script type="text/javascript" src="/public/js/user/editinfos.js"></script>
</html>