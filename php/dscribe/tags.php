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
$PAGE_NAME="Manager Course Materials";

?>
<link href="../include/ocw_tool.css" rel="stylesheet" type="text/css"/>
		<div>&nbsp;&nbsp;&nbsp; <a href="index.php">dScribe Home</a>&nbsp;| &nbsp;<a href="profile.php">Course &amp; Instructor Profiles</a>&nbsp; | &nbsp;<a href="copyright.php">Set Default Copyright</a>&nbsp; | &nbsp;Prepare Course Materials &nbsp; | &nbsp;<a href="../preview/course.php">Review for Export</a></div>
		<br/>

<div id="tool_content">
<div style="text-align:left; margin-bottom:20px;  ">
	<div id="submenu" style="font-weight:normal; color:#929292">
		<div class="tab"><a href="materials.php">Prep Course Materials</a></div><div class="tab active">&nbsp;&nbsp;&nbsp;Edit Tags&nbsp;&nbsp;&nbsp;</div>
		
	</div>
 </div>

<div>		
			<div class ="instruction">You can use tags to help group course contents in OCW site. Current list of tag choice are listed below. You can make changes to the list either by adding more tags, deleting from existing ones or changing the tag description.</div>
			<form id="_id33" name="_id33" method="post" action="https://ctpilot.umich.edu/portal/tool/f999bebf-dc83-4ff0-00f5-a4d994eccfe3/tag" enctype="application/x-www-form-urlencoded"><input type="hidden" name="jsf_tree_64" id="jsf_tree_64" value="H4sIAAAAAAAAAK1WQW/TMBQ23QaahAQbk7ghEEgwBGmbtuvGTqzTRqdOQ9tAoB4qN/Fad44dYmcrF24cuO3CDYk72i/gF3CCK3+CK2fsxOvSzCVB4lAr7+W9733Pfv7S019ghgdgkwU9C/rQ6SPLe3sAHcSl6RPsQIEZtfYDhPZEEDoiDNA2pLCHgnvnzgbzfEYRFRuvb/78tPX7RwFMt8Fsx+lj4gbSD7baLVmiGJco6hLFRIli3hKrLXCt45xZDQI5F2C+NYBHsEgg7RVlPKY9GXb1PKzpvgHvQKENrnRUZSFTbrQTOTvdAXLE6tAPA031f+zG6tfT+9/m3p98KQAw9AEAl7iiAUK1zki7kLLT79P2gvm9L8Ci6mVoxVxHbVt94RHrmVx2QuGHYh8NhQDTHezWZM7DjBzZhwep28L0ME6q+rqeWudj35LBV/cn8EzErETGQmwsp0BmpK9cMjnLGdBRUOUMO7JsE066lchZywNeHwNPtx85je2s5AC3y0lw27QDtqkdu5IHvDYGbtoB29SOnXWcUdDKGLhpByolOXN3DFfLCblgnuXiI2sdH8UTUZGxjyfHQoIh7yJIrafqaU0+xXm2zLubMdcbLPDi6AvTVBhdqCeqOIeHEPsBU+JgDfhBAq1JeXTZpR5sI86lBIyuvW7XToNPp+ypibe5mtHBHiKS0RpjRDbe6CPnsMuGumxd5t8y579oNhgJPaoja1LstApaSgUtrYIn3199vs4XyUi1Ioau2h4BLvcRdFFgON6lSWOSrTSxOrVgF5EYrFqKoLsaennEIMFFPfQMPCZeNAEeZfBoUi2SMEBQgNk+7vWJ/Ikkm2r5H9hU1UCWMmZpg0DRwlykJ6ia7iQ9MYVEQU8XPBMxWfdB3nblsVJ0vA97Ob4kzyFFZDNgoa8LXmBpYlVPCsKxAHNxvXXEnQD76hZFbz7o8L/MUtYZ6q/WWigEk5M+BV13DHo5x8boHrGrcypJ9h/lbvGw62GR9jqQOohE5q4+QqVjlYzjf4nRcUNyVX9nUhNQVg3nSh/lKV1TSnt7kgao+F3GhO//AYqfGqL9CQAA" /><input type="hidden" name="jsf_state_64" id="jsf_state_64" value="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" /><input type="hidden" name="jsf_viewid" id="jsf_viewid" value="/ocw/tag.jsp" />			
				<br/>
<table cellspacing="0" style="font-size: 1em;">
<tr><td style="padding-right: 20px; border-right: 1px dotted #ccc;"><table cellspacing="0" style="font-size: 1em;">
<thead>
<tr><th>Remove</th><th>Tag</th><th>Description</th></tr></thead>

<tr><td class="attach"><input type="checkbox" name="_id33:_id34_0:_id37" value="true" /></td><td class="taglist"><label for="_id33:_id34_0:highlight">Assignments&nbsp;&nbsp;</label></td><td class="taglist"><input type="text" size="30" name="_id33:_id34_0:highlight"  /></td></tr>
<tr><td class="attach"><input type="checkbox" name="_id33:_id34_1:_id37" value="true" /></td><td class="taglist"><label for="_id33:_id34_1:highlight">Discussion Group&nbsp;&nbsp;</label></td><td class="taglist"><input type="text" size="30" name="_id33:_id34_1:highlight"  /></td></tr>

<tr><td class="attach"><input type="checkbox" name="_id33:_id34_2:_id37" value="true" /></td><td class="taglist"><label for="_id33:_id34_2:highlight">Exams&nbsp;&nbsp;</label></td><td class="taglist"><input type="text" size="30" name="_id33:_id34_2:highlight"  /></td></tr>
<tr><td class="attach"><input type="checkbox" name="_id33:_id34_3:_id37" value="true" /></td><td class="taglist"><label for="_id33:_id34_3:highlight">Labs&nbsp;&nbsp;</label></td><td class="taglist"><input type="text" size="30" name="_id33:_id34_3:highlight"  /></td></tr>
<tr><td class="attach"><input type="checkbox" name="_id33:_id34_4:_id37" value="true" /></td><td class="taglist"><label for="_id33:_id34_4:highlight">Lecture Notes&nbsp;&nbsp;</label></td><td class="taglist"><input type="text" size="30" name="_id33:_id34_4:highlight"  /></td></tr>
<tr><td class="attach"><input type="checkbox" name="_id33:_id34_5:_id37" value="true" /></td><td class="taglist"><label for="_id33:_id34_5:highlight">Pictures&nbsp;&nbsp;</label></td><td class="taglist"><input type="text" size="30" name="_id33:_id34_5:highlight"  cols=50" rows="1"Pictures from course lectures</textarea></td></tr>
<tr><td class="attach"><input type="checkbox" name="_id33:_id34_6:_id37" value="true" /></td><td class="taglist"><label for="_id33:_id34_6:highlight">Projects&nbsp;&nbsp;</label></td><td class="taglist"><input type="text" size="30" name="_id33:_id34_6:highlight"  /></td></tr>
<tr><td class="attach"><input type="checkbox" name="_id33:_id34_7:_id37" value="true" /></td><td class="taglist"><label for="_id33:_id34_7:highlight">Readings&nbsp;&nbsp;</label></td><td class="taglist"><input type="text" size="30" name="_id33:_id34_7:highlight"  /></td></tr>

<tr><td class="attach"><input type="checkbox" name="_id33:_id34_8:_id37" value="true" /></td><td class="taglist"><label for="_id33:_id34_8:highlight">Related Resources&nbsp;&nbsp;</label></td><td class="taglist"><input type="text" size="30" name="_id33:_id34_8:highlight"  /></td></tr>
<tr><td class="attach"><input type="checkbox" name="_id33:_id34_9:_id37" value="true" /></td><td class="taglist"><label for="_id33:_id34_9:highlight">Schedule&nbsp;&nbsp;</label></td><td class="taglist"><input type="text" size="30" name="_id33:_id34_9:highlight"  /></td></tr>
<tr><td class="attach"><input type="checkbox" name="_id33:_id34_10:_id37" value="true" /></td><td class="taglist"><label for="_id33:_id34_10:highlight">Syllabus&nbsp;&nbsp;</label></td><td class="taglist"><input type="text" size="30" name="_id33:_id34_10:highlight"  /></td></tr>
<tr><td class="attach"><input type="checkbox" name="_id33:_id34_11:_id37" value="true" /></td><td class="taglist"><label for="_id33:_id34_11:highlight">Video Lectures&nbsp;&nbsp;</label></td><td class="taglist"><input type="text" size="30" name="_id33:_id34_11:highlight" /></td></tr>
<tr><td class="attach"><input type="checkbox" name="_id33:_id34_12:_id37" value="true" /></td><td class="taglist"><label for="_id33:_id34_12:highlight">module 1&nbsp;&nbsp;</label></td><td class="taglist"><input type="text" size="30" name="_id33:_id34_12:highlight" cols=50" rows="1"description of module 1</textarea></td></tr></tbody></table>
</td><td style="width:40px;">&nbsp;</td><td valign="top">
				<h4>Add a new tag</h4>

				<table border="0" cellpadding="0" cellspacing="0" class="itemSummary highlightPanel"><tbody><tr><td><span class="shorttext"><label for="_id33:newTag">Tag label &nbsp;&nbsp;</label><input id="_id33:newTag" name="_id33:newTag" type="text" value="" /></span></td></tr>
<tr><td><span style="display:block;margin:0" class="longtext"><label class="block" for="_id33:newTagDescription">Description </label><input type="text" size="30" name="_id33:newTagDescription" id="_id33:newTagDescription" /></span></td></tr>
<tr><td><span style="display:block;text-align:right"><input class="blue_submit"  name="_id33:add" type="submit" value="Add" onclick="clear__5Fid33();" class="active" /></span></td></tr>
</table>
</td></tr></table>
				<div class="act">
					<input class="blue_submit" name="_id33:submit" type="submit" value="Update" onclick="clear__5Fid33();" class="active" />
					<input class="blue_submit"  name="_id33:cancel" type="submit" value="Cancel" onclick="clear__5Fid33();" />
				</div></div>
</div>
</div>