<?php include('session.php'); ?>
<?php
if (isset($_POST['upload'])) {
	$title = $_POST['title'];
	$shareto = $_POST['shareto'];
	$protect = $_POST['protect'];
	$urgent = $_POST['urgent'];
	if ($_FILES["file"]["name"] != "") {
		if (!is_dir('uploads')) {
			mkdir('uploads', 0777, true);
		}
		if (!is_dir('uploads/temp')) {
			mkdir('uploads/temp', 0777, true);
		}
		move_uploaded_file($_FILES["file"]["tmp_name"], "uploads/temp/" . $_FILES["file"]["name"]);
		$file = explode(".", $_FILES["file"]["name"]);
		$file_name = randomString() . "." . strtolower(end($file));
		rename('uploads/temp/' . $_FILES["file"]["name"], 'uploads/temp/' . $file_name);
		if (copy('uploads/temp/' . $file_name, 'uploads/' . $file_name)) {
			unlink('uploads/temp/' . $file_name);
		}
		foreach ($shareto as $user) {
			$query = "SELECT names, phone FROM users WHERE id = '$user'";
			$query = $conn->query($query);
			$userDetails = $query->fetch_array();
			$userNames = $userDetails['names'];
			$userPhoneNumber = $userDetails['phone'];
			//
			$accesscode  = '';
			if ($protect == 'Yes' && $urgent == 'Yes') {
				$accesscode  = mt_rand(1000, 9999);
				//
				$message = "Hello, $userNames. Check file on the app. Access code: $accesscode";
				send_message($userPhoneNumber, $message);
			} else {
				if ($protect == 'Yes') {
					$accesscode  = mt_rand(1000, 9999);
					$message = "Hello, $userNames. Check file on the app. Access code: $accesscode";
					send_message($userPhoneNumber, $message);
				}
				if ($urgent == 'Yes') {
					$message = "Hello, $userNames. Check file on the app.";
					send_message($userPhoneNumber, $message);
					//
				}
			}
			$query = "INSERT INTO fileshare(title, file, owner, receiver, receivertype, urgent, accesscode, deleted) VALUES ('$title', '$file_name', '$user_id', '$user', 'User', '$urgent', '$accesscode', 'No')";
			$conn->query($query);
		}
		$msg = '';
		if (isset($_GET['byme'])) {
			$msg = '&byme';
		}
		header('Location: shared-files.php?success' . $msg);
		return;
	}
	header('Location: shared-files.php?nofile');
	return;
}
if (isset($_GET['delete'])) {
	$delete = $_GET['delete'];
	$query = "UPDATE fileshare SET deleted = 'Yes' WHERE id = '$delete'";
	$query = $conn->query($query);
	$msg = '';
	if (isset($_GET['byme'])) {
		$msg = '&byme';
	}
	header('Location: shared-files.php?success' . $msg);
	return;
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
	<title>Shared Files - School File Sharing</title>
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/line-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/select2.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap-datetimepicker.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<!--  -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
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
				<?php if (isset($_GET['nofile'])) { ?>
					<div class="alert alert-danger alert-dismissable"><button type="button" data-dismiss="alert" aria-hidden="true" class="close">×</button></span><strong>Oops!</strong> no file uploaded</div>
				<?php } ?>
				<!--  -->
				<div class="row">
					<div class="col-xs-8">
						<h4 class="page-title">Files share <?php echo (isset($_GET['byme']) ? 'by' : 'with') ?> me</h4>
					</div>
					<div class="col-xs-4 text-right m-b-30">
						<a href="#" class="btn btn-primary rounded pull-right" data-toggle="modal" data-target="#upload"><i class="fa fa-plus"></i> Share file</a>
					</div>
				</div>
				<div class="row filter-row">
					<form>
						<div class="col-sm-offset-3 col-sm-4 col-xs-6">
							<div class="form-group form-focus">
								<label class="control-label">File Title</label>
								<input type="text" name="name" class="form-control floating" />
								<?php
								if (isset($_GET['byme'])) {
									?>
									<input type="hidden" name="byme" />
								<?php
								}
								?>
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
							<table class="table table-striped custom-table m-b-0 datatable">
								<thead>
									<tr>
										<th><?php echo (isset($_GET['byme']) ? 'Receiver' : 'Sender') ?></th>
										<th>Document</th>
										<th>Date</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$query = "SELECT * FROM fileshare WHERE owner = '$user_id' AND deleted != 'Yes' ORDER BY id DESC";
									if (!isset($_GET['byme'])) {
										$query = "SELECT * FROM fileshare WHERE receivertype = 'User' AND receiver = '$user_id' AND deleted != 'Yes' ORDER BY id DESC";
									}
									if (isset($_GET['name'])) {
										$name = $_GET['name'];
										$query = "SELECT * FROM fileshare WHERE title like '%$name%' AND owner = '$user_id' AND deleted != 'Yes' ORDER BY id DESC";
										if (!isset($_GET['byme'])) {
											$query = "SELECT * FROM fileshare WHERE title like '%$name%' AND receivertype = 'User' AND receiver = '$user_id' AND deleted != 'Yes' ORDER BY id DESC";
										}
									}
									$query = $conn->query($query);
									while ($row = $query->fetch_assoc()) {
										//
										$query_user = "SELECT * FROM users WHERE id = '" . $row['receiver'] . "'";
										if (!isset($_GET['byme'])) {
											$query_user = "SELECT * FROM users WHERE id = '" . $row['owner'] . "'";
										}
										$query_user = $conn->query($query_user);
										$arr = $query_user->fetch_array();
										$names = $arr['names'];
										$role = $arr['role'];
										$user_photo = ($arr['photo'] == '') ? 'assets/img/user.jpg' : 'uploads/' . $arr['photo'];
										//
										?>
										<tr>
											<td>
												<img class="avatar" src="<?php echo $user_photo; ?>" alt="<?php echo $names; ?>">
												<h2><?php echo $names; ?> <span><?php echo $role; ?></span></h2>
											</td>
											<td>
												<strong><?php echo $row['title']; ?></strong><br>
												<?php if ($row['accesscode'] !== '' && isset($_GET['byme'])) { ?><span>Access code: </span> <strong><?php echo $row['accesscode']; ?></strong><?php } ?>
											</td>
											<td>
												<span><?php echo date('l, F d y , h:i:s', strtotime($row['date'])); ?></span>
												<div class="dropdown pull-right">
													<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
													<ul class="dropdown-menu pull-right">
														<li><a href="download.php?id=<?php echo $row['id']; ?>" title="Download"><i class="fa fa-download m-r-5"></i> Download</a></li>
														<li><a href="#" title="Delete" data-toggle="modal" data-target="#delete_file_<?php echo $row['id']; ?>"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
													</ul>
												</div>
											</td>
										</tr>
										<div id="delete_file_<?php echo $row['id']; ?>" class="modal custom-modal fade" role="dialog">
											<div class="modal-dialog">
												<div class="modal-content modal-md">
													<div class="modal-header">
														<h4 class="modal-title">Delete Document "<?php echo $row['title']; ?>"</h4>
													</div>
													<form>
														<div class="modal-body card-box">
															<p>Are you sure want to delete this document?</p>
															<div class="m-t-20"> <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
																<?php
																$byme = '';
																if (isset($_GET['byme'])) {
																	$byme = 'byme&';
																}
																?>
																<a href="shared-files.php?<?php echo $byme; ?>delete=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
															</div>
														</div>
													</form>
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
		<div id="upload" class="modal custom-modal fade" role="dialog">
			<div class="modal-dialog">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<div class="modal-content modal-md">
					<div class="modal-header">
						<h4 class="modal-title">Upload file</h4>
					</div>
					<div class="modal-body">
						<form method="POST" enctype="multipart/form-data">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>File Title</label>
										<input class="form-control" type="text" name="title">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>File</label>
										<input class="form-control" type="file" name="file">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<label>Shares to</label>
									<div class="form-group">
										<select name="shareto[]" multiple id="shareto" class="form-control" style="width: 100%;">
											<?php
											$query = "SELECT id, names, phone FROM users WHERE status = 'Active' AND deleted != 'Yes' AND id != '$user_id' ORDER BY names ASC, role DESC";
											$query = $conn->query($query);
											while ($row = $query->fetch_array()) {
												?>
												<option value="<?php echo $row['id'] ?>"><?php echo $row['names'] . " (" . $row['phone'] . ")"; ?></option>
											<?php
											}
											?>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Protect with access code</label>
										<select name="protect" class="select">
											<option>No</option>
											<option>Yes</option>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Urgent</label>
										<select name="urgent" class="select">
											<option>No</option>
											<option>Yes</option>
										</select>
									</div>
								</div>
							</div>
							<div class="m-t-20 text-center">
								<button name="upload" class="btn btn-primary">Upload</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="assets/js/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="assets/js/dataTables.bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/js/jquery.slimscroll.js"></script>
	<script type="text/javascript" src="assets/js/select2.min.js"></script>
	<script type="text/javascript" src="assets/js/moment.min.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript" src="assets/js/app.js"></script>
	<script>
		$('#shareto').select2();
	</script>
</body>

</html>
