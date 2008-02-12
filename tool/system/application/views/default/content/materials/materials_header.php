<div class="column span-24 first last">

    <div id="navlist">
		<ul id="navlist">
<?php if ($caller=='instructor') { ?> 
			<li><?=anchor("/instructor/home/$cid",$this->lang->line('ocw_ins_menu_home'))?></li>
			<li id="active"><a href=""><?=$this->lang->line('ocw_ins_menu_managematerials')?></a></li>
			<li><?=anchor("/instructor/review/$cid",$this->lang->line('ocw_ins_menu_review'))?></li>
			<li><?=anchor("/instructor/materials/$cid",$this->lang->line('ocw_ins_menu_materials'))?></li>
			<li><?=anchor("/instructor/dscribes/$cid",$this->lang->line('ocw_ins_menu_manage'))?></li>
<?php } elseif ($caller=='dscribe1') { ?>
			<li><?=anchor("/dscribe1/home/$cid",$this->lang->line('ocw_ds_menu_home'))?></li>
			<li id="active"><a href="">Manage</a></li>
			<li><?=anchor("/dscribe1/profiles/$cid",$this->lang->line('ocw_ds_menu_profiles'))?></li>
			<li><?=anchor("/dscribe1/copyright/$cid",$this->lang->line('ocw_ds_menu_copyright'))?></li>
			<li><?=anchor("/dscribe1/tags/$cid",$this->lang->line('ocw_ds_menu_tags'))?></li>
			<li><?=anchor("/dscribe1/review/$cid",$this->lang->line('ocw_ds_menu_review'))?></li>
<?php } ?>
		</ul>
	</div>
	<br/>

	<div style="border-bottom: 1px solid #eee; margin-top: -10px; margin-left: 5px; padding-bottom: 5px;">
    <div style="float: left">
		    <a href="">Courses</a> &raquo; <a href="<?=site_url('materials/home/'.$cid.'/'.$caller)?>"><?=$cname?></a> &raquo;  
        <?php echo (isset($material['name'])) ? $material['name'] : 'materials'; ?>
    </div>
    <?php if (isset($material['name'])) { ?>
    <div id="materials_nav" style="float: right">
		  <ul>
			    <li class="normal"><a id="do_open_matinfo_pane">Edit Material Info</a></li>
			    <li class="normal"><a id="do_open_matcomm_pane">View Material Comments</a></li>
			    <li class="normal"><a id="do_open_uploadco_pane">Upload Content Object</a></li>
			    <li class="normal"><a href="<?=$material['ctools_url']?>">Download Material</a></li>
      </ul>
    </div>
    <?php } ?>
    <div style="clear:both"></div>
	</div>

   <?php 
      if (isset($material['name'])) { 
          $this->load->view(property('app_views_path').'/materials/_edit_material_info.php', $data); 
          $this->load->view(property('app_views_path').'/materials/_edit_material_comments.php', $data); 
          $this->load->view(property('app_views_path').'/materials/_add_content_objects.php', $data); 
        } 
   ?>
<br/>
