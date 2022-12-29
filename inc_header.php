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
	<!--<a CLASS="navbar-brand" HREF="home.php"><img src="admin/images/logo.png" height="35" /></a>-->
	
	<ul CLASS="nav top-nav ml-auto">
		<a CLASS="navbar-brand" HREF="<?php echo BASE_URL_WEB; ?>" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Website"><i CLASS="fa fa-home top_home"></i> <?php if($configRow['logo'] != "") { ?>&nbsp;<img src="<?php echo IMG_MAIN_LOC.''.$validation->db_field_validate($configRow['logo']); ?>" height="33" style='background: #fff;' alt="Website" title="<?php echo $validation->db_field_validate($configRow['meta_title']); ?>" class="top_logoimg" /><?php } ?></a>
		<li CLASS="nav-item dropdown">
			<a HREF="#" CLASS="nav-link dropdown-toggle" id="topbar_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php if($_SESSION['mlm_imgName'] != "") { ?><img src="<?php echo FILE_LOC.''.$_SESSION['mlm_imgName']; ?>" class="rounded-circle" height="15" /><?php } else { ?><i CLASS="fa fa-user"></i><?php } ?> &nbsp;<?php echo $_SESSION['mlm_membership_id']; ?> <b CLASS="caret"></b></a>
			<div class="dropdown-menu dropdown-menu-right" aria-labelledby="topbar_dropdown">
				<a class="dropdown-item" HREF="user_password.php"><i CLASS="fa fa-fw fa-key"></i> Password</a>
				<div class="dropdown-divider"></div>
				<a class="dropdown-item" HREF="logout.php"><i CLASS="fa fa-fw fa-power-off"></i> Log Out</a>
			</div>
		</li>
	</ul>
	
	<div CLASS="collapse navbar-collapse navbar-ex1-collapse ml-auto">
		<ul CLASS="nav navbar-nav side-nav mr-auto" ID="SideNav">
			<li class="nav-item"><a HREF="home.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "dashboard") echo "active"; ?>"><i CLASS="fas fa-fw fa-tachometer-alt"></i>&nbsp; Dashboard</a></li>

			<li>
				<a href="#submenu1" data-toggle="collapse" style='background-color:#00305c;' aria-expanded="false" class="list-group-item list-group-item-action flex-column align-items-start <?php if(@$_SESSION['active_menu'] == "profile") echo "active"; ?> border-0">
					<div class="d-flex w-100 justify-content-start align-items-center">
						<span class="menu-collapsed"><i class="fas fa-users"></i>&nbsp; Profile Details</span>
						<span class="submenu-icon ml-auto"><i class="fas fa-chevron-down"></i></span>
					</div>
				</a>
				<div id='submenu1' class="collapse sidebar-submenu">
					<a HREF="profile.php" class="list-group-item list-group-item-action bg-dark text-white ">
						<span class="menu-collapsed"><i class="fas fa-users"></i>&nbsp; Profile</span>
					</a>
					<?PHP if($_SESSION['mlm_rewardid'] != "1") { ?>
					<!-- <a HREF="icard.php" class="list-group-item list-group-item-action bg-dark text-white ">
						<span class="menu-collapsed"><i class="fas fa-id-card-alt"></i>&nbsp; ICard</span>
					</a>
					<a HREF="welcome_letter.php?q=<?php echo $membership_id;?>" class="list-group-item list-group-item-action bg-dark text-white ">
						<span class="menu-collapsed"><i class="fas fa-file-invoice"></i>&nbsp; Welcome letter</span>
					</a>
					<a HREF="e-visiting-card.php?q=<?php echo $membership_id;?>" class="list-group-item list-group-item-action bg-dark text-white ">
						<span class="menu-collapsed"><i class="fas fa-file-invoice"></i>&nbsp; E Visiting Card</span>
					</a> -->
					<?PHP }?>
				</div>
			</li>
		
			<?php if($_SESSION['mlm_rewardid'] != "1") { ?>
				<!-- <li class="nav-item"><a HREF="genealogy.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "genealogy") echo "active"; ?>"><i class="fas fa-network-wired"></i>&nbsp; My Group Tree</a></li>
				<li class="nav-item"><a HREF="direct_member_view.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "direct_member") echo "active"; ?>"><i class="fas fa-network-wired"></i>&nbsp; My Direct</a></li>
				<li class="nav-item"><a HREF="downline_member_view.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "downline_member") echo "active"; ?>"><i class="fas fa-network-wired"></i>&nbsp; Downline Members</a></li> -->
			<?php } ?>
			<!-- <li class="nav-item"><a HREF="plot_inventory.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "plot_inventory") echo "active"; ?>"><i class="fas fa-book"></i>&nbsp; Plots Inventory</a></li> -->
			<!-- <li class="nav-item"><a HREF="plot_inventory_view.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "plot_inventory_followup") echo "active"; ?>"><i class="fa fa-tasks"></i>&nbsp; My Plot History</a></li> -->
			<?php if($_SESSION['mlm_rewardid'] != "1") { ?>
				<!-- <li class="nav-item"><a HREF="plot_inventory_team_view.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "plot_inventory_team_followup") echo "active"; ?>"><i class="fa fa-tasks"></i>&nbsp; Group Plot History</a></li> -->
			<?php } ?>
			<!-- <li class="nav-item"><a HREF="map_view.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "map") echo "active"; ?>"><i CLASS="fa fa-map-marker"></i>&nbsp; Maps</a></li> -->
			<?php if($_SESSION['mlm_rewardid'] > "1" && $_SESSION['mlm_rewardid'] != "") { ?>
				<!-- <li class="nav-item"><a HREF="reward_report.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "rewardReport") echo "active"; ?>"><i class="fas fa-wallet"></i>&nbsp; Reward Report</a></li> -->
				<!-- <li class="nav-item"><a HREF="ewallet_view.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "ewallet") echo "active"; ?>"><i class="fas fa-wallet"></i>&nbsp; My Account</a></li> -->
				<!-- <li class="nav-item"><a HREF="ewallet_request_view.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "ewallet_request") echo "active"; ?>"><i class="fas fa-money-check"></i>&nbsp; Passbook Entry</a></li> -->
				<!-- <li class="nav-item"><a HREF="ewallet_monthwise_view.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "ewallet_monthwise") echo "active"; ?>"><i class="fas fa-wallet"></i>&nbsp; Monthly Reports</a></li> -->
				<!-- <li class="nav-item"><a HREF="site_visit_view.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "siteVisit") echo "active"; ?>"><i CLASS="fa fa-envelope"></i>&nbsp; Site Visit Requests</a></li> -->
			<?php  } ?>
			<!-- <li class="nav-item"><a HREF="enquiry_view.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "enquiry") echo "active"; ?>"><i CLASS="fa fa-envelope"></i>&nbsp; Enquiries/Tickets</a></li> -->
			<li class="nav-item"><a HREF="user_password.php" class="nav-link <?php if(@$_SESSION['active_menu'] == "user_password") echo "active"; ?>"><i class="fa fa-key"></i>&nbsp; Change Password</a></li>
			<li class="nav-item"><a HREF="logout.php" class="nav-link"><i class="fa fa-power-off"></i>&nbsp; Log Out</a></li>
		</ul>
	</div>
</nav>