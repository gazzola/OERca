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


  <div class="tabcontainer">
    <div class="tabs">
      <input type="submit" <?=($tab=='Status') ? 'class="activesubmit"':''?> name="tab[]" value="Status" />
      <input type="submit" <?=($tab=='Information') ? 'class="activesubmit"':''?> name="tab[]" value="Information" />
      <input type="submit" <?=($tab=='Copyright') ? 'class="activesubmit"':''?> name="tab[]" value="Copyright" />
      <input type="submit" <?=($tab=='Comments') ? 'class="activesubmit"':''?> name="tab[]" value="Comments" />
      <input type="submit" <?=($tab=='History') ? 'class="activesubmit"':''?> name="tab[]" value="History" />
    </div>
	
    <div class="tabformcontent">
    <?php
        switch($tab) {
            case 'Status': $this->load->view(property('app_views_path').'/materials/co/_edit_repl_status.php', $data); break;
            case 'Information': $this->load->view(property('app_views_path').'/materials/co/_edit_repl_info.php', $data); break;
            case 'Copyright': $this->load->view(property('app_views_path').'/materials/co/_edit_repl_copy.php', $data); break;
            case 'Comments': $this->load->view(property('app_views_path').'/materials/co/_edit_repl_comments.php', $data); break;
            case 'History': $this->load->view(property('app_views_path').'/materials/co/_edit_repl_log.php', $data); break;
            default: $this->load->view(property('app_views_path').'/materials/co/_edit_repl_status.php', $data); break;
        }
    ?>
    </div>

    <br class="clear" />
  </div>
</form>

<?php } ?>

<?php $this->load->view(property('app_views_path').'/materials/co/_edit_repl_upload.php', $data); ?>

</div>
