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
		<div class="tab"><a href="course.php">Course Home</a></div><div class="tab active">&nbsp;&nbsp;&nbsp;Instructor&nbsp;&nbsp;&nbsp;</div><div class="tab"><a href="syllabus.php">Syllabus</a></div><div class="tab"><a href="schedule.php">Schedule</a></div><div class="tab"><a href="readings.php">Readings</a></div><div class="tab"><a href="assignments.php">Assignments</a></div><div class="tab"><a href="lectures.php">Lectures</a></div>
		
	</div>
 </div>
</div>
  
      <div>
<div id="about" style="padding-left:20px">

<table  style="width: 780px; height: 270px;" cellspacing="0" cellpadding="0" border="0" id="aboutTable"
summary="Information about Professor">
<tbody>
<tr>
<td style="text-align: center;" id="aboutPhoto"><br/><br/><br/><img style="border-style: none; width: 181px; " 
src="../include/images/Heading_South_small.jpg"
 alt="" />&nbsp;</td>
<td id="aboutInfo"><br/><br/><br/>
<h3>Joseph Hardin<br />
</h3>

<p>School of Information<br/>

University of Michigan<br /></p><br/>
<p>Joseph Hardin is the Director of the Collaborative Technologies Laboratory in the Media Union, and a Clinical Assistant Professor in the School of Information, at the University of Michigan, Ann Arbor.

He is currently the Board Chair for the Sakai project, an open source, online Collaboration and Learning Environment. See www.sakaiproject.org for more information.
</p><br/><br/><br/><br/>

 </td>
    <td style="color:#333; padding: 10px; width:130px;"><div><strong> Comments</strong></div>
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
				<em><a href='mailto:$comment[email]' style='color:#09c;'>kmann</a> - </em>
				<span id='' >we need a course image </span>
			
		</div>
		</td>  
</tr>
</tbody>
</table>
</div>
</div>
</DIV>

<?php  include '../include/footer.php';  ?>