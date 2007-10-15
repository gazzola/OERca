<?php foreach($childitems as $child) { ?>
<tr>
	<td>
		<?php echo nbs($depth*2); ?>
		<a href="<?php echo site_url()."dscribe/materials/$cid/view/".$child['id']?>"><?php echo $this->ocw_utils->icon($child['mimetype'])?>
		&nbsp;&nbsp;<?php echo $child['name']?></a>&nbsp;&nbsp;
	</td>

	<?php if ($child['mimetype'] == 'folder') { ?>
	<td colspan="4">&nbsp;&nbsp;</td>
	<?php } else { ?>
	<td>
		<?php echo form_dropdown('selectname_'.$child['id'], $tags,
						 $child['tag_id'],'class="update_tag" id="selectname_'.$child['id'].'"'); ?>
	</td>

	<td>
				 <small> 
					<?php
						if ($child['comments'] != null) { 
							echo $this->ocw_user->username($child['comments'][0]['user_id']).' - '.
								 character_limiter($child['comments'][0]['comments'].'&nbsp;',30);
							echo '<a href="'.site_url()."dscribe/materials/$cid/edit_material/".$child['id'].'">more &raquo;</a>'; 
						} else {
							echo 'No comments..&nbsp;&nbsp;';
							echo '<a href="'.site_url()."dscribe/materials/$cid/edit_material/".$child['id'].'">add &raquo;</a>'; 
						}
					?>
				</small>
	</td>

	<td class="options">
		<?php if ($child['validated']) { ?>
			<img src="<?php echo property('app_img')?>/validated.gif" title="ready" />
		<?php } else { ?>
			<img src="<?php echo property('app_img')?>/required.gif" title="not ready" />
		<?php } ?>
		&nbsp;&nbsp;<a href="<?php echo site_url()."dscribe/materials/$cid/view_ip/".$child['id']?>">modify</a>
	</td>

	<td class="options">
		<a href="<?php echo site_url()."dscribe/materials/$cid/edit_material/".$child['id']?>">Edit &raquo;</a> 
	</td>
	<?php } ?>
</tr>
	<?php 
		if (@is_array($child['childitems'])) { 
			$childitems = $child['childitems'];
			$depth++;
			include property('app_views_abspath').'/dscribe/_childitems.php';
	    } 
	 ?>
<?php } ?>
