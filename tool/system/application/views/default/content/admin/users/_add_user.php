<?php
echo style('blueprint/screen.css',array('media'=>"screen, projection"));
echo style('blueprint/print.css',array('media'=>"print"));
echo '<!--[if IE]>'.style('blueprint/lib/ie.css',array('media'=>"screen, projection")).'<![endif]-->';
echo style('style.css',array('media'=>"screen, projection"));
echo style('table.css',array('media'=>"screen, projection"));
echo '<style type="text/css">body { background-color: #222; padding: 15px; margin:auto; width: 400px; border:0px solid blue; height:550px; color:#999}</style>';

echo script('mootools.js');
echo script('event-selectors.js');
echo script('event-rules.js');
echo script('flash.js');

$flash=$this->db_session->flashdata('flashMessage');
if (isset($flash) AND $flash!='') {
?>
<div id="statusmsg" class="column span-10 first last">
  <div id="flashMessage" style="display:none;"><?=$flash?></div>
</div>
<?php } ?>

<h2>Add a User</h2>

<form name="adminform" method="post" action="<?php echo site_url("admin/users/add_user/$defuser")?>" enctype="multipart/form-data" style="margin:0px;">

	<table>
    <tr>
      <th style="text-align:right">Role: &nbsp;&nbsp;</th>
      <td><?php echo form_dropdown('role', $roles, (($role<>'')?$role:$defuser)) ?></td>
		</tr>

    <tr>
      <th style="text-align:right"><span style="color:red">*</span> Name: &nbsp;&nbsp;</th>
      <td><input type="text" id="name"  name="name" tabindex="1" size="40" /></td>
    </tr>

    <tr>
      <th style="text-align:right"><span style="color:red">*</span> Email: &nbsp;&nbsp;</th>
      <td><input type="text" id="email" name="email" tabindex="2" size="40" /></td>
    </tr>

    <tr>
      <th style="text-align:left"><span style="color:red">*</span>Username:</th>
      <td><input type="text" id="user_name" name="user_name" tabindex="3" size="40" /></td>
    </tr>

		<tr>
			<td colspan="2" style="border-bottom:none;">	
				<div style="text-align:left" id="originfo-show">	
					<a href="javascript:void(0);" onclick="$('originfo-other').style.display='block';$('originfo-show').style.display='none';$('originfo-hide').style.display='block'">Add Profile</a>
				</div>
				<div style="text-align:left; display:none" id="originfo-hide">	
					<a href="javascript:void(0);" onclick="$('originfo-other').style.display='none';$('originfo-hide').style.display='none';$('originfo-show').style.display='block'">Hide &raquo;</a>
				</div>
			</td>
		</tr>

		<tr>
			<td colspan="2">
				<div id="originfo-other" style="display: none; width:300px;">
				<table width="300px">
			    <tr><th style=""><span style="color:red">*</span> Title: &nbsp;&nbsp;</th></tr>
					<tr><td><input type="text" id="title" name="profile[title]" tabindex="4" size="40" /></td></tr>
			
			    <tr><th style=""><span style="color:red">*</span> Information: &nbsp;&nbsp;</th></tr>
			    <tr><td><textarea id="info" name="profile[info]" tabindex="5" style="width:300px; height:100px;"></textarea></td></tr>
			
			    <tr><th style="">URI: &nbsp;&nbsp;</th></tr>
			    <tr><td><input type="text" id="uri" name="profile[uri]" tabindex="6" size="40" /></td></tr>
			
			    <tr><th style="">Image: &nbsp;&nbsp;</th></tr>
			    <tr><td><input type="file" id="photo" name="profile" tabindex="7" size="30"/></td></tr>
				</table>
				</div>
			</td>
		</tr>

    <tr>
			<th></th>
      <td><input id="submitbutton" type="submit" name="submit" value="Add User" /></td>
    </tr>
 	</table>

</form>

<br style="clear:both"/>

<input type="button" style="float:right" onclick="parent.window.location.reload(); parent.TB_remove();" value="Close" />
