<?php include('session.php'); ?>
<?php
if (isset($_POST['create_user'])) {
	$names = $_POST['names'];
	$phone = $_POST['phone'];
	$role = $_POST['role'];
	$password = md5($_POST['password']);
	$query = "INSERT INTO users(names, phone, photo, password, role, status, deleted) VALUES('$names', '$phone', '', '$password', '$role', 'Active', '');";
	if ($conn->query($query)) {
		header('Location: users.php?success');
	}
}
if (isset($_POST['change_password'])) {
	$password = $_POST['password'];
	$confirm_password = $_POST['confirm_password'];
	$user_id = $_POST['user_id'];
	if ($password !== $confirm_password) {
		header('Location: users.php?invalidconfirmation');
		return;
	}
	$password = md5($_POST['password']);
	$query = "UPDATE users SET password = '$password' WHERE id = '$user_id'";
	if ($conn->query($query)) {
		header('Location: users.php?success');
	}
}
if (isset($_GET['delete'])) {
	$delete = $_GET['delete'];
	$query = "UPDATE users SET deleted = 'Yes' WHERE id = '$delete'";
	if ($conn->query($query)) {
		header('Location: users.php?success');
	}
}
if (isset($_POST['edit_user'])) {
	$user_id = $_POST['user_id'];
	$names = $_POST['names'];
	$phone = $_POST['phone'];
	$role = $_POST['role'];
	$status = $_POST['status'];
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
	$query = "UPDATE users SET names = '$names', phone = '$phone', role = '$role', status = '$status' WHERE id = '$user_id'";
	if ($conn->query($query)) {
		header('Location: users.php?success');
	}
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
	<title>Users - School File Sharing</title>
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/line-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/dataTables.bootstrap.min.css">
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
				<!--  -->
				<?php if (isset($_GET['success'])) { ?>
					<div class="alert alert-success alert-dismissable"><button type="button" data-dismiss="alert" aria-hidden="true" class="close">×</button></span><strong>Success!</strong> completed successfully</div>
				<?php } ?>
				<?php if (isset($_GET['invalidconfirmation'])) { ?>
					<div class="alert alert-danger alert-dismissable"><button type="button" data-dismiss="alert" aria-hidden="true" class="close">×</button></span><strong>Oops!</strong> invalid confirmation password</div>
				<?php } ?>
				<!--  -->
				<div class="row">
					<div class="col-xs-4">
						<h4 class="page-title">Users</h4>
					</div>
					<?php
					if ($user_role != 'Student') {
						?>
						<div class="col-xs-8 text-right m-b-30">
							<a href="#" class="btn btn-primary rounded" data-toggle="modal" data-target="#add_user"><i class="fa fa-plus"></i> Add User</a>
						</div>
					<?php
					}
					?>
				</div>
				<div class="row filter-row">
					<form>
						<div class="col-sm-offset-3 col-sm-4 col-xs-6">
							<div class="form-group form-focus">
								<label class="control-label">Name</label>
								<?php
								$name = '';
								if (isset($_GET['name']) && $_GET['name'] !== '') {
									$name = $_GET['name'];
								}
								?>
								<input type="text" value="<?php echo $name; ?>" name="name" class="form-control floating" />
							</div>
						</div>
						<div class="col-sm-2 col-xs-6">
							<button type="submit" class="btn btn-success btn-block"> Search </button>
						</div>
					</form>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive">
							<table class="table table-striped custom-table datatable">
								<thead>
									<tr>
										<th style="width:50%;">Names</th>
										<th>Phone</th>
										<?php
										if ($user_role == 'Administrator') {
											?>
											<th>Status</th>
										<?php
										}
										?>
									</tr>
								</thead>
								<tbody>
									<?php
									$query = "SELECT * FROM users WHERE deleted != 'Yes' ORDER BY names ASC, role DESC";
									if (isset($_GET['name']) && $_GET['name'] !== '') {
										$name = $_GET['name'];
										$query = "SELECT * FROM users WHERE (names like '%$name%' OR phone like '%$name%') AND deleted != 'Yes' ORDER BY names ASC, role DESC";
									}
									$query = $conn->query($query);
									while ($row = $query->fetch_array()) {
										$user_photo = ($row['photo'] == '') ? 'assets/img/user.jpg' : 'uploads/' . $row['photo'];
										?>
										<tr>
											<td>
												<img class="avatar" src="<?php echo $user_photo; ?>" alt="<?php echo $names; ?>">
												<h2><?php echo $row['names']; ?> <span><?php echo $row['role']; ?></span></h2>
											</td>
											<td><?php echo $row['phone']; ?></td>
											<?php
											if ($user_role == 'Administrator') {
												?>
												<td>
													<span class="label label-<?php echo $row['status'] == 'Inactive' ? 'danger' : 'info'; ?>-border pull-left"><?php echo $row['status']; ?></span>
													<div class="dropdown text-right">
														<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
														<ul class="dropdown-menu pull-right">
															<li><a href="#" data-toggle="modal" data-target="#edit_user_<?php echo $row['id']; ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
															<li><a href="#" data-toggle="modal" data-target="#delete_user_<?php echo $row['id']; ?>"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
														</ul>
													</div>
												</td>
											<?php
											}
											?>
										</tr>
										<div id="delete_user_<?php echo $row['id']; ?>" class="modal custom-modal fade" role="dialog">
											<div class="modal-dialog">
												<div class="modal-content modal-md">
													<div class="modal-header">
														<h4 class="modal-title">Delete <?php echo $row['names']; ?></h4>
													</div>
													<form>
														<div class="modal-body card-box">
															<p>Are you sure want to delete this account?</p>

															<div class="m-t-20">
																<a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
																<a href="users.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
															</div>
														</div>
													</form>
												</div>
											</div>
										</div>
										<div id="edit_user_<?php echo $row['id']; ?>" class="modal custom-modal fade" role="dialog">
											<div class="modal-dialog">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<div class="modal-content modal-lg">
													<div class="modal-header">
														<h4 class="modal-title">Edit User</h4>
													</div>
													<div class="modal-body">
														<form class="m-b-30" method="POST" enctype="multipart/form-data">
															<div class="row">
																<div class="col-md-6">
																	<div class="form-group">
																		<label class="control-label">Names <span class="text-danger">*</span></label>
																		<input class="form-control" value="<?php echo $row['names']; ?>" autocomplete="off" name="names" type="text" required>
																		<input value="<?php echo $row['id']; ?>" name="user_id" type="hidden">
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<label class="control-label">Phone <span class="text-danger">*</span></label>
																		<input class="form-control" value="<?php echo $row['phone']; ?>" autocomplete="off" name="phone" type="text" required>
																	</div>
																</div>
																<div class="col-md-4">
																	<div class="form-group">
																		<label class="control-label">Role</label>
																		<select name="role" class="select">
																			<?php
																			$list = ['Student', 'Staff', 'Administrator'];
																			foreach ($list as $single) {
																				echo '<option ' . ($single == $row['role'] ? 'selected' : '') . '>' . $single . '</option>';
																			}
																			?>
																		</select>
																	</div>
																</div>
																<div class="col-md-4">
																	<div class="form-group">
																		<label class="control-label">Photo</label>
																		<input class="form-control" name="photo" type="file">
																	</div>
																</div>
																<div class="col-md-4">
																	<div class="form-group">
																		<label class="control-label">Status</label>
																		<select name="status" class="select">
																			<?php
																			$list = ['Active', 'Inactive'];
																			foreach ($list as $single) {
																				echo '<option ' . ($single == $row['status'] ? 'selected' : '') . '>' . $single . '</option>';
																			}
																			?>
																		</select>
																	</div>
																</div>
															</div>
															<div class="m-t-20 text-center">
																<button type="submit" name="edit_user" class="btn btn-primary">Edit User</button>
															</div>
														</form>
														<form class="m-b-30" method="POST">
															<div class="row">
																<div class="col-md-6">
																	<div class="form-group">
																		<label class="control-label">Password</label>
																		<input class="form-control" name="password" type="password" required>
																		<input value="<?php echo $row['id']; ?>" name="user_id" type="hidden">
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<label class="control-label">Confirm Password</label>
																		<input class="form-control" name="confirm_password" type="password" required>
																	</div>
																</div>
															</div>
															<div class="m-t-20 text-center">
																<button name="change_password" class="btn btn-primary">Change Password</button>
															</div>
														</form>
													</div>
												</div>
											</div>
										</div>
									<?php
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="add_user" class="modal custom-modal fade" role="dialog">
			<div class="modal-dialog">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<div class="modal-content modal-lg">
					<div class="modal-header">
						<h4 class="modal-title">Add User</h4>
					</div>
					<div class="modal-body">
						<form class="m-b-30" method="POST">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label">Names <span class="text-danger">*</span></label>
										<input class="form-control" autocomplete="off" name="names" type="text" required>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label">Phone <span class="text-danger">*</span></label>
										<input class="form-control" autocomplete="off" name="phone" type="text" required>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label">Role</label>
										<select name="role" class="select">
											<option>Student</option>
											<?php
											if ($user_role == 'Administrator') {
												?>
												<option>Staff</option>
												<option>Administrator</option>
											<?php
											}
											?>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label">Password</label>
										<input class="form-control" name="password" type="password" required>
									</div>
								</div>
							</div>
							<div class="m-t-20 text-center">
								<button type="submit" name="create_user" class="btn btn-primary">Create User</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script data-cfasync="false" src="https://dreamguys.co.in/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
	<script type="text/javascript" src="assets/js/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="assets/js/dataTables.bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/js/jquery.slimscroll.js"></script>
	<script type="text/javascript" src="assets/js/select2.min.js"></script>
	<script type="text/javascript" src="assets/js/moment.min.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript" src="assets/js/app.js"></script>
</body>

</html>
