<?php	$this->load->view(property('app_views_path').'/admin/admin_header.php', $data); ?>

<div class="column span-24 first last" style="margin-bottom: 10px;">

<h2>Curriculum for <?=$sname?></h2>
<?php if ( $currlist == null || !isset($currlist) ) { ?>

		<p class="error">
			No curriculum found:&nbsp;&nbsp;<a href="<?=site_url("admin/curriculum/add_curriculum/$sid")?>
				?TB_iframe=true&height=500&width=600" class="smoothbox" title="Add new Curriculum for <?php echo $sname ?>"
				style="color:blue">Add a Curriculum</a>
		</p>

<?php } else { ?>

	<table class="sortable-onload-1 rowstyle-alt no-arrow">
		<thead>
		<tr>
			<th class="sortable">Name</th>
			<th class="sortable">Description</th>
			<th>Delete</th>
		</tr>
		</thead>
		<?php foreach($currlist as $curr) { ?>
						<tr>
							<td><?=$curr->name?>
								<br>
								<span style="font-size:9px; clear:both; margin-top:20px;">
									<a href="<?=site_url("admin/curriculum/edit_curriculum/$sid/$curr->id")?>
										?TB_iframe=true&height=500&width=600" class="smoothbox" title="Edit curriculum Info">Edit Info</a>&nbsp;&nbsp;
								</span>
							</td>
							<td><?=$curr->description?></td>
							<td>
								<?php echo anchor(site_url("admin/curriculum/remove_curriculum/$sid/$curr->id"),	
								'<img src="'.property('app_img').'/cross.png" title="Remove " />',
									array('customprompt'=>"You are about to delete curriculum $curr->name ($curr->description).  ARE YOU SURE???", 'title'=>"Remove", 'class'=>'confirm'))?>
							</td>	
						</tr>
		<?php } ?>
		<tbody>
	</table>

<?php } ?>

<h2>Subjects for <?=$sname?></h2>
<?php if ( $subjlist == null || !isset($subjlist) ) { ?>

		<p class="error">
			No subjects found:&nbsp;&nbsp;<a href="<?=site_url("admin/subjects/add_subject/$sid")?>
				?TB_iframe=true&height=500&width=600" class="smoothbox" title="Add new Subject for <?php echo $sname ?>"
				style="color:blue">Add a Subject</a>
		</p>

<?php } else { ?>

	<table class="sortable-onload-1 rowstyle-alt no-arrow">
		<thead>
		<tr>
			<th class="sortable">Code</th>
			<th class="sortable">Description</th>
			<th>Delete</th>
		</tr>
		</thead>
		<?php foreach($subjlist as $subj) { ?>
						<tr>
							<td><?=$subj->subj_code?>
								<br>
								<span style="font-size:9px; clear:both; margin-top:20px;">
									<a href="<?=site_url("admin/subjects/edit_subject/$sid/$subj->id")?>
										?TB_iframe=true&height=500&width=600" class="smoothbox"
										title="Edit subject Info">Edit Info</a>&nbsp;&nbsp;
								</span>
							</td>
							<td><?=$subj->subj_desc?></td>
							<td>
								<?php echo anchor(site_url("admin/subjects/remove_subject/$sid/$subj->id"),	
									'<img src="'.property('app_img').'/cross.png" title="Remove " />',
										array('customprompt'=>"You are about to delete subject $subj->subj_code ($subj->subj_desc).  ARE YOU SURE???", 'title'=>"Remove", 'class'=>'confirm'))?>
							</td>	
						</tr>
		<?php } ?>
		<tbody>
	</table>

<?php } ?>
<?php $this->load->view(property('app_views_path').'/admin/admin_footer.php', $data); ?>
