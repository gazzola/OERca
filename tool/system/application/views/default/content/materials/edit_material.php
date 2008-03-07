<?php $this->load->view(property('app_views_path').'/materials/materials_header.php', $data); ?>

<?php	
	echo script('iframeadjust.js'); 

  $tags[0] = '-- select --';
  $comments = $material['comments'];
  $copyholder = ($material['author']=='') ? $course['director'] : $material['author'];

  $action_types = array('Fair Use'=>'Fair Use', 
					    'Search'=>'Search',
						  'Commission'=>'Commission',
						  'Permission'=>'Permission',
						  'Retain'=>'Retain',
					    'Remove'=>'Remove');
	
  $types = '<select id="subtype_id" name="subtype_id">';
  foreach($subtypes as $type => $subtype) {
		$types .= '<optgroup label="'.$type.'">';
		foreach($subtype as $st) {
			$types .= '<option value="'.$st['id'].'">'.$st['name'].'</option>';
		}
		$types .= '</optgroup>';
  } 
  $types .= '</select>';

  $filter_types = array('Any'=>'Show All',
            'Ask'=>'Ask Instructor',
            'Done'=>'Cleared',
            'Fair Use'=>'Fair Use',
            'Search'=>'Search',
            'Commission'=>'Commission',
            'Permission'=>'Permission',
            'Retain'=>'Retain',
            'Remove'=>'Remove');
?>

<input type="hidden" id="cid" name="cid" value="<?=$cid?>" />
<input type="hidden" id="mid" name="mid" value="<?=$material['id']?>" />
<input type="hidden" id="defcopy" name="defcopy" value="<?=$course['director']?>" />
<input type="hidden" id="caller" name="caller" value="<?=$caller?>" />

<?php if ($numobjects > 0) { ?>

<div class="column span-5 first">
    <h2>Viewing <span id="upd">XX</span></h2>
        <?php echo form_dropdown('filter-type', $filter_types, $filter,'id="filter-type" style="width: 176px"'); ?>
    <br/><br/>

    <div id="imagebar" class="column span-4 first" style="text-align: center;">
      <div style="display: block; " class="carousel-component">
        <div id="ulu" class="carousel-clip-region">
          <ul  style="position: relative; top: 7px;" class="carousel-list carousel-vertical">
            <?php echo $list; ?>
          </ul>
        </div>
      </div>
    </div>

    <div id="imageknob" class="column" style="width: 35px; padding-left: 5px;">
      <img id="up-arrow" src="<?=property('app_img')?>/up-disabled.gif" alt="Previous Button"/>
      <div id="area"><div id="knob"></div></div>
      <img id="down-arrow" src="<?=property('app_img')?>/down-enabled.gif" alt="Next Button"/>
    </div>
</div>

<div class="column span-18 last">
  <iframe id="edit-co-frame" name="edit-co-frame" src="<?=site_url("materials/object_info/$cid/{$material['id']}/{$coobjects[0]['name']}")?>" scrolling="no" marginwidth="0" marginheight="0" frameborder="0" vspace="0" hspace="0" style="overflow:visible; width:100%; "></iframe>
</div>
<?php } elseif ($numobjects == 0 && $filter<>'') { ?>
 
  <div class="column span-24 first last">
		<p>
			<h2 style="display:inline">Status: </h2>
	    <?php echo form_dropdown('filter-type', $filter_types, $filter,'id="filter-type" style="width: 176px"'); ?>
	    <br/><br/>
		</p>
		<p class="error">No content objects have been marked as '<?=$filter?>'.</p>
	</div>


<?php } else { ?>
  <div class="column span-24 first last">
		<p class="error">No content objects recorded for this material.</p>
	</div>
<?php } ?>

<?php $this->load->view(property('app_views_path').'/materials/materials_footer.php', $data); ?>
