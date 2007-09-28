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
$PAGE_NAME="Manager Course Materials";

?>
<link href="../include/ocw_tool.css" rel="stylesheet" type="text/css"/>
<div>&nbsp;&nbsp;&nbsp; <a href="index.php">Instructor Home</a>&nbsp;| &nbsp;<a href="dscribes.php">Manage dScribes</a>&nbsp; | &nbsp;Select OCW Course Materials &nbsp; | &nbsp;<a href="../preview_inst/course.php">Review for Export</a> | &nbsp; <a href="../dscribe/index.php">dScribe Tools</a></div>
<br/>

<div id="tool_content">
<div style="text-align:left; margin-bottom:20px;  ">

<br/>
<table border="0" cellpadding="0" cellspacing="0" class="course_materials">
<tr>
	<td width="40%" style="font-weight: bold; text-align: center; border:1px solid #ccc;padding: 5px; background: #E5EBFF;">
	Ctools Course Materials</td>
	<td style="width: 60px;">&nbsp;</td>
	<td style="font-weight: bold; text-align: center; border:1px solid #ccc;padding: 5px; background: #E5EBFF;" width="250">
		Materials Selected for OCW Course
	</td>
</tr>
<tr>
	<td style="font-weight: normal; text-align: left; padding: 10px; border:1px solid #ccc; height: 250px; padding:5px;">
		<div class="instructions"><br/>Check the course items below that you want published to OCW. 
			Then, click on <strong>Add</strong> to update the OCW materials list on the right. <br/><br/></div>
		<div class="materials_list">
			<div class="parent"><img src="../include/images/validated.gif" height="15" /> &nbsp;&nbsp;<img src="../include/images/page.png" height="15" /> &nbsp;&nbsp; 	Syllabus</div>
			
		
		
		
			
			<div class="parent checked"> <input type="checkbox" name="checked[]" value="<?=$proposal['pk']?>" />	<img src="../include/images/folder.gif" height="18" /> &nbsp;&nbsp;	Lectures
			
				<div class="child checked">
					<div> <img src="../include/images/validated.gif" height="15" /> <img src="../include/images/ppt.jpg" height="15" /> &nbsp;&nbsp; SI514 L1 W2007.ppt</div>
					<div> <img src="../include/images/validated.gif" height="15" /> <img src="../include/images/ppt.jpg" height="15" /> &nbsp;&nbsp; SI514 L2 W2007.ppt</div>
					<div> <img src="../include/images/validated.gif" height="15" /> <img src="../include/images/ppt.jpg" height="15" /> &nbsp;&nbsp; SI514 L3 W2007.ppt</div>
					<div> <img src="../include/images/validated.gif" height="15" /> <img src="../include/images/ppt.jpg" height="15" /> &nbsp;&nbsp; SI514 L4 W2007.ppt</div>
					<div> <img src="../include/images/validated.gif" height="15" /> <img src="../include/images/ppt.jpg" height="15" /> &nbsp;&nbsp; SI514 L5 W2007.ppt</div>
					<div> <input type="checkbox" name="checked[]" value="<?=$proposal['pk']?>" /> <img src="../include/images/ppt.jpg" height="15" /> &nbsp;&nbsp; SI514 L6 W2007.ppt</div>
				<div> <input type="checkbox" name="checked[]" value="<?=$proposal['pk']?>" /> <img src="../include/images/ppt.jpg" height="15" /> &nbsp;&nbsp; SI514 L7 W2007.ppt</div>
				</div>
		   </div>
			<div class="parent">
				<img src="../include/images/blank.gif" height="3" /><input type="checkbox" name="checked[]" value="<?=$proposal['pk']?>" />
					 <img src="../include/images/page.png" height="15" />&nbsp;&nbsp;Readings</div>
				
			
			
			<div class="parent checked">
			<img src="../include/images/validated.gif" height="15" />&nbsp;&nbsp;&nbsp;&nbsp;Assignments</div>
			
			<div class="parent"><img src="../include/images/blank.gif" height="3" /><input type="checkbox" name="checked[]" value="<?=$proposal['pk']?>" />
				&nbsp;&nbsp;Schedule</div>	
			<div class="parent checked">	 
			<img  src="../include/images/exclaim.gif"> Lecture 1 video<span style="color:red; font-size: .95em;" title="this item has been changed">
				&nbsp;&nbsp;- item has been modified </span> </div>
			
		</div>
	</td>
		
 <td style="width: 10%; vertical-align:top;">
 <form name="adminform" method="post" action="<?=$_SERVER['PHP_SELF']; ?>" style="margin:0px;">

	
	<table border="0" style="font-weight: normal">
		<tr>
			<td class="text"></td>
			<td><br/><br/><br/><br/><br/>&nbsp;&nbsp;&nbsp;<input class="blue_submit" id="submitbutton" type="submit" name="login" value="Add >>>" tabindex="3" /></td>
		</tr>
	</table>
	</form><br/><br/>
</td>

<td width=40% style="vertical-align:top; font-weight: normal; text-align: left; border:1px solid #ccc; ">
 <div class="materials_list">
 <p class="instructions"><br/>To remove materials from this OCW materials list  - click on the <strong>remove</strong> link for that item.<br/><br/></p>
	
<div class="parent">&nbsp; <img src="../include/images/page.png" height=15  /> &nbsp;&nbsp;Syllabus <a href="#" title="remove this item">( remove )</a>
</div>

<div class="parent">&nbsp; <img src="../include/images/folder.gif" height=15  /> &nbsp;&nbsp;Lectures <a href="#" title="remove this item and all child items">( remove )</a>

<div class="child">
	<div><img src="../include/images/blank.gif" height="15" /><img src="../include/images/ppt.jpg" height="15" /> &nbsp;&nbsp; SI514 L1 W2007.ppt <a href="#" title="remove only this item">( remove )</a></div>
<div> <img src="../include/images/blank.gif" height="15" /><img src="../include/images/ppt.jpg" height="15" /> &nbsp;&nbsp; SI514 L2 W2007.ppt <a href="#" title="remove only this item">( remove )</a></div>
<div> <img src="../include/images/blank.gif" height="15" /><img src="../include/images/ppt.jpg" height="15" /> &nbsp;&nbsp; SI514 L3 W2007.ppt <a href="#" title="remove only this item">( remove )</a></div>
<div> <img src="../include/images/blank.gif" height="15" /><img src="../include/images/ppt.jpg" height="15" /> &nbsp;&nbsp; SI514 L4 W2007.ppt <a href="#" title="remove only this item">( remove )</a></div>
<div> <img src="../include/images/blank.gif" height="15" /><img src="../include/images/ppt.jpg" height="15" /> &nbsp;&nbsp; SI514 L5 W2007.ppt <a href="#" title="remove only this item">( remove )</a></div>
</div>
	</div>
<div class="parent">
<img src="../include/images/blank.gif" height="15" />&nbsp;&nbsp;&nbsp;&nbsp;Assignments
 <a href="#" title="remove only this item">( remove )</a></div>

	
<div class="parent">	 
<img  src="../include/images/exclaim.gif"> Lecture 1 video <span style="color:red; font-size: .95em;" title=" item has been changed">&nbsp;&nbsp;- item has been modified</span> </div>
	</div>
	</td></tr>
 
</table>
</div></div>
</div>