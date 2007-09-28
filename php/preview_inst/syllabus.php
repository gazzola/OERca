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
		<div class="tab"><a href="course.php">Course Home</a></div><div class="tab">&nbsp;&nbsp;&nbsp;<a href="review_prof.php">Instructor</a>&nbsp;&nbsp;&nbsp;</div><div class="tab active">Syllabus</div><div class="tab"><a href="schedule.php">Schedule</a></div><div class="tab"><a href="readings.php">Readings</a></div><div class="tab"><a href="assignments.php">Assignments</a></div><div class="tab"><a href="lectures.php">Lectures</a></div>
		
	</div>
 </div>     <div><div style="padding-left: 20px;">
<table cellspacing="0" cellpadding="0" border="0" width="90%" summary="SI 557 Syllabus">
        <tr>
            <td valign="top" style="width: 60%; padding-right:50px;"><br/>
            <h3 style="text-align: left; color: #000000">Syllabus<br />
            </h3>       </td>
        </tr>
         <tr><td style="padding-right:20px;"><p><strong>SI 557: Visual Persuasion</strong></p>
<p>
This is a course on semantic web technologies and their application in educational software systems that are built in open source and support the ideas of open courseware and open educational resources. The weekly activities below will change, especially after the first lecture when we talk about the interests of those taking the course this semester, and as we match our project work with the content of the class, and then as it is all refined to make the overall emerging result coherent. The goal will always be to track the concepts we are investigating with real examples of open technologies in development and use. 
<br/><br/>
The example we will focus on for application of semantic tech methods this semester is the OpenCourseWare work being done at MIT, the University of Michigan and Utah State, as well as at schools that are part of the OpenCourseWare Consortium, and the University of Michigan Sustainable-OCW project. 
<br/><br/>
S-OCW is an effort to develop a ground-up system and set of rationales for adoption of an enterprise level OCW effort. We will be looking at this from all angles to see how it can successfully evolve and how semantic or tagging methods in general can support it. The development of tools that various users can employ to make the process of migration of materials from a class to an OCW site will be a central aspect of this. Whether it is explicitly listed in the daily course activities or not, each class session will have discussion and brainstorming about the Sustainable OpenCourseWare (S-OCW) project here at the University of Michigan
<br/><br/>
In addition, students will be acting as “DScribes” (Digital Scribes) using, critiquing, and participating in the development of the emerging CTools-based “OCW Tool” currently in development at UM. Students will thus be working on getting the first generation of DScribed classes up on the UM OCW site, as well as contributing to making the tools and processes for that consistent with the models we develop for the data, meet the workflow requirements of the process, and are user friendly.
 </p><br/><br/><br/>
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
				<em><a href='mailto:$comment[email]' style='color:#09c;'>kmann</a> - </em>
				<span id='' >we need a course image </span>
			
		</div>
		</td>  
</tr>
       

    
</table>
</div></div></div>
</DIV>