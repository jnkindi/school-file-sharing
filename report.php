<?php include('session.php'); ?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
	<title>Report - School File Sharing</title>
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/line-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/select2.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap-datetimepicker.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>

<body>
	<div class="main-wrapper">
		<?php include('header.php'); ?>
		<div class="page-wrapper">
			<div class="content container-fluid">
				<div class="row">
					<div class="col-sm-8">
						<h4 class="page-title">Report</h4>
					</div>
					<div class="col-sm-4 text-right m-b-30">
						<div class="btn-group btn-group-sm">
							<!-- <button class="btn btn-default" onclick="printReport()"><i class="fa fa-print fa-lg"></i> Print</button> -->
						</div>
					</div>
				</div>
				<div class="row filter-row">
					<div class="col-sm-6">
						<form>
							<div class="col-sm-12 col-md-4 col-xs-6">
								<div class="form-group form-focus">
									<label class="control-label">From</label>
									<div class="cal-icon"><input name="from" required class="form-control floating datetimepicker" type="text"></div>
								</div>
							</div>
							<div class="col-sm-12 col-md-4 col-xs-6">
								<div class="form-group form-focus">
									<label class="control-label">To</label>
									<div class="cal-icon"><input name="to" required class="form-control floating datetimepicker" type="text"></div>
								</div>
							</div>
							<div class="col-sm-12 col-md-4 col-xs-6">
								<button type="submit" class="btn btn-success btn-block"> Search </button>
							</div>
						</form>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="panel" id="reportFull">
							<div class="panel-body">
								<div class="row">
									<div class="col-md-6 m-b-20">
										<img src="assets/img/logo2.png" class="m-b-20" alt="" style="width: 100px;">
										<ul class="list-unstyled">
											<li>University of Kigali</li>
											<li><strong>Phone Number</strong>: 0000000000</li>
											<li><strong>Address</strong>: Kigali</li>
											<li><strong>Email</strong>: info@uok.ac.rw</li>
										</ul>
									</div>
									<div class="col-md-6 m-b-20">
										<div class="invoice-details">
											<h3 class="text-uppercase">Share report</h3>
											<?php
											if (isset($_GET['from']) && isset($_GET['to'])) {
												$from = $_GET['from'];
												$to = $_GET['to'];
												?>
												<ul class="list-unstyled">
													<li>From: <span><?php echo $from; ?></span></li>
													<li>To: <span><?php echo $to; ?></span></li>
												</ul>
											<?php
											}
											?>
										</div>
									</div>
								</div>
								<div class="table-responsive">
									<table class="table table-striped table-hover">
										<thead>
											<tr>
												<th>#</th>
												<th>Shared by</th>
												<th>Shared to</th>
												<th>Title</th>
												<th>Date</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$query = "SELECT * FROM fileshare WHERE owner = '$user_id' AND deleted != 'Yes' ORDER BY id DESC";
											if (isset($_GET['from']) && isset($_GET['to'])) {
												$from = date('Y-m-d', strtotime($_GET['from']));
												$to = date('Y-m-d', strtotime($_GET['to']));
												$query = "SELECT * FROM fileshare WHERE date BETWEEN '$from' AND '$to' AND owner = '$user_id' AND deleted != 'Yes' ORDER BY id DESC";
											}
											$query = $conn->query($query);
											$no = 1;
											while ($row = $query->fetch_assoc()) {
												$query_receiver = "SELECT * FROM users WHERE id = '" . $row['receiver'] . "'";
												$query_receiver = $conn->query($query_receiver);
												$arr_receiver = $query_receiver->fetch_array();
												?>
												<tr>
													<td><?php echo $no; ?></td>
													<td><?php echo $names; ?></td>
													<td><?php echo $arr_receiver['names']; ?></td>
													<td><?php echo $row['title']; ?></td>
													<td><?php echo date('l, F d y , h:i:s', strtotime($row['date'])); ?></td>
												</tr>
												<?php
												$no++;
											}
											?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
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
		function printReport() {
			//
		}
	</script>
</body>

</html>
