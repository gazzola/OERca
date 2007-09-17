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
		<div class="tab "><a href="course.php">Course Home</a></div><div class="tab">&nbsp;&nbsp;&nbsp;<a href="review_prof.php">Instructor</a>&nbsp;&nbsp;&nbsp;</div><div class="tab"><a href="syllabus.php">Syllabus</a></div><div class="tab"><a href="schedule.php">Schedule</a></div><div class="tab  active">Readings</div><div class="tab"><a href="assignments.php">Assignments</a></div><div class="tab"><a href="lectures.php">Lectures</a></div>
		
	</div>
 </div>      <div><div style="padding-left: 20px;"><table cellspacing="0" cellpadding="0" border="0" style="width:90%;" >
    <tbody>
        <tr>
            <td valign="top">
            <h3 style="text-align: left; color: #000000">SI 557 Readings
            </h3>       </td>
        </tr>
        <tr><td>
           <table width="92%" border="0" cellspacing="0" cellpadding="5" style="text-align : left;" summary="Data for Main Block" class="typeBioTitle">
      
        <tr> 
          <td class="typejobs" width="2%"></td>
          <td class="typejobInfo" width="93%" >
          <strong>Required Readings</strong><br/><br/>Handbook of Internet Computing, 
            Chapter: 1.Title: &quot;The UARC Web-Based Collaboratory: Software 
            Architecture and Experiences. Authors: Sushila Subramanian, G. Robert 
            Malan, Hyong Sop Shim, Jang Ho Lee, Peter Knoop, Terry Weymouth, Farnam 
            Jahanian, Atul Prakash, and Joseph Hardin, Publisher: CRC Press LLC. 
            November 1999 
          </td>
        </tr>
    
        <tr> 
          <td class="typejobs" width="2%" ></td>
		            <td class="typejobInfo" width="93%"> &quot;Surviving the 
            Three Revolutions in Social Science Computing,&quot; Richard C. Rockwell, 
            Joseph Hardin, and Melanie Loots, <i>Social Science Computer Review</i>, 
            Summer 1995, Vol. 13, no. 2.
          </td>
        </tr>
       
        <tr> 
          <td class="typejobs" width="2%"></td>
          <td class="typejobInfo" width="93%"><a href="http://www.ed.gov/Technology/Futures/hardin.html">Digital 
            Technology and its Impact on Education</a>,&quot; Joseph Hardin and 
            John Ziebarth, May, 1995, U.S. Department of Education, Office of 
            Educational Technology invited whitepaper.
          </td>
        </tr>
  
        <tr> 
          <td class="typeMain" width="2%"></td>
          <td class="typejobInfo" width="93%"> <br/><br/><strong>Recommended Readings</strong><br/><br/>&quot;NCSA Mosaic and the World 
            Wide Web: Global Hypermedia Protocols for the Internet,&quot; B. Schatz 
            and J. Hardin, Science, August 12, 1994, vol. 265.<br />
          </td>
        </tr>

        
        <tr> 
          <td class="typeMain" width="2%"></td>
          <td class="typejobInfo" width="93%"> &quot;Collaboration via Hypermedia 
            for Computational Analysis,&quot; M. Andreessen and J. Hardin, Instructional 
            Computing Newsletter, 1993. <br />

         <div class="padding50">&nbsp;</div> </td>
        </tr>
       
      </table>  </td>
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
				
			
		</div>
		</td>  
		</tr>

    </tbody>
</table>
</div></div>
</DIV>

<?php  include '../include/footer.php';  ?>