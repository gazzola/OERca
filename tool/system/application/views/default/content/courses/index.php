<?php 
	$this->load->view(property('app_views_path').'/courses/_faceted_search.php', $data); 	
?>

<div class="column span-18 first last">

<?php if (!isset($courses) || $courses == null) { ?>

<p class="error">We did not find any courses for you to process yet.</p>

<?php 
	} else { 
		foreach($courses as $school => $curriculum) {
?>


<h2><?= $school ?></h2>
<!--
<p><em>Note: Hold down the shift key to select multiple columns to sort</em></p>
-->
<table class="sortable-onload-1 rowstyle-alt no-arrow" style="width: 100%;">
      <caption class="caption_progbar_key">
          <img src="<?= site_url("/home/make_stat_key/rem") ?>" class="prog-key"> No Action Assigned
          &nbsp;
          <img src="<?= site_url("/home/make_stat_key/ask") ?>" class="prog-key"> In Progress
          &nbsp;
          <img src="<?= site_url("/home/make_stat_key/done") ?>" class="prog-key"> Cleared
      </caption>
    <thead>
    <tr>
        <th class="sortable">Title</th>
        <th class="sortable">Curriculum</th>
        <th class="sortable">dScribe(s)</th>
        <th class="sortable">Instructor(s)</th>
	<!-- bdr OERDEV140 - add Content Object status to CourseListing   -->
        <th>    Content Object Status    &nbsp;</th>
        <?php if ((getUserProperty('role') == 'admin')) { ?>
          <th>Delete</th>
        <?php }?>
    </tr>
    </thead>
    <tbody>

	<?php foreach($curriculum as $course)	{ ?>
		<?php foreach($course as $c) { ?>
	<tr>
		<td>
			<?=anchor(site_url('materials/home/'.$c['id']),$c['number'].' '.$c['title'],array('title'=>'Edit course materials'))?>
			<br/>
			<?=$c['term']." ".$c['year']; ?>
			<br />
			<span style="font-size:9px; clear:both; margin-top:20px;">
			<?=
				anchor(site_url("courses/edit_course_info/{$c['id']}").'?TB_iframe=true&height=600&width=850','Edit Info &raquo;',array('class'=>'smoothbox','title'=>'Edit course information'))
			?>
			<?php if ((getUserProperty('role') == 'admin')) { ?>
			 <?=anchor(site_url("admin/courses/manage_users/{$c['id']}").'?TB_iframe=true&height=200&width=600','Manage Users', array('class'=>'smoothbox','title'=>'Manage course users')) ?>
			<?php } ?>
			</span>
		</td>
		<!-- comment out start and end date cols and move under title
    <td><?=mdate('%d %M, %Y',mysql_to_unix($c['start_date']))?></td>
    <td><?=mdate('%d %M, %Y',mysql_to_unix($c['end_date']))?></td>
    -->
    <td width="40px"><?=ucfirst($c['curriculum_name'])?></td>
    <td>
		  <b>dScribe1(s):</b><br>
		  <?php
		  if (count($c['dscribe1s']) > 0) { 
		    foreach($c['dscribe1s'] as $d) { echo ucfirst($d['name']); echo"<br />"; } 
	    } else {
	      echo "None assigned.<br />";
	    }
		  ?>
		  <b>dScribe2(s):</b><br>
		  <?php
		  if (count($c['dscribe2s']) > 0) { 
		    foreach($c['dscribe2s'] as $d) { echo ucfirst($d['name']); echo"<br />"; } 
	    } else {
	      echo "None assigned.<br />";
	    }
		  ?>
		</td>
    <td>
      <?php foreach($c['instructors'] as $i) { echo ucfirst($i['name']); echo "<br />"; } ?>
    </td>
    
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
              
	   </a>
	   <? }?>
   </td>
    <?php if ((getUserProperty('role') == 'admin')) { ?>
				<td>
					<?php echo anchor(site_url("admin/courses/remove_course/".$c['id']),	
						'<img src="'.property('app_img').'/cross.png" title="Remove course and all its materials" />',
							array('customprompt'=>"You are about to COMPLETELY and PERMANENTLY delete course ". $c['number']. " " . $c['title'] . " and ALL its related materials.  ARE YOU ABSOLUTELY SURE YOU WANT TO DO THAT???", 'title'=>"Remove", 'doublecheck' => "yes", 'class'=>'confirm')) ?>
				</td>
		<?php } ?>
	</tr>	
	<?php }} ?>
	</tbody>
</table>

<?php } } ?>

</div>
