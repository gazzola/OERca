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
$PAGE_NAME="Review and Export";

include '../include/top_header.php';

?>
<link href="../include/ocw_tool.css" rel="stylesheet" type="text/css"/>
<?php
include '../include/header.php';
?>
<div id="topmenu">&nbsp;&nbsp;&nbsp; <a href="../dscribe/index.php">dScribe Home</a>&nbsp;| &nbsp;<a href="../dscribe/profile.php">Course &amp; Instructor Profiles</a>&nbsp; | &nbsp;<a href="../dscribe/copyright.php">Set Default Copyright</a>&nbsp; | &nbsp;<a href="../dscribe/materials.php">Prepare Course Materials</a> &nbsp; | &nbsp;Review for Export</div>
		<br/>

<div id="tool_content"><div style="text-align:left; margin-bottom:20px;  ">
	<div id="submenu" style="font-weight:normal; color:#929292">
		<div class="tab"><a href="course.php">Course Home</a></div><div class="tab">&nbsp;&nbsp;&nbsp;<a href="review_prof.php">Instructor</a>&nbsp;&nbsp;&nbsp;</div><div class="tab"><a href="syllabus.php">Syllabus</a></div><div class="tab"><a href="schedule.php">Schedule</a></div><div class="tab"><a href="readings.php">Readings</a></div><div class="tab"><a href="assignments.php">Assignments</a></div><div class="tab active">Lectures</div>
		
	</div>
 </div>   <div><div style="padding-left: 20px;">
<table id="lectures" cellspacing="0" cellpadding="0" border="0" width="80%">
    <tbody>
        <tr>
            <td valign="top">
            <h3 style="text-align: left; color: #000000">Lectures
            </h3>  
            <ul><li><a href="#"><img src="../include/images/ppt.jpg" />SI514 L1 W2007.ppt</a></li>
        <li><a href="#"><img src="../include/images/ppt.jpg" />SI514 L1 W2007.ppt</a></li>
        <li><a href="#"><img src="../include/images/ppt.jpg" />SI514 L2 W2007.ppt</a></li>
        <li><a href="#"><img src="../include/images/ppt.jpg" />SI514 L4 W2007.ppt</a></li>
        <li><a href="#"><img src="../include/images/ppt.jpg" />SI514 L5 W2007.ppt</a></li>
        </ul></td>
           <td style="color:#333; padding: 10px; width:160px;"><div><strong> Comments</strong></div>
          	<div style="font-size:.92em;">
			 
			<a id="onComment" href="<?= $_SERVER['PHP_SELF'] ?>" onClick="showAddComment('<?= $pk ?>');return false;" title="add a comment" style="color:#09c;"><img src="../include/images/add.png" border=0 height="15" width="15"/> add a comment</a>
			<br/>


<?php
		$cline = 0;

			
		
?>
			<div id="addComment<?= $pk ?>" style="display:none;">
			<a href="<?= $_SERVER['PHP_SELF'] ?>" onClick="setAnchor('<?= $pk ?>');return false;" title="Save comments and any current votes" style="color:red;">Save New Comment</a><br/>
			<textarea name="cmnt<?= $pk ?>" cols="20" rows="4"></textarea>
			</div>
		</div>
		<div style="font-size:.92em;">
			
	

				<br/>
				<em><a href='mailto:$comment[email]' style='color:#09c;'>Jmanske</a> - </em>
				<span id='' >I need an email address to contact Tim B.L. re chart in L5 </span>
			
		</div>
		</td>  
        
        
        
        </tr>
       
    </tbody>
</table>
</div></div>
</DIV>

<?php  include '../include/footer.php';  ?>