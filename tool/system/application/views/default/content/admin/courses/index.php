<?php $this->load->view(property('app_views_path').'/admin/admin_header.php', $data); ?>

<div class="column span-24 first last" style="margin-bottom: 10px;">
<?php if ($courses == null || !isset($courses) ) { ?>

	<p class="error">No courses found:&nbsp;&nbsp;<a href="<?=site_url("courses/add_new_course/")?>?TB_iframe=true&height=400&width=800" class="smoothbox" title="Add a new Course" style="color:blue">Add a Course</a></p>

<?php } else { ?>
	
	<?php	// Simply load the existing courses page
				$this->load->view(property('app_views_path').'/courses/index.php', $data);
	?>

<?php } ?>

</div>

<?php $this->load->view(property('app_views_path').'/admin/admin_footer.php', $data); ?>
