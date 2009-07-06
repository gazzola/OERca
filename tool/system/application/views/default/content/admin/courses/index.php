<?php $this->load->view(property('app_views_path').'/admin/admin_header.php', $data); ?>

<div class="column span-24 first last" style="margin-bottom: 10px;">
	
	<?php	// Simply load the existing courses page
				$this->load->view(property('app_views_path').'/courses/index.php', $data);
	?>

</div>

<?php $this->load->view(property('app_views_path').'/admin/admin_footer.php', $data); ?>
