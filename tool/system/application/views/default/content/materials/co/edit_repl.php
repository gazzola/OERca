<?php
$data['ask_status'] = array('new'=>'Instructor has not looked at this object yet.',
									  'in progress'=>'Instructor is working on this',
									 	'done'=>'Instructor is done reviewing this object');
									
$data['copy_status'] = array('unknown'=>'Unknown', 'copyrighted'=>'Copyrighted','public domain'=>'Public Domain');
$copy = $repl_obj['copyright'];
$data['cp_status'] = ($copy==null) ? '' : $copy['status'];
$data['cp_holder'] = ($copy==null) ? '' : $copy['holder'];
$data['cp_notice'] = ($copy==null) ? '' : $copy['notice'];
$data['cp_url'] = ($copy==null) ? '' : $copy['url'];
$tab = $tab[0];
$data['loc_tip'] = "For textual materials like Powerpoints or PDFs, please enter the slide or page number. For videos, please enter a time stamp.";
?>

<div id="Replacement" class="morphtabs_panel">

<?php if ($this->coobject->replacement_exists($cid, $mid, $obj['id'])) { ?>

<form method="post">
	<input type="hidden" id="rid" name="rid" value="<?=$repl_obj['id']?>" />
	<input type="hidden" name="viewing" value="replacement" />

     <div id="accordion_repl">
		<h4 class="toggler">
	    	Status
	 	</h4>
	
	 	<div class="element">
	  	<?php 
	  		$this->load->view(property('app_views_path').'/materials/co/_edit_repl_info.php', $data); 	
			$this->load->view(property('app_views_path').'/materials/co/_edit_repl_status.php', $data);
		?>
		</div>
		
		<h4 class="toggler">
	    	Comments
	 	</h4>
	
	 	<div class="element">
	  	<?php 
			$this->load->view(property('app_views_path').'/materials/co/_edit_repl_comments.php', $data);
		?>
		</div>
		
		<h4 class="toggler">
	    	History
	 	</h4>
	
	 	<div class="element">
	  	<?php 
			$this->load->view(property('app_views_path').'/materials/co/_edit_repl_log.php', $data); 	
		?>
		</div>
	</div>
	
</form>

<?php } ?>

<?php $this->load->view(property('app_views_path').'/materials/co/_edit_repl_upload.php', $data); ?>

</div>
