<?php 
		$this->load->view(property('app_views_path').'/materials/materials_header.php', $data); 
		$fevt = 'onchange="parent.window.location.replace($(\'server\').value+\'materials/edit/'.$cid.'/'.$mid.'/0/\'+this.value);"';
		$numcols = (in_array($view,array('replace','ask:rco'))) ? 2 : 5;
		$inclrep = (in_array($view,array('replace','ask:rco'))) ? true : false;
?>

<input type="hidden" id="cid" name="cid" value="<?=$cid?>" />
<input type="hidden" id="mid" name="mid" value="<?=$mid?>" />
<input type="hidden" id="defcopy" name="defcopy" value="<?=$director?>" />
<input type="hidden" id="view" name="view" value="<?=$view?>" />
<input type="hidden" id="subtab" name="subtab" value="<?=$subtab?>" />

<?php if ($num_all > 0) { ?>

<div id="edit_mat_cos" class="column span-24 first last" style="margin-bottom: 10px; padding-bottom: 20px; border-bottom:1px solid #aaa;">
	<h2 style="display:inline;">View Content Objects (<?=$num_all?>):&nbsp;</h2>
	<?php echo form_dropdown('selectfilter', $select_filter, $view, 'id="selectfilter" '.$fevt) ?>
</div>

<?php if ($num_objects == 0) { ?>

		<div class="column span-24 first last"> 
      <p class="error">Presently, none of the content objects in this material fall in this category.</p>
  	</div>

<?php } else { ?>

		<div class="dwrap span-24 first last"> 
				<?= $this->ocw_utils->create_co_list($cid,$mid,$objects,$view,$inclrep,$numcols); ?>
		</div>

<?php }} else { ?>

  <div class="column span-24 first last">
		<p class="error">No content objects recorded for this material.
					<a href="<?=site_url("materials/add_object/$cid/$mid/snapper")?>?TB_iframe=true&height=450&width=500" class="smoothbox" style="color:blue" title="Add Content Objects">Use Snapper tool to capture Content Objects</a>
		</p>
	</div>

<?php } $this->load->view(property('app_views_path').'/materials/materials_footer.php', $data); ?>
