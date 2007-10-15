<?php foreach($childitems as $child) { ?>
<tr>
	<?php if ($child['mimetype'] == 'folder') { ?>
	<td colspan="4">
		<?php echo nbs($depth*2); ?>
		<?php echo $this->ocw_utils->icon($child['mimetype'])?>
		&nbsp;&nbsp;<?php echo $child['name']?>&nbsp;&nbsp;
	</td>

	<?php } else { ?>
	<td>
		<?php echo nbs($depth*2); ?>
		<a href="<?php echo site_url()."dscribe/materials/$cid/view/".$child['id']?>"><?php echo $this->ocw_utils->icon($child['mimetype'])?>
		&nbsp;&nbsp;<?php echo $child['name']?></a>&nbsp;&nbsp;
	</td>
	<td><?php echo $child['author']?></td>
	<td>
			<a href="<?php echo site_url()."dscribe/materials/$cid/edit_material/".$child['id']?>">Edit</a>
	</td>
	 <td>
		<a href="<?php echo site_url()."dscribe/materials/$cid/view_ip/".$child['id']?>">Modify IP</a>
	</td>
	<?php } ?>
</tr>
	<?php 
		if (@is_array($child['childitems'])) { 
			$childitems = $child['childitems'];
			$depth++;
			include property('app_views_abspath').'/dscribe2/_childitems.php';
	    } 
	 ?>
<?php } ?>
