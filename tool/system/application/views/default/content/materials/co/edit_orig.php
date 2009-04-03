<?php	
$data['action_types']= array_merge(array(''=>'Choose one...'), $action_types);

$data['ask_status'] = array('new'=>'Instructor has not looked at this object yet.',
									  'in progress'=>'Instructor is working on this',
									 	'done'=>'Instructor is done reviewing this object');
									
$data['copy_status'] = array('unknown'=>'Unknown', 'copyrighted'=>'Copyrighted','public domain'=>'Public Domain');

$types = '<select id="subtype_id" name="subtype_id" class="do_object_update">';
foreach($subtypes as $type => $subtype) {
		$types .= '<optgroup label="'.$type.'">';
		foreach($subtype as $st) {
			$sel = ($obj['subtype_id']== $st['id']) ? 'selected' : '';
			$types .= '<option value="'.$st['id'].'" '.$sel.'>'.$st['name'].'</option>';
    }
		$types .= '</optgroup>';
} 
$types .= '</select>';
$data['types'] = $types;

$copy = $obj['copyright'];
$data['cp_status'] = ($copy==null) ? '' : $copy['status'];
$data['cp_holder'] = ($copy==null) ? '' : $copy['holder'];
$data['cp_notice'] = ($copy==null) ? '' : $copy['notice'];
$data['cp_url'] = ($copy==null) ? '' : $copy['url'];

$questions = $obj['questions'];
$data['questions'] = $questions;
$data['instructor_questions']= ($questions<>null && isset($questions['instructor'])) ? $questions['instructor'] : null;
$data['dscribe2_questions']= ($questions<>null && isset($questions['dscribe2'])) ? $questions['dscribe2'] : null;

$data['comments'] = $obj['comments'];
$data['log'] = $obj['log'];
$tab = $tab[0];
$data['loc_tip'] = "For textual materials like Powerpoints or PDFs, please enter the slide or page number. For videos, please enter a time stamp.";
?>

<div id="Original" class="morphtabs_panel" style="display: block;">

<form method="post">
     <div id="accordion_orig">
		<h4 class="toggler">
	    	Status <img src="<?php echo property('app_img'); ?>/add.png" title="Open This" style="margin: 0;" />
	 	</h4>
	
	 	<div class="element">
	  	<?php 
	  		$this->load->view(property('app_views_path').'/materials/co/_edit_orig_info.php', $data); 	
			$this->load->view(property('app_views_path').'/materials/co/_edit_orig_status.php', $data);
		?>
		</div>
		
		<h4 class="toggler">
	    	Comments <img src="<?php echo property('app_img'); ?>/add.png" title="Open This" style="margin: 0;" />
	 	</h4>
	
	 	<div class="element">
	  	<?php 
			$this->load->view(property('app_views_path').'/materials/co/_edit_orig_comments.php', $data);
		?>
		</div>
		
		<h4 class="toggler">
	    	History <img src="<?php echo property('app_img'); ?>/add.png" title="Open This" style="margin: 0;" />
	 	</h4>
	
	 	<div class="element">
	  	<?php 
			$this->load->view(property('app_views_path').'/materials/co/_edit_orig_log.php', $data); 	
		?>
		</div>
	</div>
</form>

</div>
