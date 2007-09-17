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
$PAGE_NAME="Home";

include '../include/top_header.php';

?>
<link href="../include/ocw_tool.css" rel="stylesheet" type="text/css"/>
<?php
include '../include/header.php';
?>
	
		<div>&nbsp;&nbsp;&nbsp; <a href="index.php">dScribe Home</a>&nbsp;| &nbsp;<a href="profile.php">Course &amp; Instructor Profiles</a>&nbsp; | &nbsp;Set Default Copyright&nbsp; | &nbsp;<a href="materials.php">Prepare Course Materials</a> &nbsp; | &nbsp;<a href="../preview/course.php">Review for Export</a></div>
		<br/>
		<div id="tool_content"  style="border:0; padding-left:20px;">
	<p>Set Default Copyright Holder</p>	<br/><br/>
	
	<form name="adminform" method="post" action="<?=$_SERVER['PHP_SELF']; ?>" style="margin:0px;">

	
	<table border="0">
	
		<tr>
			<td ><input type="text" name="username" tabindex="1" value="enter copyright holder name " size="30" /><br/></td>
			<td>
		<input class="blue_submit" id="submitbutton" type="submit" name="login" value="save" tabindex="3" /></td>
		</tr>
		
		
	</table>
	</form></div><br/><br/>		
	</div>
</div><div class="clear">&nbsp;</div>
<div class="padding50">&nbsp;</div>
<?php  include '../include/footer.php';  ?>