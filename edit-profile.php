<?php include('session.php'); ?>
<?php
if (isset($_POST['edit'])) {
	$phone = $_POST['phone'];
	$changepassword = $_POST['changepassword'];
	$confirmpassword = $_POST['confirmpassword'];
	//
	$photo = $_FILES["photo"]["name"];
	if ($photo != "") {
		if (!is_dir('uploads')) {
			mkdir('uploads', 0777, true);
		}
		if (!is_dir('uploads/temp')) {
			mkdir('uploads/temp', 0777, true);
		}
		move_uploaded_file($_FILES["photo"]["tmp_name"], "uploads/temp/" . $photo);
		$file = explode(".", $photo);
		$file_name = randomString() . "." . strtolower(end($file));
		rename('uploads/temp/' . $photo, 'uploads/temp/' . $file_name);
		if (copy('uploads/temp/' . $file_name, 'uploads/' . $file_name)) {
			unlink('uploads/temp/' . $file_name);
		}
		$photo = $file_name;
		$query = "UPDATE users SET photo = '$photo' WHERE id = '$user_id'";
		$conn->query($query);
	}
	$query = "UPDATE users SET phone = '$phone' WHERE id = '$user_id'";
	$conn->query($query);
	if ($changepassword != "" && $confirmpassword != "") {
		if ($changepassword === $confirmpassword) {
			//
			$newPassword = md5($confirmpassword);
			$query = "UPDATE users SET password = '$newPassword' WHERE id = '$user_id'";
			$conn->query($query);
		} else {
			header('Location: edit-profile.php?invalidconfirmation');
			return;
		}
	}
	header('Location: edit-profile.php?success');
	return;
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
	<title>Edit Profile - School File Sharing</title>
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/line-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/select2.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap-datetimepicker.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<!--[if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif]-->
</head>

<body>
	<div class="main-wrapper">
		<?php include('header.php'); ?>
		<div class="page-wrapper">
			<div class="content container-fluid">
				<?php if (isset($_GET['success'])) { ?>
					<div class="alert alert-success alert-dismissable"><button type="button" data-dismiss="alert" aria-hidden="true" class="close">×</button></span><strong>Success!</strong> completed successfully</div>
				<?php } ?>
				<?php if (isset($_GET['invalidconfirmation'])) { ?>
					<div class="alert alert-danger alert-dismissable"><button type="button" data-dismiss="alert" aria-hidden="true" class="close">×</button></span><strong>Oops!</strong> invalid confirmation password</div>
				<?php } ?>
				<div class="row">
					<div class="col-sm-8">
						<h4 class="page-title">Edit Profile</h4>
					</div>
				</div>
				<form method="POST" enctype="multipart/form-data">
					<div class="card-box">
						<h3 class="card-title">Basic Informations</h3>
						<div class="row">
							<div class="col-md-12">
								<div class="profile-img-wrap">
									<img class="inline-block" src="<?php echo $user_photo; ?>" alt="user">
									<div class="fileupload btn btn-default">
										<span class="btn-text">edit</span>
										<input class="upload" type="file" name="photo">
									</div>
								</div>
								<div class="profile-basic">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group form-focus">
												<label class="control-label">Names</label>
												<input type="text" value="<?php echo $names; ?>" class="form-control floating" readonly />
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group form-focus">
												<label class="control-label">Phone</label>
												<input type="text" value="<?php echo $phone; ?>" class="form-control floating" name="phone" />
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group form-focus">
												<label class="control-label">Change Password</label>
												<input type="password" class="form-control floating" name="changepassword" />
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group form-focus">
												<label class="control-label">Confirm Password</label>
												<input type="password" class="form-control floating" name="confirmpassword" />
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="text-center m-t-20">
						<button type="submit" name="edit" class="btn btn-primary btn-lg" type="button">Save &amp; update</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="sidebar-overlay" data-reff="#sidebar"></div>
	<script type="text/javascript" src="assets/js/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/js/jquery.slimscroll.js"></script>
	<script type="text/javascript" src="assets/js/select2.min.js"></script>
	<script type="text/javascript" src="assets/js/moment.min.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript" src="assets/js/app.js"></script>
</body>

</html>
