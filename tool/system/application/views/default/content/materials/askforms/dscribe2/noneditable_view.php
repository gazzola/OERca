<?php 
		$this->load->view(property('app_views_path').'/materials/askforms/dscribe2/d2_header.php', $data); 
		
		if ($num_avail[$view] !=0) {
 
		if ($view=='aitems' && empty($cos[$response_type])  && $response_type <> 'all') { 
?>

	<div class="column span-24 first last"> 
			<p class="error">Presently, none of the content objects in this material fall in this category.</p>
	</div>

<?php } else { ?>

<div class="column span-24 first last"> 
<table class="rowstyle-alt no-arrow" style="padding: 0">
    <thead>
    <tr>
       	<th>&nbsp;</th>
				<?php if ($view == 'aitems') { ?><th class="sortable">Response Type</th><?php }  ?>
				<th>Questions</th>
				<?php if ($view == 'replacement') { ?>
				<th>Original Object</th>
				<th>Replacement Object</th>
				<?php } else { ?>
        <th>Content Object Information</th>
				<?php } ?>
    </tr>
    </thead>

    <tbody>
		<?php 
			if ($view == 'general') {
				$this->load->view(property('app_views_path').'/materials/askforms/dscribe2/ne_general.php', $data); 

			} elseif ($view == 'replacement') {
				$this->load->view(property('app_views_path').'/materials/askforms/dscribe2/ne_replacement.php', $data); 

			} elseif ($view == 'fairuse') {
				$this->load->view(property('app_views_path').'/materials/askforms/dscribe2/ne_fairuse.php', $data); 

			} elseif ($view == 'permission') {
				$this->load->view(property('app_views_path').'/materials/askforms/dscribe2/ne_permission.php', $data); 

			} elseif ($view == 'commission') {
				$this->load->view(property('app_views_path').'/materials/askforms/dscribe2/ne_commission.php', $data); 

			} elseif ($view == 'retain') {
				$this->load->view(property('app_views_path').'/materials/askforms/dscribe2/ne_retain.php', $data); 

			} else {
				$this->load->view(property('app_views_path').'/materials/askforms/dscribe2/ne_done.php', $data); 
	    }
		?>
	</tbody>
</table>
</div>

<?php }} ?>
