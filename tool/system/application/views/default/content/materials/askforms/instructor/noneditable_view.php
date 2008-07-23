<?php 
$this->load->view(property('app_views_path').'/materials/askforms/instructor/inst_ne_header.php', $data); 
if ($num_avail[$view] == 0) {
?>

<div class="column span-24 first last"> 
	<p class="error">Presently, none of the content objects in this material fall in this category.</p>
</div>

<?php } else { ?>
	
<div class="column span-24 first last"> 
<table class="rowstyle-alt no-arrow" style="padding: 0">
    <thead>
    	<tr>
	<?php if ($view == 'done') { ?>
       	<th>&nbsp;</th>
        <th>Content Object</th>
				<th>Information</th>

	<?php } elseif ($view == 'replacement') { ?>
       	<th>&nbsp;</th>
				<th>Questions</th>
        <th>Replacement Object</th>
        <th>Original Object</th>

	<?php } else { ?>
        <th>&nbsp;</th>
				<th>Questions</th>
        <th>Content Object Information</th>

	<?php } ?>
    	</tr>
    </thead>

    <tbody>
		<?php 
			if ($view == 'general') {
				$this->load->view(property('app_views_path').'/materials/askforms/instructor/inst_ne_general.php', $data); 
			} elseif ($view == 'provenance') {
				$this->load->view(property('app_views_path').'/materials/askforms/instructor/inst_ne_prov.php', $data); 

			} elseif ($view == 'replacement') {
				$this->load->view(property('app_views_path').'/materials/askforms/instructor/inst_ne_repl.php', $data); 

			} else {
				$this->load->view(property('app_views_path').'/materials/askforms/instructor/inst_ne_done.php', $data); 
		  }
		?>
	</tbody>
</table>
</div>

<?php } ?>
