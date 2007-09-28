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
$PAGE_NAME="Edit IP";

?>
<link href="../include/ocw_tool.css" rel="stylesheet" type="text/css"/>
	
		<div>&nbsp;&nbsp;&nbsp; <a href="index.php">dScribe Home</a>&nbsp;| &nbsp;<a href="profile.php">Course &amp; Instructor Profiles</a>&nbsp; | &nbsp;<a href="copyright.php">Set Default Copyright</a>&nbsp; | &nbsp;Prepare Course Materials &nbsp; | &nbsp;<a href="../preview/course.php">Review for Export</a></div>
		<br/>
		<div id="tool_content">
		

	<form name="form1" id="form1" method="post" action="edit_ip.php">
<table id="materials_list" width="780"  cellpadding="5" cellspacing="0">

<tr>
	<td  colspan="3" valign="top" style="padding:0px;">
	<h3><a href="materials.php" style="font-size:.9em"> <img src="../include/images/Left.png" height="15" border="0">&nbsp;&nbsp;</a>Edit IP  	&nbsp;&nbsp;&nbsp;
	</h3><div style="font-size: 1em; padding-top: 0px; padding-bottom:3px;">
		<img src="../include/images/ppt.jpg" height=15  />&nbsp;&nbsp;<strong>SI514 L1 W2007.ppt </strong>
		</div>
	</td>
</tr>
<tr>
			<td colspan=3><div style="padding-right:30px;"> Copyright Holder: &nbsp;
		&nbsp;&nbsp;	<input type="text" name="username" tabindex="1" value=" SI 514 Instructor (default)" size="30" style="color:#333; font-size: .9em;" />
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="self_describe" tabindex="1" value="Y" checked="checked"/>&nbsp;&nbsp;
	Embedded IP Objects</div><br/>
	</td></tr>
	
</tr>
<tr><td><div><a href="add_ip_object.php"><img src="../include/images/add.png" style="border:0; ">&nbsp; Add IP Object</a>
	</div></td>	
</tr>

	
<tr class="sectionHeader">

	<td style="white-space:nowrap; padding: 0px 3px;"><strong>Name</strong></td>
	<td style="white-space:nowrap; padding: 0px 3px;"><strong>IP Type</strong></td>
	<td><strong>Details</strong></td>
	<td align=center>&nbsp;&nbsp;&nbsp;<strong>Complete</strong></td>
</tr>


<tr>
	<td class="child">WWW12 Conference Logo
    </td>
	<td>	<div >
		<select name="secondaryRole">
	<?  $type="image (gif)";
	if ($type) { echo "<option value=''>$type</option>";	} ?>
	
	<option value="">-- select --</option>
		<option value="">image (jpeg)</option>
		<option value="">image (gif)</option>
		<option value="">video (wav)</option>
		<option value="">video (mpeg)</option>
		<option value="">excerpt</option>
		<option value="">quote</option>
		<option value="">citation</option>
		
	</select>
		
		</div>  </td>
	<td  class="options" style="text-align:left;">view details  </td>
	 <td align=center> <input name='check' type='checkbox' value='' checked="checked" /> 
</td>
</tr>
<tr>
	<td class="child">
	Apple Logo

    </td>
	<td>	<div >
		<select name="secondaryRole">
	<?  $type="image (gif)";
	if ($type) { echo "<option value=''>$type</option>";	} ?>
	
	<option value="">-- select --</option>
		<option value="">image (jpeg)</option>
		<option value="">image (gif)</option>
		<option value="">video (wav)</option>
		<option value="">video (mpeg)</option>
		<option value="">excerpt</option>
		<option value="">quote</option>
		<option value="">citation</option>
		
	</select>
		
		</div>  </td>
	<td  class="options" style="text-align:left;">view details  </td>
	 <td align=center> <input name='check' type='checkbox' value='' checked="checked" /> 
</td>
</tr>
<tr>
	<td class="child">
	Long Tail chart

    </td>
	<td>	<div >
		<select name="secondaryRole">
	<?  $type="image (jpeg)";
	if ($type) { echo "<option value=''>$type</option>";	} ?>
	
	<option value="">-- select --</option>
		<option value="">image (jpeg)</option>
		<option value="">image (gif)</option>
		<option value="">video (wav)</option>
		<option value="">video (mpeg)</option>
		<option value="">excerpt</option>
		<option value="">quote</option>
		<option value="">citation</option>
		
		
	</select>
		
		</div>  </td>
	<td  class="options" style="text-align:left;"><div>
			
			<a id="onComment" href="<?= $_SERVER['PHP_SELF'] ?>" onClick="showAddComment('<?= $pk ?>');return false;" title="add a comment">view details</a>
			<br/>  </td>
	 <td align=center> <input name='check' type='checkbox' value='' checked="checked" /> 
</td>
</tr>
<tr>
<td colspan=4>

<table width=80% cellpadding=0 cellspacing=0 align=center ><tr><td >

<?php
		$cline = 0;

			
		
?>
			<div id="addComment<?= $pk ?>" style="display:none; font-size:.98em; padding: 5px; background:#eee; border:1px solid #ccc;"><br/><div style="text-align:right; ">
			<a href="<?= $_SERVER['PHP_SELF'] ?>" onClick="setAnchor('<?= $pk ?>');return false;" title="close"  style="" ><span style="text-align:right; padding-right:105px;">&nbsp;&nbsp; hide details  </span></a><br/>
		</div><div> updated 2/28/2007 by Matt<br/><hr>
		<div style="padding-left:30px;"><strong>Credit line</strong> &nbsp;&nbsp;&nbsp;<input type="text" name="username" tabindex="1" value=" &nbsp;[ attribute IP object ]" size="30" style="font-size:.98em;" /></div>
		<div style="padding-left:30px;"><br/><strong>Comments</strong> &nbsp;&nbsp;&nbsp;</div><div style="padding-left:100px;"><textarea name="cmnt<?= $pk ?>" cols="40" rows="4" style="color:#666; margin-top:0; font-size:.98em;">[ describe item and/or add a comment ]</textarea></div></div>
		<div style="padding-left:30px;"><br/><strong>Action Type</strong> &nbsp;&nbsp;&nbsp;
		
		<select name="secondaryRole">
	<?  $type="";
	if ($type) { echo "<option value=''>$type</option>";	} ?>
	
	<option value="">-- select IP action--</option>
		<option value="">Remove</option>
		<option value="">Replace</option>
		<option value="">Commission</option>
		<option value="">Permission</option>
		
	</select><br/><br/><br/>
		<input class="blue_submit" id="submitbutton" type="submit" name="login" value="update" tabindex="3" />&nbsp;&nbsp;&nbsp;
		<input class="blue_submit" id="submitbutton" type="submit" name="login" value="cancel" tabindex="3" />
	
		</div></div>
			</div>
			</div>
			
		</div>
</td></tr>
</table>
</td></tr>

<tr>
	<td class="child">
	Berners-Lee quote

    </td>
	<td>	<div >
		<select name="secondaryRole">
	<?  $type="quote";
	if ($type) { echo "<option value=''>$type</option>";	} ?>
	
	<option value="">-- select --</option>
		<option value="">image (jpeg)</option>
		<option value="">image (gif)</option>
		<option value="">video (wav)</option>
		<option value="">video (mpeg)</option>
		<option value="">excerpt</option>
		<option value="">quote</option>
		<option value="">citation</option>
		
		
	</select>
		
		</div>  </td>
	<td  class="options" style="text-align:left;"><div>
			
			view details
			<br/>  </td>
	 <td align=center> <input name='check' type='checkbox' value='' checked="checked" /> 
</td>
</tr><tr><td>&nbsp;</td>	<td>&nbsp;</td><td>&nbsp;</td><td><br/>	<input class="blue_submit" id="submitbutton" type="submit" name="login" value="Update" tabindex="3" />&nbsp;&nbsp;&nbsp;
	</td>
</tr>
<tr>
			<td><br/>&nbsp;&nbsp;&nbsp;</td>
		</tr>
<tr>

	<td valign="top" colspan="2" style="padding:0px;">
	
	</td>
</tr>
</table>
</form>	</div>
</div><div class="clear">&nbsp;</div>
<div class="padding50">&nbsp;</div>