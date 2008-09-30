<?php
echo style('blueprint/screen.css',array('media'=>"screen, projection"));
echo style('blueprint/print.css',array('media'=>"print"));
echo '<!--[if IE]>'.style('blueprint/lib/ie.css',array('media'=>"screen, projection")).'<![endif]-->';
echo style('style.css',array('media'=>"screen, projection"));
echo style('table.css',array('media'=>"screen, projection"));
echo '<style type="text/css">body { background-color: #222; padding: 15px; margin:auto; width: 500px; border:0px solid blue; height:400px; color:#999}</style>';

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

<?php if ($subj !== false && is_array($subj)) { ?>

	<h2>Edit <?=$subj['subj_code']?>:<?=$subj['subj_desc']?>'s Info</h2>

<form name="adminform" method="post" action="<?php echo site_url("admin/subjects/edit_subject/{$sid}/{$subj['id']}")?>" enctype="multipart/form-data" style="margin:0px;">

	<table>
		<tr>
			<th style="text-align:right"><span style="color:red">*</span>Code: &nbsp;&nbsp;</th>
			<td><input type="text" id="subj_code" name="subj_code" tabindex="1" size="40" value="<?=$subj['subj_code']?>" ></td>
		</tr>

		<tr>
			<th style="text-align:right"><span style="color:red">*</span>Description: &nbsp;&nbsp;</th>
			<td><input type="text" id="subj_desc" name="subj_desc" tabindex="2" size="40" value="<?=$subj['subj_desc']?>" ></td>
		</tr>
		
		<tr>
			<th></th>
			<td><input id="submitbutton" type="submit" name="submit" value="Edit Subject" /></td>
		</tr>
 	</table>

	
</form>

<?php } ?>

<br style="clear:both"/>

<input type="button" style="float:right" onclick="parent.window.location.reload(); parent.TB_remove();" value="Close" />
