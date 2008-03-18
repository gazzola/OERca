<script type="text/javascript">
	<?php if ($openpane == 'uploadmat') { ?>open_uploadmat_pane = <?php echo ($openpane == 'uploadmat') ? 'true;' : 'false;'; } ?>
	<?php if ($openpane == 'editcourse') { ?>open_editcourse_pane = <?php echo ($openpane == 'editcourse') ? 'true;' : 'false;'; } ?>
	<?php if ($openpane == 'uploadco') { ?>open_uploadco_pane = <?php echo ($openpane == 'uploadco') ? 'true;' : 'false;'; } ?>
</script>

<?php
	$course_list_url = '';
   if (getUserProperty('role') == 'dscribe1') { 
			$course_list_url = site_url('manage');
   } elseif (getUserProperty('role') == 'dscribe2') { 
			$course_list_url = site_url('dscribe2/courses');
   } elseif (getUserProperty('role') == 'instructor') { 
			$course_list_url = site_url('manage');
	 }
?>

<div class="column span-24 first last">

	<div style="border-bottom: 1px solid #eee; margin-top: -10px; margin-left: 5px; padding-bottom: 5px;">
    <div style="float: left">
		    <a href="<?=$course_list_url?>">Courses</a> &raquo; 
        <?php echo (isset($material['name'])) ? '<a href="'.site_url('materials/home/'.$cid.'/'.$caller).'">'.$cname.'</a>' : $cname; ?> 
        <?php echo (isset($material['name']))?'&raquo; '.$material['name']:''?>
    </div>

    <?php if (isset($material['name'])) { ?>
    <div id="materials_nav" style="float: right">
		  <ul>
			  	<li class="normal"><a id="do_open_uploadco_pane">Add Content Objects</a></li>
			    <li class="normal"><a id="do_open_matinfo_pane">Material Info</a></li>
			    <li class="normal"><a id="do_open_matcomm_pane">Material Comments</a></li>
					<?php if ($objstats['ask']>0) { ?>
					<li class="normal"><a href="<?=site_url("materials/viewform/ask/$cid/$mid")?>">ASK form</a></li>
					<?php		} ?>
			    <li class="normal"><a href="<?=$material['ctools_url']?>">Download</a></li>
      </ul>
    </div>

    <?php } else { ?>

    <div id="materials_nav" style="float: right">
		  <ul>
			    <li class="normal"><a id="do_open_courseinfo_pane">Edit Course Info</a></li>
			    <li class="normal"><a id="do_open_uploadmat_pane">Add Materials</a></li>
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
      } else {
          $this->load->view(property('app_views_path').'/materials/_edit_course_info.php', $data); 
          $this->load->view(property('app_views_path').'/materials/_add_materials.php', $data); 
      } 
   ?>
<br/>
