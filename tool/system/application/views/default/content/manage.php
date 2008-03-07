<h1>Manage Courses</h1><br/>

<div class="column span-24 first last">
<?php if ($courses == null) { ?>

<p class="error">We did not find any courses for you.</p>

<?php 
	} else { 

		foreach($courses as $school => $curriculum) {
?>

<h2><?= $school ?></h2>
<table class="sortable-onload-1 rowstyle-alt no-arrow">
    <thead>
    <tr>
        <th class="sortable">Title</th>
        <th class="sortable-sortEnglishLonghandDateFormat">Start Date</th>
        <th class="sortable-sortEnglishLonghandDateFormat">End Date</th>
        <th class="sortable">Curriculum</th>
        <th class="sortable">Director</th>
    </tr>
    </thead>
    <tbody>

	<?php foreach($curriculum as $course)	{ ?>
		<?php foreach($course as $c) { ?>
	<tr>
		<td>
		<?php if ($sysrole <> 'user') { ?>
			<?=anchor(site_url('/materials/home/'.$c['id']), $c['number'].' '.$c['title'], array('title'=>'Edit course materials'))?> 
		<?php } else {?>
			<?= $c['number'].' '.$c['title']?> 
		<?php } ?>
		</td>
        <td><?=mdate('%d %M, %Y',mysql_to_unix($c['start_date']))?></td>
        <td><?=mdate('%d %M, %Y',mysql_to_unix($c['end_date']))?></td>
        <td width="40px"><?=ucfirst($c['cname'])?></td>
        <td><?=ucfirst($c['director'])?></td>
		<!--
		<td width="90px"> 
			<?=anchor(site_url('/materials/home/'.$c['id']),
                      'Edit', array('title'=>'Edit course materials'))?> 
		</td>
		-->
	</tr>	
	<?php }} ?>
	</tbody>
</table>

<?php } } ?>
</div>
