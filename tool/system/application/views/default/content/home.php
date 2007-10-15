<h1>Manage Courses</h1>

<p>Below are a list of courses for which you are either the instructor or dScribe for.</p>

<?php if ($courses == null) { ?>

<p class="error"><?=getUserProperty('name')?>, we did not find any courses for you to process yet.</p> 

<?php } else { ?> 

<table class="sortable-onload-1 rowstyle-alt no-arrow">
	<thead>
	<tr>
		<th class="sortable">Title</th>
		<th class="sortable-sortEnglishLonghandDateFormat">Start Date</th>
		<th class="sortable-sortEnglishLonghandDateFormat">End Date</th>
		<th class="sortable">Class</th>
		<th class="sortable">Sequence</th>
		<th class="sortable">Director</th>
		<th>&nbsp;&nbsp;</th>
	</tr>
	</thead>
	<tbody>
<?php foreach($courses as $course) { ?>
	<tr>
		<td><?=$course['number'].' '.$course['title']?></td>
		<td><?=mdate('%d %M, %Y',mysql_to_unix($course['start_date']))?></td>
		<td><?=mdate('%d %M,%Y',mysql_to_unix($course['end_date']))?></td>
		<td width="40px"><?=ucfirst($course['class'])?></td>
		<td>
			<?=($course['sequence_id']==0) ? '--':$this->course->sequence_name($course['sequence_id'])?>
		</td>
		<td><?=ucfirst($course['director'])?></td>
		<td width="90px">
	<?php if ($course['role']=='instructor') { ?>
			<?=anchor(site_url('instructor/home/'.$course['id']), 
					  '<img src="'.property('app_img').'/pencil.png" title="Edit course" />',	
					  array('title'=>'Edit course'))?> &nbsp;&nbsp;

	       <?=anchor(site_url('instructor/review/'.$course['id']), 
					  '<img src="'.property('app_img').'/zoom.png" title="Review course" />',
					  array('title'=>'Review course'))?>&nbsp;&nbsp;

			<?=anchor(site_url('instructor/remove/'.$course['id']), 
					  '<img src="'.property('app_img').'/cross.png" title="Remove course" />',
					  array('title'=>'Remove course', 'class'=>'confirm'))?>
	<?php } else { ?>
			<?=anchor(site_url('dscribe/home/'.$course['id']), 
					  '<img src="'.property('app_img').'/pencil.png" title="Edit course" />',
					  array('title'=>'Edit course'))?>

	       <?=anchor(site_url('dscribe/review/'.$course['id']), 
					  '<img src="'.property('app_img').'/zoom.png" title="Review course" />',
					  array('title'=>'Review course'))?>&nbsp;&nbsp;
	<?php } ?>
		</td>
	</tr>

<?php }} ?> 
	</tbody>
</table>

