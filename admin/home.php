<?php
include_once("inc_config.php");
include_once("login_user_check.php");

$_SESSION['active_menu'] = "dashboard";

$registerQueryResult = $db->view('regid', 'mlm_registrations', 'regid');
$registerCount = $registerQueryResult['num_rows'];

$creditResult = $db->view("SUM(wallet_total) as total_credit_amount", "mlm_registrations", "regid", "");
$creditRow = $creditResult['result'][0];

$debitResult = $db->view("SUM(total_debit) as total_debit_amount", "mlm_registrations", "regid", "");
$debitRow = $debitResult['result'][0];

$pendingResult = $db->view("SUM(balance_amount) as total_pending", "mlm_plots_inventory", "inventoryid", "");
$pendingRow = $pendingResult['result'][0];

$siteVistQueryResult = $db->view('sitevisitid', 'mlm_site_visit', 'sitevisitid', " and read_check='0'");
$sitevisitCount = $siteVistQueryResult['num_rows'];

$plotResult = $db->view('plotid', 'mlm_plots', 'plotid');
$plotCount = $plotResult['num_rows'];

$newenquiryQueryResult = $db->view('enquiryid', 'mlm_enquiries', 'enquiryid', "and read_check='0'");
$newenquiryCount = $newenquiryQueryResult['num_rows'];
?>
<!DOCTYPE html>
<html LANG="en">
<head>
<?php include_once("inc_title.php"); ?>
<?php include_once("inc_files.php"); ?>
</head>
<body>
<div ID="wrapper">
<?php include_once("inc_header.php"); ?>
<div ID="page-wrapper">
<div CLASS="container-fluid">
	<div CLASS="row">
		<div CLASS="col-lg-12">
			<h1 CLASS="page-header">Dashboard</h1>
		</div>
	</div>
	<br>
	<div CLASS="row">
		
		<!-- <div CLASS="col-lg-4 col-md-6 mb-4">
			<div CLASS="card card-green">
				<div CLASS="card-heading">
					<div CLASS="row">
						<div CLASS="col-3">
							<i CLASS="fas fa-wallet fa-5x"></i>
						</div>
						<div CLASS="col-9 text-right">
							<div CLASS="huge">&#8377;<?php echo $validation->price_format($creditRow['total_credit_amount']); ?></div>
							<div>Total Credit!</div>
						</div>
					</div>
				</div>
				<a HREF="javascript:void(0);">
					<div CLASS="card-footer">
						<span CLASS="float-left">View All</span>
						<span CLASS="float-right"><i CLASS="fa fa-arrow-circle-right"></i></span>
						<div CLASS="clearfix"></div>
					</div>
				</a>
			</div>
		</div> -->
		<!-- <div CLASS="col-lg-4 col-md-6 mb-4">
			<div CLASS="card card-yellow">
				<div CLASS="card-heading">
					<div CLASS="row">
						<div CLASS="col-3">
							<i CLASS="fas fa-wallet fa-5x"></i>
						</div>
						<div CLASS="col-9 text-right">
							<div CLASS="huge">&#8377;<?php echo $validation->price_format($debitRow['total_debit_amount']); ?></div>
							<div>Total Debit!</div>
						</div>
					</div>
				</div>
				<a HREF="javascript:void(0);">
					<div CLASS="card-footer">
						<span CLASS="float-left">View All</span>
						<span CLASS="float-right"><i CLASS="fa fa-arrow-circle-right"></i></span>
						<div CLASS="clearfix"></div>
					</div>
				</a>
			</div>
		</div> -->
		<!-- <div CLASS="col-lg-4 col-md-6 mb-4">
			<div CLASS="card card-red">
				<div CLASS="card-heading">
					<div CLASS="row">
						<div CLASS="col-3">
							<i CLASS="fas fa-wallet fa-5x"></i>
						</div>
						<div CLASS="col-9 text-right">
							<div CLASS="huge">&#8377;<?php echo $validation->price_format($pendingRow['total_pending']); ?></div>
							<div>Total Pending!</div>
						</div>
					</div>
				</div>
				<a HREF="javascript:void(0);">
					<div CLASS="card-footer">
						<span CLASS="float-left">View All</span>
						<span CLASS="float-right"><i CLASS="fa fa-arrow-circle-right"></i></span>
						<div CLASS="clearfix"></div>
					</div>
				</a>
			</div>
		</div> -->
		<div CLASS="col-lg-3 col-md-6 mb-4">
			<div CLASS="card card-blue">
				<div CLASS="card-heading">
					<div CLASS="row">
						<div CLASS="col-3">
							<i CLASS="fas fa-users fa-5x"></i>
						</div>
						<div CLASS="col-9 text-right">
							<div CLASS="huge"><?php echo $registerCount; ?></div>
							<div>Total Members!</div>
						</div>
					</div>
				</div>
				<a HREF="register_view.php">
					<div CLASS="card-footer">
						<span CLASS="float-left">View All</span>
						<span CLASS="float-right"><i CLASS="fa fa-arrow-circle-right"></i></span>
						<div CLASS="clearfix"></div>
					</div>
				</a>
			</div>
		</div>
		<!-- <div CLASS="col-lg-3 col-md-6 mb-4">
			<div CLASS="card card-red">
				<div CLASS="card-heading">
					<div CLASS="row">
						<div CLASS="col-3">
							<i CLASS="fa fa-envelope fa-5x"></i>
						</div>
						<div CLASS="col-9 text-right">
							<div CLASS="huge"><?php echo $newenquiryCount; ?></div>
							<div>New Enquiries!</div>
						</div>
					</div>
				</div>
				<a HREF="enquiry_view.php?new=true">
					<div CLASS="card-footer">
						<span CLASS="float-left">View All</span>
						<span CLASS="float-right"><i CLASS="fa fa-arrow-circle-right"></i></span>
						<div CLASS="clearfix"></div>
					</div>
				</a>
			</div>
		</div> -->
		<!-- <div CLASS="col-lg-3 col-md-6 mb-4">
			<div CLASS="card card-green">
				<div CLASS="card-heading">
					<div CLASS="row">
						<div CLASS="col-3">
							<i CLASS="fa fa-envelope fa-5x"></i>
						</div>
						<div CLASS="col-9 text-right">
							<div CLASS="huge"><?php echo $sitevisitCount; ?></div>
							<div>New Site Visit Request!</div>
						</div>
					</div>
				</div>
				<a HREF="site_visit_view.php">
					<div CLASS="card-footer">
						<span CLASS="float-left">View All</span>
						<span CLASS="float-right"><i CLASS="fa fa-arrow-circle-right"></i></span>
						<div CLASS="clearfix"></div>
					</div>
				</a>
			</div>
		</div> -->
		<div CLASS="col-lg-3 col-md-6 mb-4">
			<div CLASS="card card-green">
				<div CLASS="card-heading">
					<div CLASS="row">
						<div CLASS="col-3">
							<i CLASS="fa fa-home fa-5x"></i>
						</div>
						<div CLASS="col-9 text-right">
							<div CLASS="huge"><?php echo $plotCount; ?></div>
							<div>Total Plots</div>
						</div>
					</div>
				</div>
				<a HREF="plot_view.php">
					<div CLASS="card-footer">
						<span CLASS="float-left">View All</span>
						<span CLASS="float-right"><i CLASS="fa fa-arrow-circle-right"></i></span>
						<div CLASS="clearfix"></div>
					</div>
				</a>
			</div>
		</div>

		
	</div>
</div>
</div>
</div>
</body>
</html>