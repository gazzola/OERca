<?php
echo style('blueprint/screen.css',array('media'=>"screen, projection"));
echo style('blueprint/print.css',array('media'=>"print"));
echo '<!--[if IE]>'.style('blueprint/lib/ie.css',array('media'=>"screen, projection")).'<![endif]-->';
echo style('style.css',array('media'=>"screen, projection"));
echo style('table.css',array('media'=>"screen, projection"));
echo '<style type="text/css">body { background-color: #222; padding: 15px; margin:auto; width: 300px; border:0px solid blue; height:550px; color:#999}</style>';

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

<div class="column span-6 first last">
	<h2>Edit User Profile</h2>
</div>

<div class="column span-6 first last">

<?php if ($profile !== false && is_array($profile)) { ?>

<form name="adminform" method="post" action="<?php echo site_url("admin/users/editprofile/$defuser/$uid")?>" enctype="multipart/form-data" style="margin:0px;padding:0">
	<input type="hidden" name="task" value="update" />
	<table>
	  <tr><th style=""><span style="color:red">*</span> Title: &nbsp;&nbsp;</th></tr>
		<tr><td><input type="text" id="title" name="profile[title]" tabindex="4" size="40" value="<?=$profile['title']?>" /></td></tr>
			
		<tr><th style=""><span style="color:red">*</span> Information: &nbsp;&nbsp;</th></tr>
		<tr><td><textarea id="info" name="profile[info]" tabindex="5" style="width:300px; height:100px;"><?=$profile['info']?></textarea></td></tr>
			
		<tr><th style="">URI: &nbsp;&nbsp;</th></tr>
		<tr><td><input type="text" id="uri" name="profile[uri]" tabindex="6" size="40" value="<?=$profile['uri']?>" /></td></tr>
			
		<tr><th style="">Image: &nbsp;&nbsp;</th></tr>
		<tr>
				<td style="vertical-align:top;">
					<input type="file" id="photo" name="profile" tabindex="7" size="20"/>
					<img src="<?=site_url('admin/profileimage/'.$uid)?>" width="48px" height="48px" style="margin-top:-20px; float:right"/>
				</td>
		</tr>
    <tr><td><input id="submitbutton" type="submit" name="submit" value="Update Profile" /></td></tr>
 	</table>
</form>

<?php } else { ?>

<p class="error">Cannot find a profile for this user. Please add one below:</p>

<form name="adminform" method="post" action="<?php echo site_url("admin/users/editprofile/$defuser/$uid")?>" enctype="multipart/form-data" style="margin:0px;padding:0">
	<input type="hidden" name="task" value="add" />
	<table>
			    <tr><th style=""><span style="color:red">*</span> Title: &nbsp;&nbsp;</th></tr>
					<tr><td><input type="text" id="title" name="profile[title]" tabindex="4" size="40" /></td></tr>
			
			    <tr><th style=""><span style="color:red">*</span> Information: &nbsp;&nbsp;</th></tr>
			    <tr><td><textarea id="info" name="profile[info]" tabindex="5" style="width:300px; height:100px;"></textarea></td></tr>
			
			    <tr><th style="">URI: &nbsp;&nbsp;</th></tr>
			    <tr><td><input type="text" id="uri" name="profile[uri]" tabindex="6" size="40" /></td></tr>
			
			    <tr><th style="">Image: &nbsp;&nbsp;</th></tr>
			    <tr><td><input type="file" id="photo" name="profile" tabindex="7" size="30"/></td></tr>

    			<tr><td><input id="submitbutton" type="submit" name="submit" value="Add Profile" /></td></tr>
 	</table>
</form>

<?php } ?>

</div>

<br style="clear:both"/>

<div class="column span-5 first last" style="margin-top:20px;">
	<input type="button" style="float:right" onclick="parent.window.location.reload(); parent.TB_remove();" value="Close" />
</div>
