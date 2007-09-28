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
		<div class="tab "><a href="course.php">Course Home</a></div><div class="tab">&nbsp;&nbsp;&nbsp;<a href="review_prof.php">Instructor</a>&nbsp;&nbsp;&nbsp;</div><div class="tab"><a href="syllabus.php">Syllabus</a></div><div class="tab"><a href="schedule.php">Schedule</a></div><div class="tab"><a href="readings.php">Readings</a></div><div class="tab active">Assignments</div><div class="tab"><a href="lectures.php">Lectures</a></div>
		
	</div>
 </div>    <div> <div><div style="padding-left: 20px;">
<table cellspacing="0" cellpadding="0" border="0" style="width:90%;" >
    <tbody>
        <tr>
            <td valign="top">
            <h3 style="text-align: left; color: #000000">SI 557 Assignments
            </h3>       </td>
        </tr>
        <tr><td>
     
<table  id="schedule" cellspacing="0" cellpadding="0"  width="100%" border="0">
 
  <tr>
    <th width="60"><strong>Class #</strong></th>
    <th width="90">Date</th>
    <th width="500" >Description</th>
  </tr>
  <tr class="odd">
    <td><strong>1</strong></td>
    <td style="white-space: nowrap; padding: 2px 5px;"><p><strong>Jan 9, 2007</strong></p>
      </td>
    <td><p>Review Questions<br />
      1. What are the foundation technologies and who are the contributing communities.<br />
      2. 
      We will take a first look at the convergence of web technologies, markup language construction and manipulation efforts over the last few years.<br />
      3. 
      Where do these standards come from, and what are they driving toward?
        <br />
        4. 
        Then, what are some easily understandable examples of the application of these technologies and standards that we can use to help organize our understanding around?<br />
        5. 
        We will begin the discussion of the MIT OCW Project, its inception, goals and progress, the emergence of an OCW Consortium, and the S-OCW Project at UM. This will be placed in the larger context of discussions about Open Educational Resources, and the potentials the web presents for their use.</p>      </td>
  </tr>
  <tr class="even">
    <td><strong>2</strong></td>
    <td style="width:120px;"><p><strong>Jan 16, 2007</strong></p>
      </td>
    <td>      Interview two course instructors on their use of Ctools and OCW. Report back to the class by March 15. </td>
  </tr>
  <tr class="odd">
    <td><strong>3</strong></td>
    <td><p><strong>Jan 23, 2007</strong></p>
      </td>
    <td><p>Projects  -  submit a proposal for your course project.<br />
      <br /> 
      Topics for projects might include:<br /> 
      -     
      Discussing OCW Project at the University of Michigan and places where tagging systems might help.<br />
      -Explore how to improve and manage adoption by various populations, exploring applications of semantic technologies to the whole problem space of sustainable OCW (S-OCW).</p>      </td>
  </tr>
  <tr class="even">
    <td><strong>4</strong></td>
    <td><p><strong>Jan 30, 2007 </strong></p>
      </td>
    <td>Staff from the MIT OCW Project will be visiting us Tuesday and Wednesday to work with us on the dScribe ideas and brainstorm with us on software tools to support the dScribe process. </td>
  </tr>
  <tr class="even">
    <td><strong>8</strong></td>
    <td><p><strong>Feb 27, 2007</strong></p>
      </td>
    <td>No Class.  Decompress.</td>
  </tr>
  <tr class="odd">
    <td><strong>15</strong></td>
    <td><p><strong>Apr 17, 2007</strong></p>
      </td>
    <td>Projects and dScribing</td>
  </tr>
  <tr class="even">
    <td><strong>16</strong></td>
    <td><p><strong>Apr 20, 2007 </strong></p>
      </td>
    <td>Assignment Class Projects is due on Apr 20, 2007 5:00 pm. </td>
  </tr>
  <tr class="odd">
    <td><strong>17</strong></td>
    <td><p><strong>Apr 24, 2007</strong></p>
      </td>
    <td>Projects and dScribing</td>
  </tr>
</table>    </td>
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
				<em><a href='mailto:$comment[email]' style='color:#09c;'>jmanske</a> - </em>
				<span id='' >Several assignments appear to be missing - is this intentional? </span>
				
				<br/>
					<br/>
				<div style="padding-left: 20px; border-left:1px dotted red;"><em><a href='mailto:$comment[email]' style='color:#09c;'>hardin</a> - </em>
				<span id='' >yes, I want to rework some of the assigment descriptions before publishing the course </span></div>
			
		</div>
		</td>  

</tr>

    </tbody>
</table>
</div></div>
</DIV>