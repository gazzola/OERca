<?php $this->load->view(property('app_views_path').'/dscribe2/dscribe2_header.php', $data); ?>

<?php if ($courses == null) { ?>

<p class="error">We did not find any courses for you to process yet.</p>

<?php 
	} else { 
		foreach($courses as $school => $curriculum) {
?>

<h2><?= $school ?></h2>
<p><em>Note: Hold down the shift key to select multiple columns to sort</em></p>
<table class="sortable-onload-1 rowstyle-alt no-arrow">
    <thead>
    <tr>
        <th class="sortable">Title</th>
        <th class="sortable-sortEnglishLonghandDateFormat">Start Date</th>
        <th class="sortable-sortEnglishLonghandDateFormat">End Date</th>
        <th class="sortable">Curriculum</th>
        <th class="sortable">Director</th>
		<!--
		<th>Edit&nbsp;</th>
		-->
    </tr>
    </thead>
    <tbody>

	<?php foreach($curriculum as $course)	{ ?>
		<?php foreach($course as $c) { ?>
	<tr>
		<td><?=anchor(site_url('dscribe1/materials/'.$c['id']),$c['number'].' '.$c['title'],array('title'=>'Edit course materials'))?></td>
        <td><?=mdate('%d %M, %Y',mysql_to_unix($c['start_date']))?></td>
        <td><?=mdate('%d %M, %Y',mysql_to_unix($c['end_date']))?></td>
        <td width="40px"><?=ucfirst($c['cname'])?></td>
        <td><?=ucfirst($c['director'])?></td>
		<!--
		<td width="90px">
			<?php if ($this->material->number($c['id']) > 0) { ?>
			<?=anchor(site_url('dscribe2/courses/edit/'.$c['id']), 
					  '<img src="'.property('app_img').'/pencil.png" title="Edit course" />',	
					  array('title'=>'Edit course'))?> &nbsp;&nbsp;

	       <?=anchor(site_url('dscribe2/courses/review/'.$c['id']), 
					  '<img src="'.property('app_img').'/zoom.png" title="Review course" />',
					  array('title'=>'Review course'))?>&nbsp;&nbsp;

			<?=anchor(site_url('dscribe2/courses/remove/'.$c['id']), 
					  '<img src="'.property('app_img').'/cross.png" title="Remove course" />',
					  array('title'=>'Remove course', 'class'=>'confirm'))?>
			<?php } else { ?>
					<span>No courses materials</span>
			<?php } ?>
		</td>
		-->
	</tr>	
	<?php }} ?>
	</tbody>
</table>

<?php } } ?>

<?php $this->load->view(property('app_views_path').'/dscribe2/dscribe2_footer.php', $data); ?>
