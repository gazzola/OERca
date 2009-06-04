<?php	
/*
 * Trim out the actions that we don't want to be used any longer.
 * These actions cannot be removed from the database because there
 * are existing objects that may already have these actions chosen.
 */
function make_some_actions_unselectable($var) {
	switch ($var) {
	case "Permission":
	case "Fair Use":
	case "Commission":
		return FALSE;
	default:
		return TRUE;
	}
}
$action_types = array_filter($action_types, "make_some_actions_unselectable");

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

<div id="Original" class="mootabs_panel">

<form method="post">
	<input type="hidden" name="viewing" value="original" />
	<div class="origdetails">
		<dl class="accordion">
			<dt class="accordion_toggler_1" id="def_open_accordion">Status</dt>
			<dd class="accordion_content_1">
	  		<?php
		  		$this->load->view(property('app_views_path').'/materials/co/_edit_orig_info.php', $data); 	
	    		$this->load->view(property('app_views_path').'/materials/co/_edit_orig_status.php', $data);
	  			?>
			</dd>
    	<dt class="accordion_toggler_1">Comments</dt>
			<dd class="accordion_content_1">
	  		<?php
	    		$this->load->view(property('app_views_path').'/materials/co/_edit_orig_comments.php', $data);
	  		?>
	  	</dd>
			<dt class="accordion_toggler_1">History</dt>
			<dd class="accordion_content_1">
	  		<?php
	    		$this->load->view(property('app_views_path').'/materials/co/_edit_orig_log.php', $data);
	  		?>
	  	</dd>
		</dl>		
	</div>
</form>

</div>