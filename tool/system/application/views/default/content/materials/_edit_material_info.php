<?php	
echo style('blueprint/screen.css',array('media'=>"screen, projection"));
echo style('blueprint/print.css',array('media'=>"print"));
echo '<!--[if IE]>'.style('blueprint/lib/ie.css',array('media'=>"screen, projection")).'<![endif]-->';
echo style('style.css',array('media'=>"screen, projection"));
echo '<style type="text/css">body { background-color: #222; padding: 15px; margin:auto; width: 340px; border:0px solid blue; height:450px; color:#999}</style>';

echo script('mootools.js'); 
echo script('event-selectors.js');
echo script('event-rules.js');

$tags[0] = '-- select --';
$copyholder = ($material['author']=='') ? $course['director'] : $material['author'];
?>
<h2>Edit Material</h2>

<div class="column span-7 first last">
		<div class="formLabel">Name:</div>
		<div class="formField">
			<input type="text" name="name" id="name" class="update_material input" size="40px" value="<?=$material['name']?>" />
		</div>

		<br/>

		<div class="formLabel">Author:</div>
		<div class="formField">
			<input type="text" name="author" id="author" class="update_material input" size="40px" value="<?=$copyholder?>" />
		</div>

		<br/>

		<div class="formLabel">Collaborators:</div>
		<div class="formField">
			<textarea name="collaborators" id="collaborators" style="width:290px;height:100px;" class="update_material"><?=$material['collaborators']?></textarea>
		</div>

		<br/>

		<div class="formLabel">Material Type:</div>
		<div class="formField">
			<?php echo form_dropdown('selectname_'.$material['id'], $tags, $material['tag_id'],'class="update_tag" id="selectname_'.$material['id'].'"'); ?>
		</div>

		<br/>

		<div class="formLabel">File Type:</div>
		<div class="formField">
				<?php echo form_dropdown('mimetype_id', $mimetypes, $material['mimetype_id'], 'class="update_material"'); ?>
		</div>

		<br/>

		<div class="formField">Embedded COs?:&nbsp;&nbsp;
			<?php if ($material['embedded_co']==1) { ?>
        		<input type="radio" name="embedded_co" id="emip_yes" class="update_material" value="1" checked="checked" />&nbsp;Yes
        		<input type="radio" name="embedded_co" id="emip_no" class="update_material" value="0" />&nbsp;No
    		<?php } else { ?>
        		<input type="radio" name="embedded_co" id="emip_yes" class="update_material" value="1" />&nbsp;Yes
        		<input type="radio" name="embedded_co" id="emip_no" class="update_material" value="0" checked="checked" />&nbsp;No
    <?php }  ?>
		</div>
		
		<br/>

  <input type="button" value="Save" onclick="parent.window.location.reload(); parent.TB_remove();"/>
	&nbsp; &nbsp; &nbsp; &nbsp;
  <input type="button" value="Cancel" onclick="parent.TB_remove();"/>

	<div id="feedback" style="display:none"></div>
	<input type="hidden" id="cid" name="cid" value="<?=$cid?>" />
	<input type="hidden" id="mid" name="mid" value="<?=$mid?>" />
	<input type="hidden" id="defcopy" name="defcopy" value="<?=$course['director']?>" />
	<input type="hidden" id="imgurl" value="<?=property('app_img')?>" />
	<input type="hidden" id="server" value="<?=site_url();?>" />
  <script type="text/javascript">EventSelectors.start(Rules);</script>
</div>
