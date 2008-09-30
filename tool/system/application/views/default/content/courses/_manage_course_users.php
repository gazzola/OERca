<?php
echo style('blueprint/screen.css',array('media'=>"screen, projection"));
echo style('blueprint/print.css',array('media'=>"print"));
echo '<!--[if IE]>'.style('blueprint/lib/ie.css',array('media'=>"screen, projection")).'<![endif]-->';
echo style('style.css',array('media'=>"screen, projection"));
echo style('table.css',array('media'=>"screen, projection"));
echo '<style type="text/css">body { background-color: #222; padding: 15px; margin:auto; width: 550px; border:0px solid blue; height:150px; color:#999}</style>';

echo script('mootools.js');
echo script('event-selectors.js');
echo script('event-rules.js');
echo script('flash.js');

$flash=$this->db_session->flashdata('flashMessage');
if (isset($flash) AND $flash!='') {
?>
<div id="statusmsg" class="column span-20 first last">
  <div id="flashMessage" style="display:none;"><?=$flash?></div>
</div>
<?php } ?>

<h2>Manage users for course: "<?php echo "$ctitle"?>"</h2>

<form name="editcourseusersform" method="post" action="<?php echo site_url("admin/courses/manage_users/$cid")?>" enctype="multipart/form-data" style="margin:0px;">

	<table>
    <tr>
      <th style=""> Remove a user: &nbsp;</th>
      <td><?php echo $rem_box?></td>
			<td><input id="removebutton" type="submit" name="remove" value="Remove" /></td>
    </tr>

    <tr>
			<th style=""> Add a user: &nbsp;</th>
			<td><?php echo $add_box?></td>
			<td><input id="addbutton" type="submit" name="add" value="Add" /></td>
		</tr>
 	</table>
	
</form>

<br style="clear:both"/>

<input type="button" style="float:right" onclick="parent.window.location.reload(); parent.TB_remove();" value="Close" />