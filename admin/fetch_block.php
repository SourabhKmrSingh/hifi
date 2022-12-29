<?php
include_once("inc_config.php");

@$projectid = $_POST['projectid'];
@$blockid = $_POST['blockid'];
@$mode = $_POST['mode'];

if(isset($projectid) and $projectid != "")
{
	$blockResult = $db->view('blockid,title', 'mlm_blocks', 'blockid', "and projectid='$projectid' and status='active'", 'title asc');
	if($blockResult['num_rows'] >= 1)
	{
	?>
		<select NAME="blockid" CLASS="form-control" ID="blockid" onChange="fetch_plot();">
			<option VALUE="">--select--</option>
			<?php
			foreach($blockResult['result'] as $blockRow)
			{
			?>
				<option VALUE="<?php echo $validation->db_field_validate($blockRow['blockid']); ?>" <?php if($mode == 'edit') { if($blockRow['blockid'] == $blockid) echo "selected"; } ?>><?php echo $validation->db_field_validate($blockRow['title']); ?></option>
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