<?php
include('session.php');
if (isset($_GET['id'])) {
	$file = $_GET['id'];
	$query = "SELECT * FROM fileshare WHERE id = '$file'";
	$query = $conn->query($query);
	$arr = $query->fetch_array();
	$filetitle = $arr['title'];
	$filename = $arr['file'];
	if ($arr['accesscode'] == '') {
		//
		$filename = "uploads/" . $filename;
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private', false); // required for certain browsers
		header('Content-Type: application/pdf');
		header('Content-Disposition: attachment; filename="' . basename($filename) . '";');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . filesize($filename));
		readfile($filename);
		exit;
	}
	//
} else {
	header('Location: shared-files.php');
	return;
}

if (isset($_POST['download'])) {
	$code = $_POST['code'];
	$query = "SELECT * FROM fileshare WHERE id = '$file' AND accesscode = '$code'";
	$query = $conn->query($query);
	$arr = $query->fetch_array();
	if ($query->num_rows == 1) {
		//
		$filename = "uploads/" . $filename;
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private', false); // required for certain browsers
		header('Content-Type: application/pdf');
		header('Content-Disposition: attachment; filename="' . basename($filename) . '";');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . filesize($filename));
		readfile($filename);
		exit;
	} else {
		header('Location: download.php?invalid&id=' . $file);
	}
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
	<title>Download - School File Sharing</title>
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>

<body>
	<div class="main-wrapper">
		<div class="account-page">
			<div class="container">
				<h3 class="account-title">Download "<?php echo $filetitle; ?>"</h3>
				<div class="account-box">
					<div class="account-wrapper">
						<?php if (isset($_GET['invalid'])) { ?>
							<div class="alert alert-danger alert-dismissable"><button type="button" data-dismiss="alert" aria-hidden="true" class="close">×</button></span><strong>Oops!</strong> invalid access code</div>
						<?php } ?>
						<form method="POST">
							<div class="form-group form-focus">
								<label class="control-label">Access code</label>
								<input name="code" class="form-control floating" type="text">
							</div>
							<div class="form-group text-center">
								<button name="download" class="btn btn-primary btn-block account-btn" type="submit">Download</button>
							</div>
							<div class="text-center">
								<a href="shared-files.php">Back</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="assets/js/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/js/app.js"></script>
</body>

</html>
