<div class="column span-24 first last">

<?php if (!isset($courses) || $courses == null) { ?>

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
	<!-- bdr OERDEV140 - add CO status to CourseListing   -->
        <?php if ((getUserProperty('role') != 'dscribe1')) { ?>
        <th>    CO Status    &nbsp;</th>
        <?php }?>

		<!--
		<th>Edit&nbsp;</th>
		-->
    </tr>
    </thead>
    <tbody>

	<?php foreach($curriculum as $course)	{ ?>
		<?php foreach($course as $c) { ?>
	<tr>
		<td>
			<?=anchor(site_url('materials/home/'.$c['id']),$c['number'].' '.$c['title'],array('title'=>'Edit course materials'))?>
			<br/>
			<span style="font-size:9px; clear:both; margin-top:20px;">
			<?=
				anchor(site_url("courses/edit_course_info/{$c['id']}").'?TB_iframe=true&height=600&width=850','Edit Info &raquo;',array('class'=>'smoothbox','title'=>'Edit Course'))
			?>
			</span>
		</td>
    <td><?=mdate('%d %M, %Y',mysql_to_unix($c['start_date']))?></td>
    <td><?=mdate('%d %M, %Y',mysql_to_unix($c['end_date']))?></td>
    <td width="40px"><?=ucfirst($c['cname'])?></td>
    <td><?=ucfirst($c['director'])?></td>
    
    <?php if ((getUserProperty('role') !== 'dscribe1')) { ?>
    <td>
    <? $params_url = $c['total'].'/'.$c['done'].'/'.$c['ask'].'/'.$c['rem'];
      if ($c['total'] > 0) { ?>
	  <a href="<?php echo site_url().'materials/home/'.$c['id']?>">
          <img src="<?= site_url("/home/course_bar/$params_url") ?>" 
              alt="Progress Bar: 
              Total Objects=<?=$c['total'] ?>
              Cleared Objects=<?=$c['done'] ?> 
              Objects in progress=<?=$c['ask'] ?> 
              Remaining Objects=<?=$c['rem'] ?>"
              class="prog-bar">
	<? }?>
	   </a>
   </td>
    <?php }?>
	</tr>	
	<?php }} ?>
	</tbody>
</table>

<?php } } ?>

</div>
