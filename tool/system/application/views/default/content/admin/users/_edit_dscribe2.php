<?php $this->load->view(property('app_views_path').'/admin/admin_header.php', $data); 

echo style('mootabs1.2.css',array('media'=>"screen, projection"));
echo script('mootabs1.2.js');
?>

<div id="myTabs" class="column span-24 first last">
	<ul class="mootabs_title">
		<li title="Dscribe" style="padding-left:10px; margin-left:0;"><h2>Manage dScribes</h2></li>
		<li title="Course" style="padding-left:10px; margin-left:0;"><h2>Manage Courses</h2></li>
	</ul>

	<div id="Dscribe" class="mootabs_panel">
			<div class="column span-24 first last" style="margin-bottom: 10px; margin-top: 10px; padding-bottom: 20px; border-bottom:1px solid #aaa;">
				<form method="post" action="<?php echo site_url("admin/users/edit/$defuser/{$user['id']}")?>" enctype="multipart/form-data" style="margin:0px;padding:0">
					<input type="hidden" name="task" value="assigndscribe1" />
				<h2 style="display:inline;">Dscribes: &nbsp;</h2>
				<?php echo $select_dscribes ?>
			   &nbsp;<input id="submitbutton" type="submit" name="submit" value="Assign dscribe1 to dscribe2" />
				</form>
			</div>

			<div class="column span-24 first last">
			<?php if (!is_array($dscribes)) { ?>
			
			<p class="error">We did not find any dscribes assigned to this dscribe2 yet.</p>
			
			<?php } else { ?> 
	
			<table class="sortable-onload-1 rowstyle-alt no-arrow">
    		<thead>
    		<tr>
        	<th class="sortable">Name</th>
        	<th class="sortable">User Name</th>
        	<th class="sortable">Email</th>
        	<th>&nbsp;</th>
				</tr>
				</thead>
				
				<tbody>
				<?php
					foreach($dscribes as $did) { $d = $this->ocw_user->get_user_by_id($did); ?>
					<tr>
						<td>
								<?php echo $d['name']?>
								<br/>
								<span style="font-size:9px; clear:both; margin-top:20px;">
									<a href="<?=site_url("admin/users/editinfo/$tab/{$d['id']}")?>?TB_iframe=true&height=500&width=400" class="smoothbox" title="Editing <?=$d['name']?> Info">Edit Info</a>&nbsp;|&nbsp;
									<a href="<?=site_url("admin/users/editprofile/$tab/{$d['id']}")?>?TB_iframe=true&height=650&width=400" class="smoothbox" title="Editing <?=$d['name']?> Profile">Edit Profile</a>
							</span>
						</td>
  					<td><?php echo $d['user_name']?></td>
  					<td><?php echo safe_mailto($d['email'])?></td>
						<td>
							<form method="post" action="<?php echo site_url("admin/users/edit/$defuser/{$user['id']}")?>" enctype="multipart/form-data" style="margin:0px;padding:0">
								<input type="hidden" name="dsid" value="<?=$d['id']?>" />
								<input type="hidden" name="task" value="unassigndscribe1" />
			  				<input id="submitbutton" type="submit" name="submit" value="Unassign dscribe1" class="confirm"/>
							</form>
						</td>
				</tr>
			<?php }} ?>

				</tbody>
			</table>
			</div>
	</div>

	<div id="Course" class="mootabs_panel">

			<div class="column span-24 first last" style="margin-bottom: 10px; margin-top: 10px; padding-bottom: 20px; border-bottom:1px solid #aaa;">
				<form name="adminform" method="post" action="<?php echo site_url("admin/users/edit/$defuser/{$user['id']}")?>" enctype="multipart/form-data" style="margin:0px;padding:0">
					<input type="hidden" name="task" value="addcourse" />
				<h2 style="display:inline;">Courses: &nbsp;</h2>
				<?php echo $select_courses ?>
			   &nbsp;<input id="submitbutton" type="submit" name="submit" value="Assign dscribe2 to course" />
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
			        <th class="sortable">Instructor(s)</th>
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
						<form method="post" action="<?php echo site_url("admin/users/edit/$defuser/{$user['id']}")?>" enctype="multipart/form-data" style="margin:0px;padding:0">
							<input type="hidden" name="cid" value="<?=$c['id']?>" />
							<input type="hidden" name="task" value="removecourse" />
			  			<input id="submitbutton" type="submit" name="submit" value="Unassign dscribe2 from this course" class="confirm"/>
						</form>
					</td>
				</tr>	
				<?php }} ?>
				</tbody>
			</table>
			
			<?php } } ?>
	</div>

</div>

<?php $this->load->view(property('app_views_path').'/admin/admin_footer.php', $data); ?>
