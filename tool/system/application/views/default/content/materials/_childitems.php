<?php foreach($childitems as $child) { ?>
<tr>
	<td>
		<?php echo nbs($depth*2); ?>
			<a href="<?php echo site_url()."materials/edit/$cid/".$child['id']?>"><?= $child['name']?>&nbsp;&nbsp;</a>
	</td>

	<?php if ($child['mimetype'] == 'folder') { ?>
	<td colspan="4">&nbsp;&nbsp;</td>
	<?php } else { ?>

	<td>
		<?= $child['author'] ?>
	</td>

	<td class="options">
		<?php if ($child['validated']) { ?>
			<img src="<?php echo property('app_img')?>/validated.gif" title="ready" />
		<?php } else { ?>
			<img src="<?php echo property('app_img')?>/required.gif" title="not ready" />
		<?php } ?>
		<?php echo ($child['embedded_co']==0) ? '(no CO)' : "&nbsp;({$child['statcount']})"; ?>
	</td>
		<td>
			<b>
			 <?php 
				if ($objstats['ask'] > 0) { echo '<small>Yes&nbsp;(<a href="'.site_url("materials/askforms/$cid/".$child['id']).'">view ASK form</a>)</small>'; } else { echo 'no ask items'; }?> 
			</b>
		</td>
	<?php } ?>
</tr>
	<?php 
		if (@is_array($child['childitems'])) { 
			$childitems = $child['childitems'];
			$depth++;
			include property('app_views_abspath').'/materials/_childitems.php';
	    } 
	 ?>
<?php } ?>
