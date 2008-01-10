<?php $tags[0] = '-- select --'; ?>

<input type="hidden" id="cid" name="cid" value="<?=$cid?>" />

<h1>Materials</h1><br/>

<div class="column span-24 first last">
<?php if ($materials == null) { ?>

<p class="error">No materials found for this course</p>

<?php } else { 

 foreach($materials as $category => $cmaterial) { 
?> 
<h2><?=$category?></h2>
<table class="sortable-onload-1 rowstyle-alt no-arrow">
	<thead>
	<tr>
		<th class="sortable"><strong>Name</strong></th>
		<th class="sortable"><strong>Author</strong></th>
		<th><strong>Comments</strong></th>
		<th class="sortable"><strong>CO Status</strong></th>
		<th class="sortable"><strong>Ask Items?</strong></th>
		<!--
		<th><strong>Edit</strong></th>
		-->
	</tr>
	</thead>

	<tbody>
<?php foreach($cmaterial as $material) { 
	$objstats =  $this->coobject->object_stats($material['id']);

?>
	<tr>
		<td>
			<!--<a href="<?php echo site_url()."materials/edit/$cid/".$material['id']?>"> <?= $this->ocw_utils->icon($material['mimetype'])?>&nbsp;&nbsp;<?= $material['name']?>&nbsp;&nbsp;</a>-->
			<a href="<?php echo site_url()."materials/edit/$cid/".$material['id']?>"><?= $material['name']?>&nbsp;&nbsp;</a>
		</td>

		<?php if ($material['mimetype'] == 'folder') { ?>
		<td colspan="6">&nbsp;&nbsp;</td>
		<?php } else { ?>

		<td>
			<?= $material['author'] ?>
		<!--
			<?php echo form_dropdown('selectname_'.$material['id'], $tags,
						 $material['tag_id'],'class="update_tag" id="selectname_'.$material['id'].'"'); ?>
		-->
		</td>

		<td>
				 <small> 
					<?php
						if ($material['comments'] != null) { 
							echo $this->ocw_user->username($material['comments'][0]['user_id']).' - '.
								 character_limiter($material['comments'][0]['comments'].'&nbsp;',30);
							echo '<a href="'.site_url()."materials/edit/$cid/".$material['id'].'">more &raquo;</a>'; 
						} else {
							echo 'No comments..&nbsp;&nbsp;';
							echo '<a href="'.site_url()."materials/edit/$cid/".$material['id'].'">add &raquo;</a>'; 
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
		<?php echo ($material['embedded_co']==0) ? '(no CO)' : "&nbsp;({$material['statcount']})"; ?>
		</td>
		<!--
		<td>
			<a href="<?php echo site_url()."materials/edit/$cid/".$material['id']?>">modify</a>
		</td>
		-->
		<td>
			<b>
			 <?php 
				if ($objstats['ask'] > 0) { echo '<small>Yes&nbsp;(<a href="'.site_url("materials/viewform/ask/$cid/".$material['id']).'">view ASK form</a>)</small>'; } else { echo 'no ask items'; }?> 
			</b>
		</td>
		<?php } ?>
	</tr>
	<?php 
		if (@is_array($material['childitems'])) { 
			$childitems = $material['childitems'];
			$depth = 1;
			include property('app_views_abspath').'/materials/_childitems.php';
	    } 
	 ?>
<?php }?>
	</tbody>
</table>
<?php }}  ?>

</div>
