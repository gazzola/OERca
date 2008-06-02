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
?>

<div id="Original" class="mootabs_panel">

<form method="post">
	<input type="hidden" name="viewing" value="original" />
	<div class="tabcontainer">
		<div class="tabs">
			<input type="submit" <?=($tab=='Status' || $tab=='Upload') ? 'class="activesubmit"':''?> name="tab[]" value="Status" />
			<input type="submit" <?=($tab=='Information') ? 'class="activesubmit"':''?> name="tab[]" value="Information" />
			<input type="submit" <?=($tab=='Copyright') ? 'class="activesubmit"':''?> name="tab[]" value="Copyright" />
			<input type="submit" <?=($tab=='Comments') ? 'class="activesubmit"':''?> name="tab[]" value="Comments" />
			<input type="submit" <?=($tab=='Log') ? 'class="activesubmit"':''?> name="tab[]" value="Log" />
		</div>

		<div class="tabformcontent">
  	<?php 
				switch($tab) {
						case 'Status': $this->load->view(property('app_views_path').'/materials/co/_edit_orig_status.php', $data); break; 
						case 'Information': $this->load->view(property('app_views_path').'/materials/co/_edit_orig_info.php', $data); break; 
						case 'Copyright': $this->load->view(property('app_views_path').'/materials/co/_edit_orig_copy.php', $data); break; 
						case 'Comments': $this->load->view(property('app_views_path').'/materials/co/_edit_orig_comments.php', $data); break; 
						case 'Log': $this->load->view(property('app_views_path').'/materials/co/_edit_orig_log.php', $data); break; 
						default: $this->load->view(property('app_views_path').'/materials/co/_edit_orig_status.php', $data); break; 
				}
		?>
		</div>
	
		<br class="clear" />
	</div>
</form>

</div>
