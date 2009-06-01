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
	  <h4 id="status_sect_toggler" class="faceted_search_toggler" onclick="status_sect.toggle()"> Status </h4>
	  <div id="status_sect" class="element">
	  <?php
		  $this->load->view(property('app_views_path').'/materials/co/_edit_orig_info.php', $data); 	
	    $this->load->view(property('app_views_path').'/materials/co/_edit_orig_status.php', $data);
	  ?>
	  </div>
    <script>
    var status_sect = new Fx.Slide($('status_sect'), {
    duration: 200,
    onComplete: function(el) {
      var toggler = $(el.id+'_toggler');
      if (toggler.getStyle('background-image') == "url(<?php echo property('app_img'); ?>/expand.gif)") toggler.setStyle('background-image', "url(<?php echo property('app_img'); ?>/collapse.gif)");
      else toggler.setStyle('background-image', "url(<?php echo property('app_img'); ?>/expand.gif)");
      },
        transition: Fx.Transitions.linear
    }).toggle();
    </script>
    
	  <h4 id="comment_sect_toggler" class="faceted_search_toggler" onclick="comment_sect.toggle()"> Comments </h4>
	  <div id="comment_sect" class="element">
	  <?php
	    $this->load->view(property('app_views_path').'/materials/co/_edit_orig_comments.php', $data);
	  ?>
	  </div>
	  <script>
    var comment_sect = new Fx.Slide($('comment_sect'), {
    duration: 200,
    onComplete: function(el) {
      var toggler = $(el.id+'_toggler');
      if (toggler.getStyle('background-image') == "url(<?php echo property('app_img'); ?>/expand.gif)") toggler.setStyle('background-image', "url(<?php echo property('app_img'); ?>/collapse.gif)");
      else toggler.setStyle('background-image', "url(<?php echo property('app_img'); ?>/expand.gif)");
      },
        transition: Fx.Transitions.linear
    }).toggle();
    </script>
	  <h4 id="history_sect_toggler" class="faceted_search_toggler" onclick="history_sect.toggle()"> History </h4>
	  <div id="history_sect" class="element">
	  <?php
	    $this->load->view(property('app_views_path').'/materials/co/_edit_orig_log.php', $data);
	  ?>
	  </div>
	  <script>
    var history_sect = new Fx.Slide($('history_sect'), {
    duration: 200,
    onComplete: function(el) {
      var toggler = $(el.id+'_toggler');
      if (toggler.getStyle('background-image') == "url(<?php echo property('app_img'); ?>/expand.gif)") toggler.setStyle('background-image', "url(<?php echo property('app_img'); ?>/collapse.gif)");
      else toggler.setStyle('background-image', "url(<?php echo property('app_img'); ?>/expand.gif)");
      },
        transition: Fx.Transitions.linear
    }).toggle();
    </script>
	</div>
</form>

</div>