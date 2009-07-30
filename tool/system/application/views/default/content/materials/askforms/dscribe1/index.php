<?php if ($questions_to != 'instructor') { ?>
<input type="hidden" id="idarray" name="idarray" value='<?=$idarray?>' />  
<?php } ?>

<?php 
$this->load->view(property('app_views_path').'/materials/askforms/askform_header.php', $data); 

if ($questions_to == 'instructor') { 
	  $this->load->view(property('app_views_path').'/materials/askforms/instructor/noneditable_view.php', $data); 
} else { 	
		$this->load->view(property('app_views_path').'/materials/askforms/dscribe2/noneditable_view.php', $data); 
}
?>
