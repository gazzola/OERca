<?php
	$data['loc_tip'] = "For textual materials like Powerpoints or PDFs, please enter the slide or page number. For videos, please enter a time stamp.";

	$course_list_url = '';
   if (getUserProperty('role') == 'dscribe1') { 
			$course_list_url = site_url('dscribe1/courses');
   } elseif (getUserProperty('role') == 'dscribe2') { 
			$course_list_url = site_url('dscribe2/courses');
   } elseif (getUserProperty('role') == 'instructor') { 
			$course_list_url = site_url('instructor/courses');
	 } elseif (getUserProperty('role') == 'admin') {
			$course_list_url = site_url('admin/courses');
	}
?>

<div class="column span-24 first last">

	<div style="border-bottom: 1px solid #eee; margin-top: -10px; margin-left: 5px; padding-bottom: 5px;">
    <div style="float: left">
		    <a href="<?=$course_list_url?>">Courses</a> &raquo; 
        <?php echo (isset($material['name'])) ? '<a href="'.site_url('materials/home/'.$cid).'">'.$cname.'</a>' : $cname; ?> 
        <?php echo (isset($material['name']))?'&raquo; '.$material['name']:''?>
    </div>

    <?php if (isset($material['name'])) { ?>
    <div id="materials_nav" style="float: right">
		  <ul>
					<li class="normal"><a href="<?=site_url("materials/add_object/$cid/$mid/snapper")?>?TB_iframe=true&height=450&width=450" class="smoothbox" title="Add Content Objects">Add Content Objects</a></li>
					<li class="normal"><a href="<?=site_url("materials/askforms/$cid/$mid")?>" target="_new">ASK Forms</a></li>
			    <li class="normal"><a href="<?=site_url("materials/download_all_rcos/$cid/$mid/")?>">Download all Replacement COs</a></li>
      </ul>
    </div>

    <?php } else { ?>

    <div id="materials_nav" style="float: right">
		  <ul>
			    <li class="normal">
						<a href="<?=site_url("materials/add_material/$cid/single/view")?>?TB_iframe=true&height=450&width=350" class="smoothbox" title="Add Materials">Add Materials</a>
					</li>
      </ul>
    </div>
      
    <?php } ?>
    <div style="clear:both"></div>
	</div>
<br/>
