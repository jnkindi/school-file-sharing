<?php include('session.php'); ?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
	<title>Home - School File Sharing</title>
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/line-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="assets/plugins/morris/morris.css">
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
				<div class="row">
					<div class="col-md-6 col-sm-6 col-lg-3">
						<div class="dash-widget clearfix card-box">
							<span class="dash-widget-icon"><i class="fa fa-archive" aria-hidden="true"></i></span>
							<div class="dash-widget-info">
								<?php
								$query = "SELECT COUNT(id) AS count FROM fileshare WHERE deleted != 'Yes'";
								$query = $conn->query($query);
								$arr = $query->fetch_array();
								$count = $arr['count'];
								?>
								<h3><?php echo number_format($count); ?></h3>
								<span>All shares</span>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-lg-3">
						<div class="dash-widget clearfix card-box">
							<span class="dash-widget-icon"><i class="fa fa-folder" aria-hidden="true"></i></span>
							<div class="dash-widget-info">
								<?php
								$query = "SELECT COUNT(id) AS count FROM fileshare WHERE owner = '$user_id' AND deleted != 'Yes'";
								$query = $conn->query($query);
								$arr = $query->fetch_array();
								$count = $arr['count'];
								?>
								<h3><?php echo number_format($count); ?></h3>
								<span>Shared by me</span>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-lg-3">
						<div class="dash-widget clearfix card-box">
							<span class="dash-widget-icon"><i class="fa fa-folder-open" aria-hidden="true"></i></span>
							<div class="dash-widget-info">
								<?php
								$query = "SELECT COUNT(id) AS count FROM fileshare WHERE receivertype = 'User' AND receiver = '$user_id' AND deleted != 'Yes'";
								$query = $conn->query($query);
								$arr = $query->fetch_array();
								$count = $arr['count'];
								?>
								<h3><?php echo number_format($count); ?></h3>
								<span>Shared with me</span>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-lg-3">
						<div class="dash-widget clearfix card-box">
							<span class="dash-widget-icon"><i class="fa fa-group" aria-hidden="true"></i></span>
							<div class="dash-widget-info">
								<?php
								$query = "SELECT COUNT(id) AS count FROM users WHERE deleted != 'Yes'";
								$query = $conn->query($query);
								$arr = $query->fetch_array();
								$count = $arr['count'];
								?>
								<h3><?php echo number_format($count); ?></h3>
								<span>All users</span>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="panel panel-table">
							<div class="panel-heading">
								<h3 class="panel-title">Recently shared with me</h3>
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-striped custom-table m-b-0">
										<thead>
											<tr>
												<th style="min-width:200px;">Sender</th>
												<th>Date</th>
												<th>Title</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$query = "SELECT * FROM fileshare WHERE receivertype = 'User' AND receiver = '$user_id' AND deleted != 'Yes' ORDER BY id DESC LIMIT 5";
											$query = $conn->query($query);
											while ($row = $query->fetch_assoc()) {
												//
												$query_user = "SELECT * FROM users WHERE id = '" . $row['owner'] . "'";
												$query_user = $conn->query($query_user);
												$arr = $query_user->fetch_array();
												$names = $arr['names'];
												$role = $arr['role'];
												$user_photo = ($arr['photo'] == '') ? 'assets/img/user.jpg' : 'uploads/' . $arr['photo'];
												//
												?>
												<tr>
													<td style="min-width:200px;">
														<img class="avatar" src="<?php echo $user_photo; ?>" alt="<?php echo $names; ?>">
														<h2><?php echo $names; ?> <span><?php echo $role; ?></span></h2>
													</td>
													<td><span><?php echo date('l, F d y', strtotime($row['date']));; ?></span></td>
													<td><span><?php echo $row['title']; ?></span></td>
												</tr>
											<?php
											}
											?>
										</tbody>
									</table>
								</div>
							</div>
							<div class="panel-footer">
								<a href="shared-files.php" class="text-primary">View all</a>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="panel panel-table">
							<div class="panel-heading">
								<h3 class="panel-title">Recently shared by me</h3>
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-striped custom-table m-b-0">
										<thead>
											<tr>
												<th style="min-width:200px;">Receiver</th>
												<th>Date</th>
												<th>Title</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$query = "SELECT * FROM fileshare WHERE owner = '$user_id' AND deleted != 'Yes' ORDER BY id DESC LIMIT 5";
											$query = $conn->query($query);
											while ($row = $query->fetch_assoc()) {
												//
												$query_user = "SELECT * FROM users WHERE id = '" . $row['receiver'] . "'";
												$query_user = $conn->query($query_user);
												$arr = $query_user->fetch_array();
												$names = $arr['names'];
												$role = $arr['role'];
												$user_photo = ($arr['photo'] == '') ? 'assets/img/user.jpg' : 'uploads/' . $arr['photo'];
												//
												?>
												<tr>
													<td style="min-width:200px;">
														<img class="avatar" src="<?php echo $user_photo; ?>" alt="<?php echo $names; ?>">
														<h2><?php echo $names; ?> <span><?php echo $role; ?></span></h2>
													</td>
													<td><span><?php echo date('l, F d y', strtotime($row['date'])); ?></span></td>
													<td><span><?php echo $row['title']; ?></span></td>
												</tr>
											<?php
											}
											?>
										</tbody>
									</table>
								</div>
							</div>
							<div class="panel-footer">
								<a href="shared-files.php?byme" class="text-primary">View all</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script data-cfasync="false" src="https://dreamguys.co.in/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
	<script type="text/javascript" src="assets/js/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/js/jquery.slimscroll.js"></script>
	<script type="text/javascript" src="assets/plugins/morris/morris.min.js"></script>
	<script type="text/javascript" src="assets/plugins/raphael/raphael-min.js"></script>
	<script type="text/javascript" src="assets/js/chart.js"></script>
	<script type="text/javascript" src="assets/js/app.js"></script>

</body>

</html>
