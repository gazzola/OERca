<?php	
  $tags[0] = '-- select --';
  $copyholder = ($material['author']=='') ? $course['director'] : $material['author'];
?>

<div id="pane_matinfo" class="editpane">

<div class="column span-7 first colborder">
		<div class="formLabel">Name:</div>
		<div class="formField">
			<input type="text" name="name" id="name" class="update_material input" size="40px" value="<?=$material['name']?>" />
		</div>

		<div class="formLabel">Author:</div>
		<div class="formField">
			<input type="text" name="author" id="author" class="update_material input" size="40px" value="<?=$copyholder?>" />
		</div>

		<div class="formLabel">Collaborators:</div>
		<div class="formField">
			<textarea name="collaborators" id="collaborators" class="update_material" cols="40" rows="4"><?=$material['collaborators']?></textarea>
		</div>
</div>

<div class="column span-14 last">
		<div class="formLabel">Tag:</div>
		<div class="formField">
			<?php echo form_dropdown('selectname_'.$material['id'], $tags, $material['tag_id'],'class="update_tag" id="selectname_'.$material['id'].'"'); ?>
		</div>

		<div class="formLabel">Mimetype:</div>
		<div class="formField">
				<?php echo form_dropdown('mimetype_id', $mimetypes, $material['mimetype_id'], 'class="update_material"'); ?>
		</div>


		<div class="formLabel">Embedded COs?:</div>
		<div class="formField">
			<?php if ($material['embedded_co']==1) { ?>
        		<input type="radio" name="embedded_co" id="emip_yes" class="update_material" value="1" checked="checked" />&nbsp;Yes
        		<input type="radio" name="embedded_co" id="emip_no" class="update_material" value="0" />&nbsp;No
    		<?php } else { ?>
        		<input type="radio" name="embedded_co" id="emip_yes" class="update_material" value="1" />&nbsp;Yes
        		<input type="radio" name="embedded_co" id="emip_no" class="update_material" value="0" checked="checked" />&nbsp;No
    <?php }  ?>
		</div>
</div>

<div class="column span-21 first last">
  <input type="button" value="Done" id="do_close_matinfo_pane"/>
</div>

</div>
