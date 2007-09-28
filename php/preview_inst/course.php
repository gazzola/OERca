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

?>
<link href="../include/ocw_tool.css" rel="stylesheet" type="text/css"/>
<div>&nbsp;&nbsp;&nbsp; <a href="../instructor/index.php">Instructor Home</a>&nbsp;| &nbsp;<a href="../instructor/dscribes.php">Manage dScribes</a>&nbsp; | &nbsp;<a href="../instructor/materials.php">Select OCW Course Materials</a>&nbsp; | &nbsp;Review for Export | &nbsp;<a href="../dscribe/index.php">dScribe Tools</a></div>
<br/>

<div id="tool_content"><div style="text-align:left; margin-bottom:20px;  ">
	<div id="submenu" style="font-weight:normal; color:#929292">
		<div class="tab active">Course Home</div><div class="tab">&nbsp;&nbsp;&nbsp;<a href="review_prof.php">Instructor</a>&nbsp;&nbsp;&nbsp;</div><div class="tab"><a href="syllabus.php">Syllabus</a></div><div class="tab"><a href="schedule.php">Schedule</a></div><div class="tab"><a href="readings.php">Readings</a></div><div class="tab"><a href="assignments.php">Assignments</a></div><div class="tab"><a href="lectures.php">Lectures</a></div>
		
	</div>
 </div> 
 </div> 
   <div><div id="about" style="padding-left:20px;">
<table cellspacing="0" cellpadding="0" border="0" style="width: 780px; height: 250px;" id="aboutTable" summary="Information about Course">
    <tbody>
        <tr>
            <td valign="top" style="text-align: center;" id="aboutPhoto" >
          

            <img width="276" height="277" alt="course logo" style="border-style: none;" src="../include/images/si557_visualPersuasion.jpg" /> 
                             </td>
            <td id="aboutInfo"><br/>
            <h3>Visual Persuasion<br />
            </h3>
            <p><strong>Karen Markey<br /><br/>
            </strong></p>
            <p>School of Information</p><br/>

           <p>SI 557 Visual Persuasion Students examine the nature of visual persuasion in everyday life, learn how to manipulate the formal elements of visual imagery to deliver a persuasive message, discover how visual imagery influences behavior, develop strategies to protect themselves from the unwanted messages images convey, and learn how to use persuasive imagery wisely in their own creations.
</p><br />
</td>            
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
				<em><a href='mailto:$comment[email]' style='color:#09c;'>brightman4</a> - </em>
				<span id='' >we need a course image </span>
				<br/><br/>
				<div style="padding-left: 20px; border-left:1px dotted red;"><em><a href='mailto:$comment[email]' style='color:#09c;'>kmarkey</a> - </em>
				<span id='' >I don't have one, do you know a graphics student that could help?' </span></div>
			
		</div>
		</td>  
        </tr>

    </tbody>
</table><br/><br/>
</div></div>
</DIV>