<?php
/*
 * Created on Apr 13, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
?>

<?php

$TOOL_NAME="dScribe";
$PAGE_NAME="Create/Update Profiles";

?>
<?php
include("../../fckeditor/fckeditor.php");
?>
<link href="../include/ocw_tool.css" rel="stylesheet" type="text/css"/>
<div>&nbsp;&nbsp;&nbsp; <a href="index.php">dScribe Home</a>&nbsp;| &nbsp;Course &amp; Instructor Profiles&nbsp; | &nbsp;<a href="copyright.php">Set Default Copyright</a>&nbsp; | &nbsp;<a href="materials.php">Prepare Course Materials</a> &nbsp; | &nbsp;<a href="../preview/course.php">Review for Export</a></div>
		<br/>

<div id="tool_content">
<p>
<strong>Edit Course Profile</strong></p><br/>
&nbsp;&nbsp;&nbsp;
<form name="course" id="course" method="post" action="">
<?php
$oFCKeditor = new FCKeditor('FCKeditor1');
$oFCKeditor->BasePath = '../../fckeditor/';
$oFCKeditor->Value = 'Default text in editor';
$oFCKeditor->Create();
?>
<br/>
&nbsp;&nbsp;&nbsp;<input class="blue_submit" id="submitbutton" type="submit" name="login" value="save" tabindex="3" />   
&nbsp;&nbsp;&nbsp;<input class="blue_submit" id="submitbutton" type="submit" name="login" value="cancel" tabindex="3" /></p>
</form>
<br/><br/>
<p>
<strong>Edit Instructor Profile</strong></p><br/>
&nbsp;&nbsp;&nbsp;
<form name="instructor" id="instructor" method="post" action="">
<?php
$oFCKeditor = new FCKeditor('FCKeditor2');
$oFCKeditor->BasePath = '../../fckeditor/';
$oFCKeditor->Value = 'Default text in editor';
$oFCKeditor->Create();
?>
<br/>
&nbsp;&nbsp;&nbsp;<input class="blue_submit" id="submitbutton" type="submit" name="login" value="save" tabindex="3" /> 
&nbsp;&nbsp;&nbsp;<input class="blue_submit" id="submitbutton" type="submit" name="login" value="cancel" tabindex="3" /></p>
</form>
<br/><br/>
</div>
</DIV>
