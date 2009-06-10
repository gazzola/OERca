<?php 
		$this->load->view(property('app_views_path').'/materials/materials_header.php', $data); 
		$fevt = 'onchange="parent.window.location.replace($(\'server\').value+\'materials/edit/'.$cid.'/'.$mid.'/0/\'+this.value);"';
		$numcols = (in_array($view,array('replace','ask:rco'))) ? 2 : 3;
		$inclrep = (in_array($view,array('replace','ask:rco'))) ? true : false;

	$this->load->view(property('app_views_path').'/materials/_faceted_search_co.php', $data); 	

?>

<input type="hidden" id="cid" name="cid" value="<?=$cid?>" />
<input type="hidden" id="mid" name="mid" value="<?=$mid?>" />
<input type="hidden" id="defcopy" name="defcopy" value="<?=$director?>" />
<input type="hidden" id="view" name="view" value="<?=$view?>" />

<?php if ($num_all > 0) { ?>

<?php if ($num_objects == 0) { ?>

		<div class="column span-16 first last"> 
      <p class="error">Presently, none of the content objects in this material fall in this category.</p>
  	</div>

<?php } else { ?>
<!--
			<div class="div_progbar_key">
          		<img src="<?= site_url("/home/make_stat_key/rem") ?>" class="prog-key"> No Action Assigned
          		 &nbsp;
          		<img src="<?= site_url("/home/make_stat_key/ask") ?>" class="prog-key">  In Progress
          		&nbsp;
          		<img src="<?= site_url("/home/make_stat_key/done") ?>" class="prog-key"> Cleared
      		</div>
		<br /><br />-->
		<div class="column span-18 first last dwrap">
			<?= $this->ocw_utils->create_co_list($cid,$mid,$objects,$view,$inclrep,$numcols); ?>
		</div>
		
<?php }} else { ?>

  <div class="column span-18 first last">
		<p class="error">No content objects recorded for this material.
					<a href="<?=site_url("materials/add_object/$cid/$mid/snapper")?>?TB_iframe=true&height=500&width=450" class="smoothbox" style="color:blue" title="Add Content Objects">Use Snapper tool to capture Content Objects</a>
		</p>
	</div>

<?php } $this->load->view(property('app_views_path').'/materials/materials_footer.php', $data); ?>
