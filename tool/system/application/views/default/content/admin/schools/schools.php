<?php $this->load->view(property('app_views_path').'/admin/admin_header.php', $data); ?>

<div class="column span-24 first last" style="margin-bottom: 10px;">
<?php if ($schools == null || !isset($schools) ) { ?>

		 <p class="error">No schools found:&nbsp;&nbsp;<a href="<?=site_url("admin/schools/add_school/")?>?TB_iframe=true&height=500&width=600" class="smoothbox" title="Add a new School" style="color:blue">Add a School</a></p>

<?php } else { ?>

	<table class="sortable-onload-1 rowstyle-alt no-arrow">
		<thead>
		<tr>
			<th class="sortable">Name</th>
			<th class="sortable">Description</th>
			<th></th>
		</tr>
		</thead>
		<?php foreach($schools as $s) { ?>
		<tr>
			<td>
				<a href="<?=site_url("admin/curriculum/view/$s->id")?>" title="Manage <?=$s->name?>'s Subjects and Curriculum"><?=$s->name?></a>
				<br>
				<span style="font-size:9px; clear:both; margin-top:20px;">
						<a href="<?=site_url("admin/schools/edit_school/$s->id")?>?TB_iframe=true&height=500&width=600" class="smoothbox" title="Edit <?=$s->name?>'s Info">Edit Info</a>&nbsp;&nbsp;
				</span>
			</td>
			<td><?=ucfirst($s->description)?></td>
			<td>
				<?php echo anchor(site_url("admin/schools/remove_school/".$s->id),	
					'<img src="'.property('app_img').'/cross.png" title="Remove " />',
					array('customprompt'=> "You are about to delete school $s->name. ARE YOU SURE???", 'title'=>"Remove", 'class'=>'confirm'))?>
			</td>	
		</tr>
		<?php } ?>
		<tbody>
	</table>

<?php } ?>

</div>

<?php $this->load->view(property('app_views_path').'/admin/admin_footer.php', $data); ?>
