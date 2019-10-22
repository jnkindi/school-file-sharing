<?php
session_start();
$uname = '';
$error = ''; // Variable To Store Error Message
if (isset($_SESSION['logged_user_info'])) {
	header("Location: dashboard.php");
}
if (isset($_POST['login'])) {
	// Define $username and $password
	$username = $_POST['username'];
	$uname = $_POST['username'];
	$password = $_POST['password'];
	// Establishing Connection with Server by passing server_name, user name, password and database as a parameter
	include("config/config.php");
	// To protect MySQL injection for Security purpose
	$username = stripslashes($username);
	$password = md5(stripslashes($password));
	// SQL query to fetch information of registerd users and finds user match.

	$query = "SELECT id, status FROM users WHERE phone = '$username' AND password = '$password' AND deleted != 'Yes' LIMIT 1";
	$query = $conn->query($query);
	if ($query->num_rows == 1) {
		$arr = $query->fetch_array();
		if ($arr['status'] == 'Active') {
			$_SESSION['logged_user_info'] = $arr['id']; // Initializing Session
			header("Location: home.php?_rdr"); // Redirecting To Other Page
			return;
		}
		$error = '<div class="alert alert-warning alert-dismissable"><button type="button" data-dismiss="alert" aria-hidden="true" class="close">×</button></span><strong>Oops!</strong> Account deactivated! Contact administrator for support</div>';
	} else {
		$error = '<div class="alert alert-danger alert-dismissable"><button type="button" data-dismiss="alert" aria-hidden="true" class="close">×</button></span><strong>Oops!</strong> Invalid Email or Password</div>';
	}
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
	<title>Login - School File Sharing</title>
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>

<body>
	<div class="main-wrapper">
		<div class="account-page">
			<div class="container">
				<h3 class="account-title">File Sharing</h3>
				<div class="account-box">
					<div class="account-wrapper">
						<div class="account-logo">
							<a href="index.php"><img src="assets/img/logo2.png" alt="School File Sharing"></a>
						</div>
						<form action="index.php" method="POST">
							<?php echo $error; ?>
							<div class="form-group form-focus">
								<label class="control-label">Phone</label>
								<input class="form-control floating" value="<?php echo $uname; ?>" type="text" name="username">
							</div>
							<div class="form-group form-focus">
								<label class="control-label">Password</label>
								<input class="form-control floating" type="password" name="password">
							</div>
							<div class="form-group text-center">
								<button class="btn btn-primary btn-block account-btn" type="submit" name="login">Login</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="sidebar-overlay" data-reff="#sidebar"></div>
	<script type="text/javascript" src="assets/js/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/js/app.js"></script>
</body>

</html>
