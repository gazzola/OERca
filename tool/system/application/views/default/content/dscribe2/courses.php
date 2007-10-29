<?php 
	echo script('datepicker.js');
	echo style('datepicker.css');

	$this->load->view(property('app_views_path').'/dscribe2/dscribe2_header.php', $data); 
	$curriculum['new'] = 'Add new curriculum:';
	$curriculum['none'] = 'None';
	$curriculum[0] = '--- Select Curriculum ---';
	$sequences['new'] = 'Add new sequence:';
	$sequences['none'] = 'None';
	$sequences[0] = '--- Select Sequence ---';
?>

<div id="tool_content">

<a href="javascript:void(0);" class="do_show_addpanel"><img src="<?= property('app_img').'/add.png'?>" valign="bottom"/>&nbsp; Add a Course</a>

<div id="ippanel_error" 
		style="color:red; display: none; width: 90%; border: 1px solid #ccc; background-color: #eee; margin-bottom: 10px;">
</div>


<?php $this->load->view(property('app_views_path').'/dscribe2/_add_course.php', $data); ?> 
<br><br>

<h1>Courses</h1>

<?php if ($courses == null) { ?>

<p class="error"><?=getUserProperty('name')?>, we did not find any courses for you to process yet.</p> 

<?php } else { ?> 

<p><em>Note: Hold down the shift key to select multiple columns to sort</em></p>
<table class="sortable-onload-1 rowstyle-alt no-arrow">
	<thead>
	<tr>
		<th class="sortable">Title</th>
		<th class="sortable-sortEnglishLonghandDateFormat">Start Date</th>
		<th class="sortable-sortEnglishLonghandDateFormat">End Date</th>
		<th class="sortable">Class</th>
		<th class="sortable">Sequence</th>
		<th class="sortable">Director</th>
		<th>Edit&nbsp;</th>
	</tr>
	</thead>
	<tbody>
<?php foreach($courses as $course) { ?>
	<tr>
		<td><?=$course['number'].' '.$course['title']?></td>
		<td><?=mdate('%d %M, %Y',mysql_to_unix($course['start_date']))?></td>
		<td><?=mdate('%d %M, %Y',mysql_to_unix($course['end_date']))?></td>
		<td width="40px"><?=ucfirst($course['class'])?></td>
		<td>
			<?=($course['sequence_id']==0) ? '--':$this->course->sequence_name($course['sequence_id'])?>
		</td>
		<td><?=ucfirst($course['director'])?></td>
		<td width="90px">
			<?=anchor(site_url('dscribe2/courses/edit/'.$course['id']), 
					  '<img src="'.property('app_img').'/pencil.png" title="Edit course" />',	
					  array('title'=>'Edit course'))?> &nbsp;&nbsp;

	       <?=anchor(site_url('dscribe2/courses/review/'.$course['id']), 
					  '<img src="'.property('app_img').'/zoom.png" title="Review course" />',
					  array('title'=>'Review course'))?>&nbsp;&nbsp;

			<?=anchor(site_url('dscribe2/courses/remove/'.$course['id']), 
					  '<img src="'.property('app_img').'/cross.png" title="Remove course" />',
					  array('title'=>'Remove course', 'class'=>'confirm'))?>
		</td>
	</tr>

<?php }} ?> 
	</tbody>
</table>


</div>
