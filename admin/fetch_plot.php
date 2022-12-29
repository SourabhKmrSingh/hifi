<?php
include_once("inc_config.php");

@$blockid = $_POST['blockid'];
@$plotid = $_POST['plotid'];
@$mode = $_POST['mode'];

if(isset($blockid) and $blockid != "")
{
	$plotResult = $db->view('plotid,title', 'mlm_plots', 'plotid', "and blockid='$blockid' and status='active'", 'title asc');
	if($plotResult['num_rows'] >= 1)
	{
	?>
		<select NAME="plotid" CLASS="form-control" ID="plotid">
			<option VALUE="">--select--</option>
			<?php
			foreach($plotResult['result'] as $plotRow)
			{
			?>
				<option VALUE="<?php echo $validation->db_field_validate($plotRow['plotid']); ?>" <?php if($mode == 'edit') { if($plotRow['plotid'] == $plotid) echo "selected"; } ?>><?php echo $validation->db_field_validate($plotRow['title']); ?></option>
			<?php
			}
			?>
		</select>
	<?php
	}
	else
	{
		echo '<p class="text">No Data Available!</p>';
	}
}
else
{
	echo '<p class="text">No Data Available!</p>';
}
?>