<?php 
	  echo style('mootabs1.2.css',array('media'=>"screen, projection"));
	  echo script('mootabs1.2.js');

		$this->load->view(property('app_views_path').'/materials/materials_header.php', $data); 

    $att=' id="active"';
    $all = 'All ('.$num_all.')';
    $new = 'New ('.$num_new.')';
    $search = 'Search ('.$num_search.')';
    $ask = 'Ask Instructor ('.($num_ask_orig + $num_ask_repl).')';
    $fairuse = 'Fair Use ('.$num_fairuse.')';
    $permission = 'Permission ('.$num_permission.')';
    $commission = 'Commission ('.$num_commission.')';
    $retain = 'Retain ('.($num_retain_perm+$num_retain_pd+$num_retain_nc).')';
    $replace = 'Replace ('.$num_replace.')';
    $recreate = 'Re-Create ('.$num_recreate.')';
    $remove = 'Remove ('.$num_remove.')';
    $cleared = 'Cleared ('.$num_cleared.')';
		$data['oid'] = $oid;
?>

<input type="hidden" id="cid" name="cid" value="<?=$cid?>" />
<input type="hidden" id="mid" name="mid" value="<?=$mid?>" />
<input type="hidden" id="defcopy" name="defcopy" value="<?=$director?>" />
<input type="hidden" id="view" name="view" value="<?=$view?>" />
<input type="hidden" id="subtab" name="subtab" value="<?=$subtab?>" />

<?php if ($num_all > 0) { ?>

<div id="edit_mat_cos" class="column span-24 first last">
	<h2>Edit Content Objects (<?=$num_all?>)</h2>
</div>

<div class="column span-24 first last" style="margin-bottom: 10px;">
   <div id="navlist">
      <ul id="navlist">
      <li<?=($view=='all')?$att:''?>><?=anchor("/materials/edit/$cid/$mid/0/all/",$all)?></li>
      <li<?=($view=='new')?$att:''?>><?=anchor("/materials/edit/$cid/$mid/0/new/",$new)?></li>
      <li<?=($view=='search')?$att:''?>><?=anchor("/materials/edit/$cid/$mid/0/search/",$search)?></li>
      <li<?=($view=='ask')?$att:''?>><?=anchor("/materials/edit/$cid/$mid/0/ask/",$ask)?></li>
      <li<?=($view=='fairuse')?$att:''?>><?=anchor("/materials/edit/$cid/$mid/0/fairuse/",$fairuse)?></li>
      <li<?=($view=='permission')?$att:''?>><?=anchor("/materials/edit/$cid/$mid/0/permission/",$permission)?></li>
      <li<?=($view=='commission')?$att:''?>><?=anchor("/materials/edit/$cid/$mid/0/commission/",$commission)?></li>
      <li<?=($view=='retain')?$att:''?>><?=anchor("/materials/edit/$cid/$mid/0/retain/",$retain)?></li>
      <li<?=($view=='replace')?$att:''?>><?=anchor("/materials/edit/$cid/$mid/0/replace/",$replace)?></li>
      <li<?=($view=='recreate')?$att:''?>><?=anchor("/materials/edit/$cid/$mid/0/recreate/",$recreate)?></li>
      <li<?=($view=='remove')?$att:''?>><?=anchor("/materials/edit/$cid/$mid/0/remove/",$remove)?></li>
      <li<?=($view=='cleared')?$att:''?>><?=anchor("/materials/edit/$cid/$mid/0/cleared/",$cleared)?></li>
    </ul>
   </div>
</div>

<br/><br/>

	<?php if ($num_objects == 0) { ?>

		<div class="column span-24 first last"> 
      <p class="error">Presently, none of the content objects in this material fall in this category.</p>
  	</div>

	<? } else { ?>
			<?php if ($view=='ask') { ?>
		
		<div id="myTabs" class="column span-24 first last">
  		<ul id="ColistTabs" class="mootabs_title">
    			<li title="ColistOriginal" style="margin-left:0;"><h2>Original (<?=$num_ask_orig?>)</h2></li>
    			<li title="ColistReplacement" style="margin-left:13px;"><h2>Replacement (<?=$num_ask_repl?>)</h2></li>
  		</ul>

  		<div id="ColistOriginal" class="mootabs_panel">
				<div  class="dwrap column span-23 first last"> 
				<?php if ($num_ask_orig > 0) { ?>
						<?= $this->ocw_utils->create_co_list($cid,$mid,$objects['orig']);?>
				<?php } else { ?>
      				<p class="error">Presently, none of the content objects in this material fall in this category.</p>
				<?php } ?>
				</div>
			</div>

  		<div id="ColistReplacement" class="mootabs_panel">
					<div class="dwrap column span-23 first last"> 
				<?php if ($num_ask_repl > 0) { ?>
						<?= $this->ocw_utils->create_co_list($cid,$mid,$objects['repl']);?>
				<?php } else { ?>
      				<p class="error">Presently, none of the content objects in this material fall in this category.</p>
				<?php } ?>
				 </div>
		 </div>
	</div>
	
			<?php } elseif($view=='retain') { ?>

		<div id="myTabs" class="column span-24 first last">
  		<ul id="ColistTabs" class="mootabs_title">
    			<li title="ColistRetainPM" style="margin-left:0;"><h2>Permission (<?=$num_retain_perm?>)</h2></li>
    			<li title="ColistRetainPD" style="margin-left:13px;"><h2>Public Domain (<?=$num_retain_pd?>)</h2></li>
    			<li title="ColistRetainNC" style="margin-left:13px;"><h2>No Copyright (<?=$num_retain_nc?>)</h2></li>
  		</ul>

  		<div id="ColistRetainPM" class="mootabs_panel">
				<div  class="dwrap column span-23 first last"> 
				<?php if ($num_retain_perm > 0) { ?>
						<?= $this->ocw_utils->create_co_list($cid,$mid,$objects['perm']);?>
				<?php } else { ?>
      				<p class="error">Presently, none of the content objects in this material fall in this category.</p>
				<?php } ?>
				</div>
			</div>

  		<div id="ColistRetainPD" class="mootabs_panel">
					<div class="dwrap column span-23 first last"> 
				<?php if ($num_retain_pd > 0) { ?>
						<?= $this->ocw_utils->create_co_list($cid,$mid,$objects['pd']);?>
				<?php } else { ?>
      				<p class="error">Presently, none of the content objects in this material fall in this category.</p>
				<?php } ?>
				 </div>
		 </div>

  		<div id="ColistRetainNC" class="mootabs_panel">
					<div class="dwrap column span-23 first last"> 
				<?php if ($num_retain_nc > 0) { ?>
						<?= $this->ocw_utils->create_co_list($cid,$mid,$objects['nc']);?>
				<?php } else { ?>
      				<p class="error">Presently, none of the content objects in this material fall in this category.</p>
				<?php } ?>
				 </div>
		 </div>
	</div>


			<?php } else { ?> 

				<div class="dwrap span-24 first last"> 
						<?= $this->ocw_utils->create_co_list($cid,$mid,$objects);?>
				</div>

<?php }}} else { ?>
  <div class="column span-24 first last">
		<p class="error">No content objects recorded for this material.
					<a href="<?=site_url("materials/add_object/$cid/$mid/snapper")?>?TB_iframe=true&height=500&width=450" class="smoothbox" style="color:blue" title="Add Content Objects">Use Snapper tool to capture Content Objects</a>
		</p>
	</div>
<?php } ?>

<?php $this->load->view(property('app_views_path').'/materials/materials_footer.php', $data); ?>
