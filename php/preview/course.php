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
		<div class="tab active">Course Home</div><div class="tab">&nbsp;&nbsp;&nbsp;<a href="review_prof.php">Instructor</a>&nbsp;&nbsp;&nbsp;</div><div class="tab"><a href="syllabus.php">Syllabus</a></div><div class="tab"><a href="schedule.php">Schedule</a></div><div class="tab"><a href="readings.php">Readings</a></div><div class="tab"><a href="assignments.php">Assignments</a></div><div class="tab"><a href="lectures.php">Lectures</a></div>
		
	</div>
 </div> 
 </div> 
   <div><div id="about" style="padding-left:20px">
<table cellspacing="0" cellpadding="0" border="0" style="width: 780px; height: 250px;" id="aboutTable" summary="Information about Course">
    <tbody>
        <tr>
            <td valign="top" style="text-align: center;" id="aboutPhoto"><br/>
          

            <img width="276" height="277" alt="course logo" style="border-style: none;" src="" /> 
                             </td>
            <td id="aboutInfo"><br/>
            <h3>SI 514 Semantic Web<br />
            </h3>
            <p><strong>Joseph Hardin<br /><br/>
            </strong></p>
            <p>School of Information</p><br/>

           <p> This is an introduction to the ideas and technologies underlying the proposals and projects grouped under the rubric of the "Semantic Web." The course will take as a starting point the World Wide Web Consortium (W3C) vision, where "The Semantic Web is an extension of the current web in which information is given well-defined meaning, better enabling computers and people to work in cooperation." (Tim Berners-Lee), and will investigate the series of technologies in use and under development to achieve this vision, as well as sample applications of these technologies. </p>
            <br />
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
				<br/><br/>
				<div style="padding-left: 20px; border-left:1px dotted red;"><em><a href='mailto:$comment[email]' style='color:#09c;'>hardin</a> - </em>
				<span id='' >I don't have one, do you know a graphics student that could help?' </span></div>
			
		</div>
		</td>  
        </tr>

    </tbody>
</table>
</div></div>
</DIV>

<?php  include '../include/footer.php';  ?>