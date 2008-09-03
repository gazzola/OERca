<?php $this->load->view(property('app_views_path').'/admin/admin_header.php', $data); ?>

<div id="add_course" class="column span-24 first last" style="margin-bottom: 10px; padding-bottom: 20px; border-bottom:1px solid #aaa;">
	<form name="adminform" method="post" action="<?php echo site_url("admin/users/edit/$defuser/{$user['id']}")?>" enctype="multipart/form-data" style="margin:0px;padding:0">
		<input type="hidden" name="task" value="addcourse" />
	<h2 style="display:inline;">Courses: &nbsp;</h2>
	<?php echo $select_courses ?>
   &nbsp;<input id="submitbutton" type="submit" name="submit" value="Assign instructor to course" />
	</form>
</div>

<div class="column span-24 first last">

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
        <th class="sortable">Primary Instructor</th>
				<th>&nbsp;</th>
    </tr>
    </thead>
    <tbody>

	<?php foreach($curriculum as $course)	{ ?>
		<?php foreach($course as $c) { ?>
	<tr>
		<td>
			<?=anchor(site_url('materials/home/'.$c['id']),$c['number'].' '.$c['title'],array('title'=>'Edit course materials','target'=>'_blank'))?>
			<br/>
			<span style="font-size:9px; clear:both; margin-top:20px;">
			<?=
				anchor(site_url("courses/edit_course_info/{$c['id']}").'?TB_iframe=true&height=675&width=875','Edit Info &raquo;',array('class'=>'smoothbox','title'=>'Edit Course'))
			?>
			</span>
		</td>
    <td><?=mdate('%d %M, %Y',mysql_to_unix($c['start_date']))?></td>
    <td><?=mdate('%d %M, %Y',mysql_to_unix($c['end_date']))?></td>
    <td width="40px"><?=ucfirst($c['cname'])?></td>
    <td><?=ucfirst($c['director'])?></td>
    <td><?=ucfirst($c['instructors'])?></td>
		<td>
			<form name="adminform2" method="post" action="<?php echo site_url("admin/users/edit/$defuser/{$user['id']}")?>" enctype="multipart/form-data" style="margin:0px;padding:0">
				<input type="hidden" name="cid" value="<?=$c['id']?>" />
				<input type="hidden" name="task" value="removecourse" />
  			<input id="submitbutton" type="submit" name="submit" value="Unassign instructor from this course" class="confirm"/>
			</form>
		</td>
	</tr>	
	<?php }} ?>
	</tbody>
</table>

<?php } } ?>

</div>

<?php $this->load->view(property('app_views_path').'/admin/admin_footer.php', $data); ?>
