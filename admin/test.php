<?php
include_once("inc_config.php");
include_once("login_user_check.php");

$_SESSION['active_menu'] = "dashboard";

$registerQueryResult = $db->view('regid', 'mlm_registrations', 'regid');
$registerCount = $registerQueryResult['num_rows'];

$creditResult = $db->view("SUM(amount) as total_credit_amount", "mlm_transactions", "transactionid", "and type='credit'");
$creditRow = $creditResult['result'][0];

$debitResult = $db->view("SUM(amount) as total_debit_amount", "mlm_transactions", "transactionid", "and type='debit'");
$debitRow = $debitResult['result'][0];

$enquiryQueryResult = $db->view('enquiryid', 'mlm_enquiries', 'enquiryid');
$enquiryCount = $enquiryQueryResult['num_rows'];

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
	<div class="body genealogy-body genealogy-scroll">
    <div class="genealogy-tree">
        <ul>
            <li>
                <a href="javascript:void(0);">
                    <div class="member-view-box">
                        <div class="member-image">
                            <img src="images/user-icon.png" alt="Member">
                            <div class="member-details">
                                <p>John Doe</p>
                            </div>
                        </div>
                    </div>
                </a>
                <ul class="active">
                    <li>
                        <a href="javascript:void(0);">
                            <div class="member-view-box">
                                <div class="member-image">
                                    <img src="images/user-icon.png" alt="Member">
                                    <div class="member-details">
                                        <p>Member 1 asdsad</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <ul >
                            <li>
                                <a href="javascript:void(0);">
                                    <div class="member-view-box">
                                        <div class="member-image">
                                            <img src="images/user-icon.png" alt="Member">
                                            <div class="member-details">
                                                <p>Member 1-1</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">
                                    <div class="member-view-box">
                                        <div class="member-image">
                                            <img src="images/user-icon.png" alt="Member">
                                            <div class="member-details">
                                                <p>Member 1-2</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">
                                    <div class="member-view-box">
                                        <div class="member-image">
                                            <img src="images/user-icon.png" alt="Member">
                                            <div class="member-details">
                                                <p>Member 1-3</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <ul>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="member-view-box">
                                                <div class="member-image">
                                                    <img src="images/user-icon.png" alt="Member">
                                                    <div class="member-details">
                                                        <p>Member 1-3-1</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="member-view-box">
                                                <div class="member-image">
                                                    <img src="images/user-icon.png" alt="Member">
                                                    <div class="member-details">
                                                        <p>Member 1-3-2</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="member-view-box">
                                                <div class="member-image">
                                                    <img src="images/user-icon.png" alt="Member">
                                                    <div class="member-details">
                                                        <p>Member 1-3-3</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript:void(0);">
                                    <div class="member-view-box">
                                        <div class="member-image">
                                            <img src="images/user-icon.png" alt="Member">
                                            <div class="member-details">
                                                <p>Member 1-4</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">
                                    <div class="member-view-box">
                                        <div class="member-image">
                                            <img src="images/user-icon.png" alt="Member">
                                            <div class="member-details">
                                                <p>Member 1-5</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">
                                    <div class="member-view-box">
                                        <div class="member-image">
                                            <img src="images/user-icon.png" alt="Member">
                                            <div class="member-details">
                                                <p>Member 1-6</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">
                                    <div class="member-view-box">
                                        <div class="member-image">
                                            <img src="images/user-icon.png" alt="Member">
                                            <div class="member-details">
                                                <p>Member 1-7</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <ul>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="member-view-box">
                                                <div class="member-image">
                                                    <img src="images/user-icon.png" alt="Member">
                                                    <div class="member-details">
                                                        <p>Member 1-7-1</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="member-view-box">
                                                <div class="member-image">
                                                    <img src="images/user-icon.png" alt="Member">
                                                    <div class="member-details">
                                                        <p>Member 1-7-2</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                        <ul>
                                            <li>
                                                <a href="javascript:void(0);">
                                                    <div class="member-view-box">
                                                        <div class="member-image">
                                                            <img src="images/user-icon.png" alt="Member">
                                                            <div class="member-details">
                                                                <p>Member 1-7-2-1</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);">
                                                    <div class="member-view-box">
                                                        <div class="member-image">
                                                            <img src="images/user-icon.png" alt="Member">
                                                            <div class="member-details">
                                                                <p>Member 1-7-2-2</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);">
                                                    <div class="member-view-box">
                                                        <div class="member-image">
                                                            <img src="images/user-icon.png" alt="Member">
                                                            <div class="member-details">
                                                                <p>Member 1-7-2-3</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="member-view-box">
                                                <div class="member-image">
                                                    <img src="images/user-icon.png" alt="Member">
                                                    <div class="member-details">
                                                        <p>Member 1-7-3</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);">
                            <div class="member-view-box">
                                <div class="member-image">
                                    <img src="images/user-icon.png" alt="Member">
                                    <div class="member-details">
                                        <p>Member 2</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <ul class="active">
                            <li>
                                <a href="javascript:void(0);">
                                    <div class="member-view-box">
                                        <div class="member-image">
                                            <img src="images/user-icon.png" alt="Member">
                                            <div class="member-details">
                                                <p>John Doe</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                              <ul>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="member-view-box">
                                                <div class="member-image">
                                                    <img src="images/user-icon.png" alt="Member">
                                                    <div class="member-details">
                                                        <p>John Doe</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="member-view-box">
                                                <div class="member-image">
                                                    <img src="images/user-icon.png" alt="Member">
                                                    <div class="member-details">
                                                        <p>John Doe</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="member-view-box">
                                                <div class="member-image">
                                                    <img src="images/user-icon.png" alt="Member">
                                                    <div class="member-details">
                                                        <p>John Doe</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript:void(0);">
                                    <div class="member-view-box">
                                        <div class="member-image">
                                            <img src="images/user-icon.png" alt="Member">
                                            <div class="member-details">
                                                <p>John Doe</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <ul>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="member-view-box">
                                                <div class="member-image">
                                                    <img src="images/user-icon.png" alt="Member">
                                                    <div class="member-details">
                                                        <p>John Doe</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="member-view-box">
                                                <div class="member-image">
                                                    <img src="images/user-icon.png" alt="Member">
                                                    <div class="member-details">
                                                        <p>John Doe</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="member-view-box">
                                                <div class="member-image">
                                                    <img src="images/user-icon.png" alt="Member">
                                                    <div class="member-details">
                                                        <p>John Doe</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript:void(0);">
                                    <div class="member-view-box">
                                        <div class="member-image">
                                            <img src="images/user-icon.png" alt="Member">
                                            <div class="member-details">
                                                <p>John Doe</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                              <ul>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="member-view-box">
                                                <div class="member-image">
                                                    <img src="images/user-icon.png" alt="Member">
                                                    <div class="member-details">
                                                        <p>John Doe</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="member-view-box">
                                                <div class="member-image">
                                                    <img src="images/user-icon.png" alt="Member">
                                                    <div class="member-details">
                                                        <p>John Doe</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="member-view-box">
                                                <div class="member-image">
                                                    <img src="images/user-icon.png" alt="Member">
                                                    <div class="member-details">
                                                        <p>John Doe</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
</div>
</div>
</div>
</body>
</html>