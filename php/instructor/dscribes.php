<?php
/*
 * Created on Apr 13, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
?>

<?php

$TOOL_NAME="Instructor";
$PAGE_NAME="Manage DScribes";

?>
<link href="../include/ocw_tool.css" rel="stylesheet" type="text/css"/>
<div>&nbsp;&nbsp;&nbsp; <a href="index.php">Instructor Home</a>&nbsp;| &nbsp;Manage dScribes&nbsp; | &nbsp;<a href="materials.php">Select OCW Course Materials</a>&nbsp; | &nbsp;<a href="../preview_inst/course.php">Review for Export </a>| &nbsp; <a href="../dscribe/index.php">dScribe Tools</a></div>
<br/>
<div id="tool_content">
<div style="padding-left:20px;">
<h3>Add or remove dScribes for this course </h2>
<p style="width:50%;">As the instructor for this course, you may act as your own dScribe, or you may want to assign this role to one of your students or other dScribe.  You can also choose to do both . </p>

<div><br/>
	<form name="adminform" method="post" action="<?=$_SERVER['PHP_SELF']; ?>" style="margin:0px;">

	
	<table border="0">
	<tr>
	<td class="text"><input type="radio" name="self_describe" tabindex="1" value="Y"/>&nbsp;&nbsp;
	I (the instructor) will dScribe this course<br/><br/></td>
		</tr>
		<tr>
			<td >Add a dScribe&nbsp;&nbsp;<input type="text" name="username" tabindex="1" value="enter unique name" size="20" /><br/></td>
			<td>
		<input class="blue_submit" id="submitbutton" type="submit" name="login" value="add" tabindex="3" /></td>
		</tr>
		
		
	</table>
	</form></div><br/><br/>
<div><p>&nbsp;&nbsp;<strong>Current dScribes</strong><br/></p><br/></div>
<table id="dscribe_table" width="100%" border=0 cellpadding=0 cellspacing=0>
<tr class="tableheader" style="background:#eee; border:1px solid #ccc;"><td>Name</td><td>email</td><td>Permissions</td><td>Remove </td></tr>
<tr><td>Fabio McKluskey</td><td><a href="mailto:useremail@umich.edu">mckluskey@umich.edu</a></td><td>Level 1</td><td><a href="#" title="remove this dScribe from this course">remove</a></td></tr> 
<tr><td>Thomas Hanning</td><td><a href="mailto:useremail@umich.edu">dScribe@umich.edu</a></td><td>Level 2</td><td><a href="#" title="remove this dScribe from this course">remove</a></td></tr> 
</table>
<div class="padding50">&nbsp;</div>
</div>
</DIV>