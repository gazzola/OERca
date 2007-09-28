<?php
/*
 * Created on Apr 13, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
?>

<?php

$TOOL_NAME="dScribe Home";
$PAGE_NAME="Home";

?>
<link href="../include/ocw_tool.css" rel="stylesheet" type="text/css"/>
	
		<div>&nbsp;&nbsp;&nbsp; <a href="index.php">dScribe Home</a>&nbsp;| &nbsp;<a href="profile.php">Course &amp; Instructor Profiles</a>&nbsp; | &nbsp;<a href="copyright.php">Set Default Copyright</a>&nbsp; | &nbsp;Prepare Course Materials &nbsp; | &nbsp;<a href="../preview/course.php">Review for Export</a></div>
		<br/>
		<div id="tool_content">
		
<div style="text-align:left; ">
	<div id="submenu" style="font-weight:normal; color:#929292; ">
		<div class="tab active">Prep Course Materials</div><div class="tab">&nbsp;&nbsp;&nbsp;
		<a href="tags">Edit Tags</a>&nbsp;&nbsp;&nbsp;</div>
	</div>
 </div>
	<form name="form1" id="form1" method="post" action="">
<table id="materials_list" width="780"  cellpadding="3" cellspacing="0">
<tr>
	<td  colspan="3" valign="top" style="padding:0px;">
		<div style="font-size: 1em; padding-top: 20px; padding-bottom:3px;"><img src="../include/images/collapse.gif">&nbsp;&nbsp;<strong>Syllabus</strong>
		</div>
	</td>
</tr><tr class="sectionHeader" >
	<td><strong>Export</strong></td><td ><strong>Name</strong></td>

	<td><strong>Tag</strong></td>
	<td style="white-space:nowrap; text-align:center; padding: 0px 3px;"><strong>Modify File</strong></td>
	<td style="white-space:nowrap; text-align:center; padding: 0px 3px;"><strong>Modify IP</strong></td>
	<td style="white-space:nowrap; text-align:center; padding: 0px 3px;"><strong>IP Status</strong></td>
	<td colspan=2>&nbsp;&nbsp;&nbsp;<strong>Comments</strong></td>
	
</tr>
<tr>
	<td> <input name='check' type='checkbox' value='' /> </td>
	<td><img src="../include/images/page.png" height=12  /><a href="#">&nbsp;&nbsp;Syllabus</a>  &nbsp;&nbsp;

    </td>
	<td><?php $type="Syllabus"; include '../include/selection.php';  $type="";?>  </td><td class="options" style="text-align:center;" >
	 edit </td> <td  class="options" style="text-align:center;"><a href="edit_ip.php">view/update</a>  </td>
	
	<td align=center><img src="../include/images/validated.gif" title="completed"><a href="#"></a> </td>
	<td>
	<?php include ('../include/comments.php'); ?>
</td>
	
</tr>

<tr>
	<td  colspan="3" valign="top" style="padding:0px;">
		<div style="font-size: 1em; padding-top: 20px; padding-bottom:3px;"><img src="../include/images/collapse.gif">&nbsp;&nbsp;<strong>Resource Items </strong>
		</div>
	</td>
</tr><tr class="sectionHeader">
	<td><strong>Export</strong></td><td><strong>Name</strong></td>

	<td><strong>Tag</strong></td>
	<td style="white-space:nowrap; text-align:center; padding: 0px 3px;"><strong>Modify File</strong></td>
	<td style="white-space:nowrap; text-align:center; padding: 0px 3px;"><strong>Modify IP</strong></td>
	<td><strong>IP Status</strong></td>
	<td colspan=2>&nbsp;&nbsp;&nbsp;<strong>Comments</strong></td>
</tr>
<tr>
	<td> <input name='check' type='checkbox' value='' /> </td>
	<td><img src="../include/images/page.png" height=12  /><a href="#">&nbsp;&nbsp;Readings</a>  &nbsp;&nbsp;

    </td>
	<td><?php include '../include/selection.php';?>  </td><td class="options" style="text-align:center;"> edit </td> <td  class="options" style="text-align:center;"><a href="edit_ip.php">view/update</a>  </td>
	
	<td align=center><img src="../include/images/validated.gif" title="completed"><a href="#"></a> </td>
	<td>
	<?php include ('../include/comments.php'); ?>
</td>
</tr>

<tr>
	<td> <input name='check' type='checkbox' value='' /> </td>
	<td><img src="../include/images/collapse.gif"> <img src="../include/images/folder.gif" height=18  /><a href="#"> &nbsp; Lectures</a> &nbsp;&nbsp;

    </td>
	<td>&nbsp; </td>
	<td><a href="#">&nbsp;</a> </td> <td><a href="#">&nbsp;</a> </td> <td>&nbsp; </td>
	
</tr>
<tr>
	<td>&nbsp;</td>
	<td class="child"> <input name='check' type='checkbox' value='' /> <img src="../include/images/ppt.jpg" height=15  />
	<a href="#"> &nbsp; SI514 L1 W2007.ppt</a> &nbsp;&nbsp;

    </td>
	<td><?php $type="Lecture Notes"; include '../include/selection.php'; ?>   </td>
	<td class="options" style="text-align:center;"> edit </td> <td  class="options" style="text-align:center;"><a href="edit_ip.php">view/update</a>  </td>
	 <td align=center><img src="../include/images/validated.gif" title="completed"> <a href="#" class="complete"></a> </td><td>
	<?php include ('../include/comments.php'); ?>
</td>
</tr>
<tr>
	<td> </td>
	<td class="child"><input name='check' type='checkbox' value='' /> <img src="../include/images/ppt.jpg" height=15  />
	<a href="#"> &nbsp; SI514 L2 W2007.ppt</a> &nbsp;&nbsp;

    </td>
	<td><?php include '../include/selection.php';?> </td>
	<td class="options" style="text-align:center;"> edit </td> <td  class="options" style="text-align:center;"><a href="edit_ip.php">view/update</a>  </td>
	 <td align=center><img src="../include/images/validated.gif" title="completed"> <a href="#" class="complete"></a> </td><td>
	<?php include ('../include/comments.php'); ?>
</td>
</tr>
<tr>
	<td>  </td>
	<td class="child"><input name='check' type='checkbox' value='' /><img src="../include/images/ppt.jpg" height=15  />
	<a href="#"> &nbsp; SI514 L3 W2007.ppt</a> &nbsp;&nbsp;

    </td>
	<td><?php include '../include/selection.php';?> </td>
	<td class="options" style="text-align:center;"> edit </td> <td  class="options" style="text-align:center;"><a href="edit_ip.php">view/update</a>  </td>
	 <td align=center><a title="no IP info provided" class="no_IP" href="#">&nbsp;&nbsp;--&nbsp;&nbsp;</a> </td>
	 <td >
		<div style="font-size:.95em;">
			
	<a id="onComment" href="<?= $_SERVER['PHP_SELF'] ?>" onClick="showAddComment('<?= $pk ?>');return false;" title="add a comment"><img src="../include/images/add.png" border=0 height="15" width="15"/></a>
					<br/>


<?php
		$cline = 1;

			echo "<td><div style='font-size:.9em;' class='evenrow'>" .
				
				"<em><a href='mailto:$comment[email]'>jkolman</a> - </em>" .
				"<span id='fullcmnt$pk$cline' >file is very large, can we compress some images?</span></div></td>";
		
?>
			<div id="addComment1" style="display:none;">
			<a href="<?= $_SERVER['PHP_SELF'] ?>" onClick="setAnchor('<?= $pk ?>');return false;" title="Save comments and any current votes" style="color:red;">Save New Comment</a><br/>
			<textarea name="cmnt<?= $pk ?>" cols="20" rows="5"></textarea>
			</div>
		</div>
</td>
</tr>
<tr>
	<td> &nbsp;</td>
	<td class="child"><input name='check' type='checkbox' value='' /><img src="../include/images/ppt.jpg" height=15  />
	<a href="#"> &nbsp; SI514 L4 W2007.ppt</a> &nbsp;&nbsp;

    </td>
	<td><?php include '../include/selection.php';?> </td>
	<td class="options" style="text-align:center;"> edit </td> <td  class="options" style="text-align:center;"><a href="edit_ip.php">view/update</a>  </td>
	 <td align=center><img src="../include/images/validated.gif" title="completed"> <a class="complete" href="#"> </a> </td>
	 <td>
	<?php include ('../include/comments.php'); ?>
</td>
</tr>
<tr>
	<td>&nbsp; </td>
	<td class="child"><input name='check' type='checkbox' value='' /><img src="../include/images/ppt.jpg" height=15  />
	<a href="#"> &nbsp; SI514 L5 W2007.ppt</a> &nbsp;&nbsp;

    </td>
	<td><?php  include '../include/selection.php';  $type="";?>   </td>
	<td class="options" style="text-align:center;"> edit </td> <td  class="options" style="text-align:center;"><a href="edit_ip.php">view/update</a>  </td>
	 <td align=center><img src="../include/images/required.gif" title="in progress" > <a href="#"></a>  </td>
	 <td >
		<div style="font-size:.95em;">
			
	<a id="onComment" href="<?= $_SERVER['PHP_SELF'] ?>" onClick="showAddComment('<?= $pk ?>');return false;" title="add a comment"><img src="../include/images/add.png" border=0 height="15" width="15"/></a>
					<br/>


<?php
		$cline = 1;

			echo "<td><div style='font-size:.9em;' class='evenrow'>" .
				
				"<em><a href='mailto:$comment[email]'>hardin</a> - </em>" .
				"<span id='fullcmnt$pk$cline' >need copyright info for imbedded images</span></div></td>";
		
?>
			<div id="addComment1" style="display:none;">
			<a href="<?= $_SERVER['PHP_SELF'] ?>" onClick="setAnchor('<?= $pk ?>');return false;" title="Save comments and any current votes" style="color:red;">Save New Comment</a><br/>
			<textarea name="cmnt<?= $pk ?>" cols="20" rows="5"></textarea>
			</div>
		</div>
</td>
</tr>


<tr><td><br/><br/><td></td></tr>

<tr>
	<td> <input name='check' type='checkbox' value='' /> </td>

	<td ><a href="#"><img src="../include/images/file_acrobat.gif" height=14 border="0" />&nbsp; Overview</a >&nbsp;&nbsp; 
    </td>
	<td><?php include '../include/selection.php';?> </td>
	<td class="options" style="text-align:center;"> edit </td> <td  class="options" style="text-align:center;"><a href="edit_ip.php">view/update</a>  </td>
	  <td align=center><img src="../include/images/validated.gif" title="completed"> <a class="complete" href="#"> </a> </td><td>
	<?php include ('../include/comments.php'); ?>
</td>
</tr>
<tr>
	<td> <input name='check' type='checkbox' value='' /> </td>

	<td ><a href="#"><img src="../include/images/page.png" height=14 border="0" />&nbsp; Requirements</a>&nbsp;&nbsp; 
    </td>
	<td><?php include '../include/selection.php';?> </td>
	<td class="options" style="text-align:center;"> edit </td> <td  class="options" style="text-align:center;"><a href="edit_ip.php">view/update</a>  </td>
	  <td align=center><img src="../include/images/validated.gif" title="completed"> <a class="complete" href="#"> </a> </td>
	  <td>
	<?php include ('../include/comments.php'); ?>
</td>
	</tr>
<tr>
	<td> <input name='check' type='checkbox' value='' /> </td>

	<td ><a href="#"><img src="../include/images/file_acrobat.gif" height=14 border="0" />&nbsp; Final Project Info</a>&nbsp;&nbsp;
    </td>
	<td><?php include '../include/selection.php';?>  </td>
	<td class="options" style="text-align:center;"> edit </td> <td  class="options" style="text-align:center;"><a href="edit_ip.php">view/update</a>  </td>
	 <td align=center><img src="../include/images/required.gif" title="in progress" > <a href="#"></a>  </td><td>
	<?php include ('../include/comments.php'); ?>
</td>
	</tr>


<tr><td><br/><br/><td></td></tr>

<tr>
	<td colspan="4" valign="top" style="padding:0px;">
		<div style="font-size: 1em; padding-top: 20px; padding-bottom:3px;"><img src="../include/images/collapse.gif"> &nbsp;&nbsp;<strong>Assignments</strong>
		
		</div>
		
	</td>
</tr><tr class="sectionHeader">
	<td><strong>Export</strong></td><td style="padding-left:0px; " ><strong>Name</strong></td>

	<td ><strong>Tag</strong></td>
	<td ><strong>Modify File</strong></td>
	<td ><strong>Modify IP</strong></td>
	<td><strong>IP Status</strong></td>
	<td colspan=2>&nbsp;&nbsp;&nbsp;<strong>Comments</strong></td>
</tr>
<tr>
	<td> <input name='check' type='checkbox' value='' /> </td>
	<td ><a href="#"><img src="generic.gif" border=0>&nbsp; 20-25 minute presentation on knowledge management systems</a> &nbsp;&nbsp;

    </td>
	<td><?php $type="Assignments"; include '../include/selection.php';  ?>   </td>
<td class="options" style="text-align:center;"> edit </td> <td  class="options" style="text-align:center;"><a href="edit_ip.php">view/update</a>  </td>
	 <td align=center><img src="../include/images/validated.gif" title="completed"> <a class="complete" href="#"> </a> </td>
	 <td>
	<?php include ('../include/comments.php'); ?>
</td></tr>
<tr>
	<td> <input name='check' type='checkbox' value='' /> </td>
	<td ><a href="#"><img src="generic.gif" border=0>&nbsp; 2 page report on Biases and Valuing Information</a> &nbsp;&nbsp;

    </td>
	<td><?php include '../include/selection.php';?>  </td>
<td class="options" style="text-align:center;"> edit </td> <td  class="options" style="text-align:center;"><a href="edit_ip.php">view/update</a>  </td>
	 <td align=center><img src="../include/images/validated.gif" title="completed"> <a class="complete" href="#"> </a> </td>
	 <td>
	<?php include ('../include/comments.php'); ?>
</td></tr>
<tr>
	<td> <input name='check' type='checkbox' value='' /> </td>
	<td ><a href="#"><img src="generic.gif" border=0>&nbsp; 10-15 page consulting report </a>&nbsp;&nbsp;

    </td>
	<td><?php include '../include/selection.php';  $type="";?>   </td>
<td class="options" style="text-align:center;"> edit </td> <td  class="options" style="text-align:center;"><a href="edit_ip.php">view/update</a>  </td>
	 <td align=center><img src="../include/images/validated.gif" title="completed"> <a class="complete" href="#"> </a> </td><td>
	<?php include ('../include/comments.php'); ?>
</td>
	 </tr>
	 

<tr><td><br/><br/><td></td></tr>
<tr>
	<td  colspan="3" valign="top" style="padding:0px;">
		<div style="font-size: 1em; padding-top: 10px; padding-bottom:10px;"><img src="../include/images/expand.gif">&nbsp;&nbsp;<strong>Melete Modules</strong>
		
		</div>

	</td>
</tr>
<tr>
	<td  colspan="3" valign="top" style="padding:0px;">
		<div style="font-size: 1em; padding-top: 10px; padding-bottom:10px;"><img src="../include/images/expand.gif">&nbsp;&nbsp;<strong>Schedule</strong>
		
		</div>
	</td>
</tr>

<tr>
			<td><br/>&nbsp;&nbsp;&nbsp;<input class="blue_submit" id="submitbutton" type="submit" name="login" value="Update" tabindex="3" /></td>
		</tr>
<tr>

	<td valign="top" colspan="2" style="padding:0px;">
		<div >
		
		</div>
	</td>
</tr>
</table>
</form>	</div>
</div><div class="clear">&nbsp;</div>
<div class="padding50">&nbsp;</div>