<?php
/*
 * Created on Apr 13, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
?>

<?php

$TOOL_NAME="dScribe Home";
$PAGE_NAME="Edit IP";

include '../include/top_header.php';

?>
<link href="../include/ocw_tool.css" rel="stylesheet" type="text/css"/>
<?php
include '../include/header.php';
?>
	
		<div>&nbsp;&nbsp;&nbsp; <a href="index.php">dScribe Home</a>&nbsp;| &nbsp;<a href="profile.php">Course &amp; Instructor Profiles</a>&nbsp; | &nbsp;<a href="copyright.php">Set Default Copyright</a>&nbsp; | &nbsp;Prepare Course Materials &nbsp; | &nbsp;<a href="../preview/course.php">Review for Export</a></div>
		<br/>
		<div id="tool_content">
		

	<form name="form1" id="form1" method="post" action="edit_ip.php">
<table id="materials_list" width="780"  cellpadding="5" cellspacing="0" >

<tr>
	<td  valign="top" style="padding:0px;">
	<h3>Edit IP</h3>	<div style="font-size: 1em; padding-top: 0px; padding-bottom:3px;">
		<img src="../include/images/ppt.jpg" height=15  />&nbsp;&nbsp;<strong>SI514 L1 W2007.ppt </strong>
		</div>
	</td>
</tr>
<tr>
<td>

	<table width=80% cellpadding=0 cellspacing=0 align=center style="background:#eee; border: 1px solid #ccc; padding:20px;">
	
		<tr>
			<td colspan=2>
				<strong>Add New IP Object</strong><hr /><br/><br/>
			</td>
		</tr>
		<tr>
			<td  align=right><strong>Object Name</strong>&nbsp;&nbsp;</td>
			<td><input type="text" name="username" tabindex="1" value="" size="30" style="color:#333;" />
			<br/><br/></td></tr>
		<tr>
			 <td  align=right><strong>Object Type&nbsp;&nbsp;</strong></td>
			<td>	<select name="secondaryRole">
			<?  $type="";
			if ($type) { echo "<option value=''>$type</option>";	} ?>
			
			<option value="">-- select IP type --</option>
				<option value="">image (jpeg)</option>
				<option value="">image (gif)</option>
				<option value="">video (wav)</option>
				<option value="">video (mpeg)</option>
				<option value="">excerpt</option>
				<option value="">quote</option>
				<option value="">citation</option>
				
			</select><br/><br/></td>
		</tr>
	
	
	

			
		<tr>
		<td align=right><strong>Credit line</strong> &nbsp;&nbsp;&nbsp;</td>
		<td><input type="text" name="username" tabindex="1" value=" &nbsp;[ attribute IP object ]" size="30" style="font-size:.98em;" />
		<br/><br/></td>
		</tr>
		<tr><td align=right><strong>Comments</strong> &nbsp;&nbsp;&nbsp;</td>
		<td><textarea name="cmnt<?= $pk ?>" cols="40" rows="4" style="color:#666; margin-top:0; font-size:.98em;">[ describe item and/or add a comment ]</textarea>
		<br/><br/></td></tr>
		<tr><td align=right><strong>Action Type</strong> &nbsp;&nbsp;&nbsp;</td>
		<td>
		<select name="secondaryRole">
	<?  $type="";
	if ($type) { echo "<option value=''>$type</option>";	} ?>
	
	<option value="">-- select IP action--</option>
		<option value="">Remove</option>
		<option value="">Replace</option>
		<option value="">Commission</option>
		<option value="">Permission</option>
		
	</select><br/><br/><br/></td></tr>
	<tr><td>&nbsp;</td>
	<td>
		<input class="blue_submit" id="submitbutton" type="submit" name="login" value="save" tabindex="3" />&nbsp;&nbsp;&nbsp;
		<input class="blue_submit" id="submitbutton" type="submit" name="login" value="cancel" tabindex="3" />
	
	
</td></tr>

<tr>

	<td valign="top" colspan="2" style="padding:0px;">
	
	</td>
</tr>
</table>
</form>	</div>
</div><div class="clear">&nbsp;</div>
<div class="padding50">&nbsp;</div>
<?php  include '../include/footer.php';  ?>