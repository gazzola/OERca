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
<div>&nbsp;&nbsp;&nbsp; <a href="../instructor/index.php">Instructor Home</a>&nbsp;| &nbsp;<a href="../instructor/dscribes.php">Manage dScribes</a>&nbsp; | &nbsp;<a href="../instructor/materials.php">Select OCW Course Materials</a>&nbsp; | &nbsp;Review for Export | &nbsp;<a href="../dscribe/index.php">dScribe Tools</a></div>
<br/>

<div id="tool_content"><div style="text-align:left; margin-bottom:20px;  ">
	<div id="submenu" style="font-weight:normal; color:#929292">
		<div class="tab"><a href="course.php">Course Home</a></div><div class="tab active">&nbsp;&nbsp;&nbsp;Instructor&nbsp;&nbsp;&nbsp;</div><div class="tab"><a href="syllabus.php">Syllabus</a></div><div class="tab"><a href="schedule.php">Schedule</a></div><div class="tab"><a href="readings.php">Readings</a></div><div class="tab"><a href="assignments.php">Assignments</a></div><div class="tab"><a href="lectures.php">Lectures</a></div>
		
	</div>
 </div>
</div>
  
      <div>
<div id="about" style="padding-left:20px;">

<table  style="width: 780px; height: 270px;" cellspacing="0" cellpadding="0" border="0" id="aboutTable"
summary="Information about Professor">
<tbody>
<tr>
<td style="text-align: center;" id="aboutPhoto"><br/><br/><img style="border-style: none; width: 191px; height: 192px;" 
src="../include/images/Markey_Karen.jpg"
 alt="" />&nbsp;</td>
<td id="aboutInfo"> <h3>SI 557 Visual Persuasion<br />
            </h3>
            <p><strong>Karen Markey<br />
            </strong><br/></p>
            <p>School of Information</p>

           <p> <br/>
Karen Markey is researching visualization and social computing for networked learning systems in the School of Information and Learning Technologies at the University of Michigan. Karen also teaches online courses in Digital Media and Web Development using Sakai.

</p>
<br/><br/>
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
				<span id='' >Karen - I copied your bio from your SI home page, is this okay?</span>
			
		</div>
		</td>  
</tr>
</tbody>
</table><br/><br/>
</div>
</div>
</DIV>

<?php  include '../include/footer.php';  ?>