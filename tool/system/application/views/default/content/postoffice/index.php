<div class="column span-24 first last">

<p style="font-weight: bold">
	<form action="<?=site_url('postservice')?>" method="post">
		Get <?php echo form_dropdown('sorttype', $select_sorttypes, $sorttype, 'id="sorttype"') ?> emails	
	 <input type="submit" name="go" value="go" />
	</form>
</p>

<br/>

<?php if ($queue == null) { ?>

<p class="error">We did not find any email in the queue.</p>

<?php 
	} else { 
?>
<p><em>Note: Hold down the shift key to select multiple columns to sort</em></p>
<table class="sortable-onload-8 rowstyle-alt no-arrow">
    <thead>
    <tr>
        <th class="sortable">Type</th>
        <th class="sortable">From</th>
        <th class="sortable">To</th>
        <th class="sortable">Sent</th>
        <th class="sortable">Course</th>
        <th class="sortable">Material</th>
        <th class="sortable">Object</th>
        <th class="sortable-sortEnglishLonghandDateFormat">Created</th>
        <th class="sortable-sortEnglishLonghandDateFormat">Last Modified</th>
    </tr>
    </thead>

    <tbody>
		<?php foreach($queue as $e) { ?>
			<tr>
				<td><?= preg_replace('/_/',' ',$e->msg_type)?></td>
        <td><?=$this->ocw_user->goofyname($e->from_id)?></td>
        <td><?=$this->ocw_user->goofyname($e->to_id)?></td>
				<td>
  			<?php if ($e->sent=='yes') { ?>
  				<img src="<?=property('app_img')?>/validated.gif" title="sent" />
  			<?php } else { ?>
  				<img src="<?=property('app_img')?>/cross.png" title="not sent" />
  			<?php } ?>
				</td>
				<td><?=$this->course->course_title($e->course_id)?></td>
				<td><?=$this->material->getMaterialName($e->material_id)?></td>
				<td><?php
						 if ($e->object_type == 'original') {
						 		 $co = $this->coobject->coobjects($e->material_id, $e->object_id);
						  } else {
						 		 $co = $this->coobject->replacements($e->material_id, '', $e->object_id);
							}
						  echo $co[0]['name'];
				?>
				</td>
        <td><?=mdate('%d %M, %Y',mysql_to_unix($e->created_at))?></td>
        <td><?=mdate('%d %M, %Y',mysql_to_unix($e->modified_on))?></td>
			</tr>	
		<?php } ?>
		</tbody>
</table>

<?php } ?>

</div>
