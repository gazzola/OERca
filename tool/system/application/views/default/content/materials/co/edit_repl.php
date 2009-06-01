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

<div id="Replacement" class="mootabs_panel">

<?php if ($this->coobject->replacement_exists($cid, $mid, $obj['id'])) { ?>

<form method="post">
	<input type="hidden" id="rid" name="rid" value="<?=$repl_obj['id']?>" />
	<input type="hidden" name="viewing" value="replacement" />

  <div class="repdetails">
    <h4 id="rep_status_sect_toggler" class="faceted_search_toggler"  onclick="rep_status_sect.toggle()">Status</h4>
    <div id="rep_status_sect" class="element">
    <?php 
	  	$this->load->view(property('app_views_path').'/materials/co/_edit_repl_info.php', $data); 	
			$this->load->view(property('app_views_path').'/materials/co/_edit_repl_status.php', $data);
		?>
    </div>
    <script>
    var rep_status_sect = new Fx.Slide($('rep_status_sect'), {
    duration: 200,
    onComplete: function(el) {
      var toggler = $(el.id+'_toggler');
      if (toggler.getStyle('background-image') == "url(<?php echo property('app_img'); ?>/expand.gif)") toggler.setStyle('background-image', "url(<?php echo property('app_img'); ?>/collapse.gif)");
      else toggler.setStyle('background-image', "url(<?php echo property('app_img'); ?>/expand.gif)");
      },
        transition: Fx.Transitions.linear
    }).toggle();
    </script>
    
    <h4 id="rep_comment_sect_toggler" class="faceted_search_toggler" onclick="rep_comment_sect.toggle()">Comments</h4>
    <div id="rep_comment_sect" class="element">
    <?php 
	  	$this->load->view(property('app_views_path').'/materials/co/_edit_repl_comments.php', $data);
		?>
    </div>
    <script>
    var rep_comment_sect = new Fx.Slide($('rep_comment_sect'), {
    duration: 200,
    onComplete: function(el) {
      var toggler = $(el.id+'_toggler');
      if (toggler.getStyle('background-image') == "url(<?php echo property('app_img'); ?>/expand.gif)") toggler.setStyle('background-image', "url(<?php echo property('app_img'); ?>/collapse.gif)");
      else toggler.setStyle('background-image', "url(<?php echo property('app_img'); ?>/expand.gif)");
      },
        transition: Fx.Transitions.linear
    }).toggle();
    </script>
    
    <h4 id="rep_history_sect_toggler" class="faceted_search_toggler" onclick="rep_history_sect.toggle()">History</h4>
    <div id="rep_history_sect" class="element">
    <?php 
	  	$this->load->view(property('app_views_path').'/materials/co/_edit_repl_log.php', $data);
		?>
    </div>
    <script>
    var rep_history_sect = new Fx.Slide($('rep_history_sect'), {
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

<?php } ?>

<?php $this->load->view(property('app_views_path').'/materials/co/_edit_repl_upload.php', $data); ?>

</div>
