<?php
echo style('blueprint/screen.css',array('media'=>"screen, projection"));
echo style('blueprint/print.css',array('media'=>"print"));
echo '<!--[if IE]>'.style('blueprint/lib/ie.css',array('media'=>"screen, projection")).'<![endif]-->';
echo style('style.css',array('media'=>"screen, projection"));
echo style('table.css',array('media'=>"screen, projection"));
echo '<style type="text/css">body { background-color: #222; padding: 15px; margin:auto; width: 280px; border:0px solid blue; height:450px; color:#999}</style>';

echo script('mootools.js');
echo script('event-selectors.js');
echo script('event-rules.js');
echo script('flash.js');

$flash=$this->db_session->flashdata('flashMessage');
if (isset($flash) AND $flash!='') {
?>
<div id="statusmsg" class="column span-6 first last">
  <div id="flashMessage" style="display:none;"><?=$flash?></div>
</div>
<?php } ?>

<div class="column span-6 first last">
	<h2>Edit User</h2>
</div>

<div class="column span-6 first last">

<?php if ($user !== false && is_array($user)) { ?>

<form name="adminform" method="post" action="<?php echo site_url("admin/users/editinfo/$defuser/{$user['id']}")?>" enctype="multipart/form-data" style="margin:0px; padding:0">

	<table>
    <tr>
      <th style="text-align:right">Role: &nbsp;&nbsp;</th>
      <td><?php echo form_dropdown('role', $roles, $user['role']) ?></td>
		</tr>

    <tr>
      <th style="text-align:right"><span style="color:red">*</span> Name: &nbsp;&nbsp;</th>
      <td><input type="text" id="name"  name="name" tabindex="1" size="30" value="<?=$user['name']?>" /></td>
    </tr>

    <tr>
      <th style="text-align:right"><span style="color:red">*</span> Email: &nbsp;&nbsp;</th>
      <td><input type="text" id="email" name="email" tabindex="2" size="30" value="<?=$user['email']?>" /></td>
    </tr>

    <tr>
      <th style="text-align:left"><span style="color:red">*</span>Username:</th>
      <td><input type="text" id="user_name" name="user_name" tabindex="3" size="30" value="<?=$user['user_name']?>" /></td>
    </tr>

    <tr>
			<th></th>
      <td><input id="submitbutton" type="submit" name="submit" value="Update" /></td>
    </tr>
 	</table>

</form>

<?php } else { ?>

	<p class="error">Cannot find the details for this user. Please contact the administrator.</p>

<?php } ?>

</div>

<br style="clear:both"/>

<div class="column span-5 first last">
	<input type="button" style="float:right" onclick="parent.window.location.reload(); parent.TB_remove();" value="Close" />
</div>
