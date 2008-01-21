<?php $this->load->view(property('app_views_path').'/instructor/instructor_header.php', $data); ?>

<iframe id="materialsframe" name="materialsframe" src="<?=site_url('materials/index/'.$cid)?>" scrolling="no" marginwidth="0" marginheight="0" frameborder="0" vspace="0" hspace="0" style="overflow:visible; width:100%; display:none"></iframe>

<?php $this->load->view(property('app_views_path').'/instructor/instructor_footer.php', $data); ?>
