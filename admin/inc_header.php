<style>
	
@media (max-width: 768px){
	.navbar-brand{
		display: none;
	}
	#SideNav{
		display: block;
	}
}
</style>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top scrolling-navbar nav-top-bar" role="navigation">
	<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-ex1-collapse" aria-controls="navbar-ex1-collapse" aria-expanded="false" aria-label="Toggle navigation">
		<span CLASS="icon-bar"></span>
		<span CLASS="icon-bar"></span>
		<span CLASS="icon-bar"></span>
	</button>
	<!-- <a CLASS="navbar-brand" HREF="home.php"><img src="images/logo.png" height="35" /></a> -->
	
	<ul CLASS="nav top-nav ml-auto">
		<a CLASS="navbar-brand" HREF="<?php echo BASE_URL; ?>" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Visit MLM Portal"><i CLASS="fa fa-home top_home"></i> <?php if($configRow['logo'] != "") { ?>&nbsp;<img src="<?php echo IMG_MAIN_LOC.''.$validation->db_field_validate($configRow['logo']); ?>" style='background: #fff;' height="33" alt="Visit MLM Portal" title="<?php echo $validation->db_field_validate($configRow['meta_title']); ?>" class="top_logoimg" /><?php } ?></a>
		<li CLASS="nav-item dropdown">
			<a HREF="#" CLASS="nav-link dropdown-toggle" id="topbar_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php if($_SESSION['mlm_be_imgName'] != "") { ?><img src="<?php echo FILE_LOC.''.$_SESSION['mlm_be_imgName']; ?>" class="rounded-circle" height="15" /><?php } else { ?><i CLASS="fa fa-user"></i><?php } ?> &nbsp;<?php echo $_SESSION['mlm_be_display_name']; ?> <b CLASS="caret"></b></a>
			<div class="dropdown-menu dropdown-menu-right" aria-labelledby="topbar_dropdown">
				<?php if($_SESSION['mlm_be_type'] == "admin") { ?>
					<!-- <a class="dropdown-item" HREF="user_master_view.php"><i CLASS="fa fa-fw fa-user"></i> User Master</a> -->
					<a class="dropdown-item" HREF="config.php"><i class="fas fa-user-cog"></i> Configurations</a>
				<?php } ?>
				<a class="dropdown-item" HREF="user_password.php"><i CLASS="fa fa-fw fa-key"></i> Password</a>
				<div class="dropdown-divider"></div>
				<a class="dropdown-item" HREF="logout.php"><i CLASS="fa fa-fw fa-power-off"></i> Log Out</a>
			</div>
		</li>
	</ul>
	
	<div CLASS="collapse navbar-collapse navbar-ex1-collapse ml-auto">
		<ul CLASS="nav navbar-nav side-nav mr-auto" ID="SideNav">
			<?php if($_SESSION['per_read'] == "1") { ?>
				<li class="nav-item"><a HREF="home.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "dashboard") echo "active"; ?>"><i CLASS="fas fa-fw fa-tachometer-alt"></i>&nbsp; Dashboard</a></li>
				<!-- <li class="nav-item"><a HREF="plan_view.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "plan") echo "active"; ?>"><i CLASS="fa fa-tasks"></i>&nbsp; Plans</a></li> -->
				<li class="nav-item"><a HREF="project_view.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "project") echo "active"; ?>"><i class="fas fa-project-diagram"></i>&nbsp; Projects</a></li>
				<li class="nav-item"><a HREF="block_view.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "block") echo "active"; ?>"><i CLASS="fa fa-edit"></i>&nbsp; Blocks</a></li>
				 <li class="nav-item"><a HREF="plot_view.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "plot") echo "active"; ?>"><i CLASS="fa fa-home"></i>&nbsp; Plots</a></li>
				<!-- <li class="nav-item"><a HREF="plot_inventory.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "plot_inventory") echo "active"; ?>"><i class="fas fa-book"></i>&nbsp; Plots Inventory</a></li>
				<li class="nav-item"><a HREF="plot_inventory_view.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "plot_inventory_followup") echo "active"; ?>"><i class="fa fa-tasks"></i>&nbsp; Plots Inventory History</a></li>
				<li class="nav-item"><a HREF="map_view.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "map") echo "active"; ?>"><i CLASS="fa fa-map-marker"></i>&nbsp; Maps</a></li> -->
				<!-- <li class="nav-item"><a HREF="level_view.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "level") echo "active"; ?>"><i class="fa fa-tasks"></i>&nbsp; Levels</a></li> -->
				<!-- <li class="nav-item"><a HREF="reward_view.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "reward") echo "active"; ?>"><i class="fas fa-money-bill-alt"></i>&nbsp; Rewards</a></li> -->
				<!-- <li class="nav-item"><a HREF="challenge_view.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "challenge") echo "active"; ?>"><i class="fas fa-trophy"></i>&nbsp; Challenges</a></li> -->
				<!-- <li class="nav-item"><a HREF="challenge_history_view.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "challengehistory") echo "active"; ?>"><i class="fas fa-trophy"></i>&nbsp; Challenges History</a></li> -->
				<!-- <li class="nav-item"><a HREF="ewallet_view.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "ewallet") echo "active"; ?>"><i class="fas fa-wallet"></i>&nbsp; E-Wallet</a></li> -->
				<!-- <li class="nav-item"><a HREF="ewallet_monthwise_view.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "ewallet_monthwise") echo "active"; ?>"><i class="fas fa-wallet"></i>&nbsp; E-Wallet (Month & User wise)</a></li> -->
				<!-- <li class="nav-item"><a HREF="ewallet_request_view.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "ewallet_request") echo "active"; ?>"><i class="fas fa-money-check"></i>&nbsp; Payout</a></li> -->
				<!-- <li class="nav-item"><a HREF="site_visit_view.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "sitevisit") echo "active"; ?>"><i CLASS="fa fa-envelope"></i>&nbsp; Site Visit Request</a></li> -->
				<!-- <li class="nav-item"><a HREF="news_updates_view.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "news_updates") echo "active"; ?>"><i class="fas fa-rss"></i>&nbsp; News & Updates</a></li> -->
				<!-- <li class="nav-item"><a HREF="enquiry_view.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "enquiry") echo "active"; ?>"><i CLASS="fa fa-envelope"></i>&nbsp; Enquiries/Tickets</a></li> -->
				<li class="nav-item"><a HREF="register_view.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "register") echo "active"; ?>"><i class="fas fa-users"></i>&nbsp; Members</a></li>
				<!-- <li class="nav-item"><a HREF="user_master_view.php" class="nav-link <?php if(@$_SE/SSION['active_menu'] == "user_master") echo "active"; ?>"><i class="fa fa-user"></i>&nbsp; User Master</a></li> -->
				<li class="nav-item"><a HREF="user_password.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "user_password") echo "active"; ?>"><i class="fa fa-key"></i>&nbsp; Change Password</a></li>
				<li class="nav-item"><a HREF="logout.php" class="nav-link"><i class="fa fa-power-off"></i>&nbsp; Log Out</a></li>
			<?php } ?>
		</ul>
	</div>
</nav>