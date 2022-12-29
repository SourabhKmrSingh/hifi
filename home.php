<?php
include_once("inc_config.php");
include_once("login_user_check.php");
include_once("inc_downline.php");

$_SESSION['active_menu'] = "dashboard";
$membership_id = $_SESSION['mlm_membership_id'];

$registerResult = $db->view('first_name,last_name,membership_id,sponsor_id,regid,members,wallet_total,wallet_money, status, rewardid', 'mlm_registrations', 'regid', "and regid = '$regid'");
$registerRow = $registerResult['result'][0];

$enquiryResult = $db->view('enquiryid', 'mlm_enquiries', 'enquiryid', "and regid = '$regid'");
$enquiryCount = $enquiryResult['num_rows'];

$inventorytotalResult = $db->view("SUM(amount) as paid_amount", "mlm_plots_inventory_history", "historyid", " and regid='{$regid}'");
$inventorytotalRow = $inventorytotalResult['result'][0];
$paid_amount = $inventorytotalRow['paid_amount'];

$balanceAmountResult = $db->view("sum(balance_amount) as BalanceAmount", "mlm_plots_inventory", "inventoryid", " and status ='active' and regid ='$regid'");
$balanceAmount = $balanceAmountResult['result'][0];

?>
<!DOCTYPE html>
<html LANG="en">

<head>
	<?php include_once("inc_title.php"); ?>
	<?php include_once("inc_files.php"); ?>
	<style>
		.card_custom {
			box-shadow: 0 2px 20px rgba(65, 131, 215, 0.22), 0 2px 11px rgba(65, 131, 215, 0.15);
			background: #FFFFFF;
		}
	</style>
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

				<?php

				$birthMessageResult = $db->view("*", "mlm_registrations", "regid", " and status = 'active' and date_of_birth = '$createdate'");

				// $birthMessageResult['num_rows'] >= 1
				if (0) {

					$birthdayMessage = "";

					foreach ($birthMessageResult['result'] as $birthMessageRow) {
						$birthdayMessage .= $birthMessageRow['first_name'] . " " . $birthMessageRow['last_name'] . ", ";
					}

					$birthdayMessage = trim($birthdayMessage, " ,");

					$birthdayMessage .= " has there birthday today.";

				?>

					<div class="row">
						<div class="col-sm-12">
							<div class="ticker-wrap">
								<div class="ticker">
									<div class="ticker__item"><?php echo $validation->db_field_validate($birthdayMessage);?></div>
								</div>
							</div>
						</div>
					</div> 

				<?php } ?>

				<br/>
				<?php if ($_SESSION['mlm_account_number'] == "" || $_SESSION['mlm_document'] == "" and $_SESSION['mlm_rewardid'] != "1" && 0) { ?>
					<div CLASS="row mb-3">
						<div CLASS="col-12 text-center">
							<p class="font-weight-bold">
								<font color="red"><?php if ($_SESSION['mlm_account_number'] == "") echo "Bank Details,"; if ($_SESSION['mlm_document'] == "") echo " KYC Details"; ?> are mandatory otherwise you will not get any amount in your account. Please complete your profile from <a href="profile.php">here</a></font>
							</p>
						</div>
					</div>
				<?php } ?>

				<div CLASS="row">
					<div CLASS="col-lg-4 col-md-6 mb-3 mb-md-0 ">
						<div class="card overflow-hidden" style="max-height: 165px;">
							<div class="card-body">
								<div class="row">
									<div class="col">
										<h6 class=""><?php echo $validation->db_field_validate($registerRow['first_name'] . ' ' . $registerRow['last_name']); ?></h6>
										<h6 class="mb-2 number-font">Membership ID: <?php echo $validation->db_field_validate($registerRow['membership_id']); ?></h6>
										<p class="text-muted mb-0">
											<span class="">Sponsor ID: <?php echo $validation->db_field_validate($registerRow['sponsor_id']); ?></span>
										</p>
										<p class="text-muted mb-0">
											<span class="">Status: <?php echo $validation->db_field_validate(ucfirst($registerRow['status'])); ?></span>&nbsp;&nbsp;&nbsp;

											<?php if ($registerRow['rewardid'] != "" && $registerRow['rewardid'] > 0) {
												$rewardid = $registerRow['rewardid'];
												$rewardResult = $db->view("title", "mlm_rewards", 'rewardid', " and rewardid = '{$rewardid}'");
												$rewardRow = $rewardResult['result'][0];
											?>
												<span style='white-space: nowrap;'>Designation: <?php echo $validation->db_field_validate(ucfirst($rewardRow['title'])); ?></span>
											<?php } ?>

										</p>

									</div>
									<div class="col col-auto">
										<?php if ($_SESSION['mlm_imgName'] != "") { ?>
											<div class="d-flex">
												<img src="<?php echo FILE_LOC . '' . $_SESSION['mlm_imgName']; ?>" height="70" />
											</div>
										<?php } else { ?>
											<div class="counter-icon bg-secondary-gradient box-shadow-secondary brround ">
												<i CLASS="fa fa-user fa-1x text-light"></i>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div><br>

					<?php
					$newsUpdateResult = $db->view("*", "mlm_news_updates", "newsid", " and status = 'active'");

					if ($newsUpdateResult['num_rows'] >= 1) {

					?>

						<div class="col-sm-12">
							<h4>News & Updates</h4>
							<hr style='border: 1px solid #e8e8e8;' />
						</div>

						<div class="">
							<div class="holder">
								<ul id="ticker01">
									<?php foreach ($newsUpdateResult['result'] as $newsUpdateRow) { ?>
										<li><span><?php echo $validation->db_field_validate($newsUpdateRow['title']); ?> - </span><a href="#"> <?php echo $validation->db_field_validate($newsUpdateRow['message']); ?></a></li>
									<?php } ?>
								</ul>
							</div>

							
						</div>

					<?php } ?>
					<?php if ($_SESSION['mlm_rewardid'] != "1") { ?>
						<!-- <div CLASS="col-lg-4 col-md-6 mb-4">
			<div CLASS="card card-green">
				<div CLASS="card-heading">
					<div CLASS="row">
						<div CLASS="col-3">
							<i CLASS="fas fa-wallet fa-5x"></i>
						</div>
						<div CLASS="col-9 text-right">
							<div CLASS="huge">&#8377;<?php echo $validation->price_format($registerRow['wallet_total']); ?></div>
							<div>Total Credits!</div>
						</div>
					</div>
				</div>
				<a HREF="ewallet_view.php?type=credit">
					<div CLASS="card-footer">
						<span CLASS="float-left">View All</span>
						<span CLASS="float-right"><i CLASS="fa fa-arrow-circle-right"></i></span>
						<div CLASS="clearfix"></div>
					</div>
				</a>
			</div>
		</div>
		<div CLASS="col-lg-4 col-md-6 mb-4">
			<div CLASS="card card-yellow">
				<div CLASS="card-heading">
					<div CLASS="row">
						<div CLASS="col-3">
							<i CLASS="fas fa-wallet fa-5x"></i>
						</div>
						<div CLASS="col-9 text-right">
							<div CLASS="huge">&#8377;<?php echo $validation->price_format($registerRow['wallet_money']); ?></div>
							<div>Wallet Balance!</div>
						</div>
					</div>
				</div>
				<a HREF="ewallet_view.php">
					<div CLASS="card-footer">
						<span CLASS="float-left">View All</span>
						<span CLASS="float-right"><i CLASS="fa fa-arrow-circle-right"></i></span>
						<div CLASS="clearfix"></div>
					</div>
				</a>
			</div>
		</div>

		
		<div CLASS="col-lg-4 col-md-6 mb-3 mb-md-0">
			<div CLASS="card card-blue">
				<div CLASS="card-heading">
					<div CLASS="row">
						<div CLASS="col-3">
							<i CLASS="fa fa-network-wired fa-5x"></i>
						</div>
						<div CLASS="col-9 text-right">
							<div CLASS="huge"><?php echo count(explode(",", $members)); ?></div>
							<div>Downline Members!</div>
						</div> 	
					</div>
				</div>
				<a HREF="genealogy.php">
					<div CLASS="card-footer">
						<span CLASS="float-left">View All</span>
						<span CLASS="float-right"><i CLASS="fa fa-arrow-circle-right"></i></span>
						<div CLASS="clearfix"></div>
					</div>
				</a>
			</div>
		</div> -->



					<?PHP } ?>


					<!-- <div CLASS="col-lg-4 col-md-6 mb-4">
			<div CLASS="card card-green">
				<div CLASS="card-heading">
					<div CLASS="row">
						<div CLASS="col-3">
							<i CLASS="fas fa-wallet fa-5x"></i>
						</div>
						<div CLASS="col-9 text-right">
							<div CLASS="huge">&#8377;<?php echo $validation->price_format($paid_amount); ?></div>
							<div>Total Payment!</div>
						</div>
					</div>
				</div>
				<a HREF="ewallet_view.php">
					<div CLASS="card-footer">
						<span CLASS="float-left">View All</span>
						<span CLASS="float-right"><i CLASS="fa fa-arrow-circle-right"></i></span>
						<div CLASS="clearfix"></div>
					</div>
				</a>
			</div>
		</div>


		<div CLASS="col-lg-4 col-md-6 mb-4">
			<div CLASS="card card-yellow">
				<div CLASS="card-heading">
					<div CLASS="row">
						<div CLASS="col-3">
							<i CLASS="fas fa-wallet fa-5x"></i>
						</div>
						<div CLASS="col-9 text-right">
							<div CLASS="huge">&#8377;<?php echo $validation->price_format($balanceAmount['BalanceAmount']); ?></div>
							<div>Total Balance!</div>
						</div>
					</div>
				</div>
				<a HREF="ewallet_view.php">
					<div CLASS="card-footer">
						<span CLASS="float-left">View All</span>
						<span CLASS="float-right"><i CLASS="fa fa-arrow-circle-right"></i></span>
						<div CLASS="clearfix"></div>
					</div>
				</a>
			</div>
		</div>

		<div CLASS="col-lg-4 col-md-6 mb-3 mb-md-0">
			<div CLASS="card card-red">
				<div CLASS="card-heading">
					<div CLASS="row">
						<div CLASS="col-3">
							<i CLASS="fa fa-envelope fa-5x"></i>
						</div>
						<div CLASS="col-9 text-right">
							<div CLASS="huge"><?php echo $enquiryCount; ?></div>
							<div>Enquiries/Tickets!</div>
						</div>
					</div>
				</div>
				<a HREF="enquiry_view.php">
					<div CLASS="card-footer">
						<span CLASS="float-left">View All</span>
						<span CLASS="float-right"><i CLASS="fa fa-arrow-circle-right"></i></span>
						<div CLASS="clearfix"></div>
					</div>
				</a>
			</div>
		</div> -->
					<?php if ($_SESSION['mlm_rewardid'] != "1") { ?>

						<!-- <div CLASS="col-lg-4 col-md-6 mb-3 mb-md-0">
			<div CLASS="card card-info">
				<div CLASS="card-heading bg-info text-light ">
					<div CLASS="row d-flex justify-content-center align-items-center">
						<div CLASS="col-3">
							<i class="fas fa-id-card-alt fa-5x"></i>
						</div>
						<div CLASS="col-9 text-right">
							<div CLASS="huge mb-0 pb-0">ICard</div>
						</div>
					</div>
				</div>
				<a HREF="icard.php">
					<div CLASS="card-footer text-info border-info">
						<span CLASS="float-left">Download</span>
						<span CLASS="float-right"><i CLASS="fa fa-arrow-circle-right"></i></span>
						<div CLASS="clearfix"></div>
					</div>
				</a>
			</div>
		</div>
		<div CLASS="col-lg-4 col-md-6 mb-3 mb-md-0">
			<div CLASS="card card-blue">
				<div CLASS="card-heading text-light">
					<div CLASS="row d-flex justify-content-center align-items-center">
						<div CLASS="col-3">
							<i class="fas fa-file-invoice fa-5x"></i>
						</div>
						<div CLASS="col-9 text-right">
							<div CLASS="huge mb-0 pb-0">Welcome letter</div>
						</div>
					</div>
				</div>
				<a HREF="welcome_letter.php?q=<?php echo $membership_id; ?>">
					<div CLASS="card-footer text-info">
						<span CLASS="float-left">Download</span>
						<span CLASS="float-right"><i CLASS="fa fa-arrow-circle-right"></i></span>
						<div CLASS="clearfix"></div>
					</div>
				</a>
			</div>
		</div>
		<div CLASS="col-lg-4 col-md-6 mb-3 mb-md-0">
			<div CLASS="card card-blue">
				<div CLASS="card-heading text-light">
					<div CLASS="row d-flex justify-content-center align-items-center">
						<div CLASS="col-3">
							<i class="fas fa-file-invoice fa-5x"></i>
						</div>
						<div CLASS="col-9 text-right">
							<div CLASS="huge mb-0 pb-0">E Visiting Card</div>
						</div>
					</div>
				</div>
				<a HREF="e-visiting-card.php?q=<?php echo $membership_id; ?>">
					<div CLASS="card-footer text-info">
						<span CLASS="float-left">Download</span>
						<span CLASS="float-right"><i CLASS="fa fa-arrow-circle-right"></i></span>
						<div CLASS="clearfix"></div>
					</div>
				</a>
			</div>
		</div> -->
					<?php } ?>
					<?php

					$referral_link = BASE_URL . 'register' . SUFFIX . '?id=' . $validation->db_field_validate($_SESSION['mlm_membership_id']);

					?>
					<!-- <div class="col-sm-12 d-flex justify-content-center">
			<p class="text">Referral link : <a href="<?php echo $referral_link; ?>" target="_blank"><?php echo $referral_link; ?></a>&nbsp;&nbsp; - &nbsp;&nbsp;
			<a href='#' class='btn btn-info' onClick='copyLink("<?php echo $referral_link; ?>");'><i class="fas fa-copy"></i></a>
			<a href="https://api.whatsapp.com/send?text=<?php echo $referral_link; ?>" class='btn btn-success' target='_blank'><i class="fab fa-whatsapp " ></i></a>
			<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $referral_link; ?>" class='btn btn-primary' target="_blank">
				<i class="fab fa-facebook" ></i>
			</a>
			</p>
		</div> -->
				</div>
			</div>
		</div>
	</div>
	<script>
		function copyLink(str) {
			const el = document.createElement('textarea');
			el.value = str;
			document.body.appendChild(el);
			el.select();
			document.execCommand('copy');
			document.body.removeChild(el);
			$.notify("Link Copied!", {
				className: 'success',
				autoHide: true,
				autoHideDelay: 1000
			});
		}
	</script>
</body>

</html>