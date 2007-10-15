<?php 
	$this->load->view(property('app_views_path').'/dscribe/dscribe_header.php', $data); 
	$tags[0] = '-- select --';
?>

<input type="hidden" id="cid" name="cid" value="<?=$cid?>" />

<div id="tool_content">


<?php foreach($materials as $category => $cmaterial) { ?> 
<table class="rowstyle-alt no-arrow" width="780px">
	<caption><?=$category?></caption>
	<thead>
	<tr>
		<th><strong>Name</strong></th>
		<th><strong>Tag</strong></th>
		<th><strong>Comments</strong></th>
		<th><strong>IP Status</strong></th>
		<th>&nbsp;</th>
	</tr>
	</thead>

	<tbody>
<?php foreach($cmaterial as $material) { ?>
	<tr>
		<td>
			<a href="<?=site_url()."dscribe/materials/$cid/view/".$material['id']?>"><?= $this->ocw_utils->icon($material['mimetype'])?>
			&nbsp;&nbsp;<?= $material['name']?></a>&nbsp;&nbsp;
		</td>


		<?php if ($material['mimetype'] == 'folder') { ?>
		<td colspan="6">&nbsp;&nbsp;</td>
		<?php } else { ?>

		<td>
			<?php echo form_dropdown('selectname_'.$material['id'], $tags,
						 $material['tag_id'],'class="update_tag" id="selectname_'.$material['id'].'"'); ?>
		</td>

		<td>
				 <small> 
					<?php
						if ($material['comments'] != null) { 
							echo $this->ocw_user->username($material['comments'][0]['user_id']).' - '.
								 character_limiter($material['comments'][0]['comments'].'&nbsp;',30);
							echo '<a href="'.site_url()."dscribe/materials/$cid/edit_material/".$material['id'].'">more &raquo;</a>'; 
						} else {
							echo 'No comments..&nbsp;&nbsp;';
							echo '<a href="'.site_url()."dscribe/materials/$cid/edit_material/".$material['id'].'">add &raquo;</a>'; 
						}
					?>
				</small>
		</td>

		<td class="options">
			<?php if ($material['validated']) { ?>
				<img src="<?=property('app_img')?>/validated.gif" title="ready" />
			<?php } else { ?>
				<img src="<?=property('app_img')?>/required.gif" title="not ready" />
			<?php } ?>
			&nbsp;&nbsp;<a href="<?php echo site_url()."dscribe/materials/$cid/view_ip/".$material['id']?>">modify</a>
		</td>

		<td class="options">
			<a href="<?php echo site_url()."dscribe/materials/$cid/edit_material/".$material['id']?>">Edit &raquo;</a> 
		</td>

		<?php } ?>
	</tr>
	<?php 
		if (@is_array($material['childitems'])) { 
			$childitems = $material['childitems'];
			$depth = 1;
			include property('app_views_abspath').'/dscribe/_childitems.php';
	    } 
	 ?>
<?php }?>
	</tbody>
</table>
<?php }  ?>

</div>
