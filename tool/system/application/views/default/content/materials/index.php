<?php $this->load->view(property('app_views_path').'/materials/materials_header.php', $data); ?>

<?php $tags[0] = '-- select --'; ?>

<input type="hidden" id="cid" name="cid" value="<?=$cid?>" />

<?php if ($materials == null) { 
	$printkey = FALSE;	
?>

<p class="error">No materials found for this course</p>

<?php } else { 
    $printkey = TRUE;

 foreach($materials as $category => $cmaterial) { 
?>
 
<h2 style="clear:both"><?=$category?></h2>
<?php $mat_form_attr = array( 'id' => 'mat_form', 'name' => 'mat_form'); ?>

<?php echo form_open("materials/manipulate/$cid", $mat_form_attr) ?>
  <div class="column span-8 firstlast">
    <input type="submit" name="delete" id="delete" value="Delete" class="confirm"
					customprompt="You are about to permanently delete ALL the selected materials. ARE YOU SURE?">
    <input type="submit" name="download" id="download" value="Download">
          &nbsp;*includes Content Objects<br />
    <span>
      Select:
      &nbsp;
      <a href="javascript:void(0);" onClick="SetAllCheckBoxes('mat_form', 'select_material[]', true);" >All,</a>
      &nbsp;
      <a href="javascript:void(0);" onClick="SetAllCheckBoxes('mat_form', 'select_material[]', false);" >None</a>
    </span>
  </div>

  <table class="sortable-onload-7 rowstyle-alt no-arrow" style="clear: both; margin-bottom: 0px;">
      <caption class="caption_progbar_key">
          <img src="<?= site_url("/home/make_stat_key/rem") ?>" class="prog-key"> No Action Assigned
          &nbsp;
          <img src="<?= site_url("/home/make_stat_key/ask") ?>" class="prog-key"> In Progress
          &nbsp;
          <img src="<?= site_url("/home/make_stat_key/done") ?>" class="prog-key"> Cleared
      </caption>
  	<thead>
  	<tr>
  	  <th><strong>Select</strong></th>
  		<th class="sortable"><strong>Name</strong></th>
  		<th class="sortable"><strong>File Type</strong></th>
  		<th class="sortable"><strong>Material Type</strong></th>
  		<th class="sortable-sortEnglishDateTime"><strong>Date Modified</strong></th>
  		<th class="sortable"><strong>Author</strong></th>
  		<th class=""><strong>Content Object Status</strong></th>
  	</tr>
  	</thead>

  	<tbody>
  <?php foreach($cmaterial as $material) { 
		$numcomments = count($material['comments']);
		$askcolor = ($this->material->status($material['id'],getUserProperty('id'),'askform')) 
						  ? 'color:green;font-weight:bold' : '';
  ?>
  	<tr>
  	  <td>
  	      <input type="checkbox" name="select_material[]" id="<?=$material['id'] ?>" value="<?=$material['id'] ?>" >
  		</td>
		
  		<td>
  			<span style="font-size: 13px;"><a href="<?php echo site_url()."materials/edit/$cid/".$material['id']?>"><?= $material['name']?></a></span>
				<br/>
				<span style="font-size:9px; clear:both; margin-top:20px;">
						<a href="<?=site_url("materials/editinfo/$cid/{$material['id']}")?>?TB_iframe=true&height=500&width=350" class="smoothbox" title="Editing <?=$material['name']?> Info">Edit</a>&nbsp|
						<a href="<?=site_url("materials/editcomments/$cid/{$material['id']}")?>?TB_iframe=true&height=500&width=350" class="smoothbox" title="Comments for <?=$material['name']?>">Comments (<?=$numcomments?>)</a>&nbsp|
						<a href="<?=site_url("materials/askforms/$cid/{$material['id']}")?>" title="View Material ASK forms" style="<?=$askcolor?>" target="_blank">ASK Forms</a>&nbsp|
						<?php if ($material['files'][0]['fileurl']) { ?>
						  <a href="<?=$material['files'][0]['fileurl']?>">Download</a>
					  <?php } else { ?>
					    No Material <?php } ?>
				</span>
  		</td>

  		<?php if ($material['mimetype'] == 'folder') { ?>
  		<td colspan="6">&nbsp;&nbsp;</td>
  		<?php } else { ?>
    
      <td>
        <?= $material['mimename'] ?>
      </td>
    
      <td>
        <?= $material['tagname'] ?>
      </td>
		
  		<td>
  		  <?= $material['display_date'] ?><br/>
      </td>
    
      <td>
      	<?= $material['author'] ?>
      </td>
    
  		<td class="options">
    		<? $params_url = $material['mtotal'].'/'.$material['mdone'].'/'.$material['mask'].'/'.$material['mrem'].'/'.$material['mdash'];
		if ($material['mdash'] == 1) { ?>
			<span style="font-size: 13px;"><center>--</center></span>
		<?}
      		//elseif ($material['mtotal'] > 0) { //OERDEV-181 mbleed: removed this if statement, 0 CO case will produce progbars 
      		else {
		?>
                   <a href="<?php echo site_url()."materials/edit/$cid/".$material['id']?>">
		   <img src="<?= site_url("/home/material_bar/$params_url") ?>" 
              	   alt="Progress Bar: 
              		Total Objects=<?=$material['mtotal'] ?>
              		Cleared Objects=<?=$material['mdone'] ?> 
              		Objects in progress=<?=$material['mask'] ?> 
              		Remaining Objects=<?=$material['mrem'] ?>"
              	   class="prog-bar">
        	   <? }?>
		   </a>
  		</td>
		
  		<?php } ?>
  	</tr>
  <?php } }?>
  	</tbody>
  </table>
  <div class="column span-8 firstlast" style="margin-bottom:30px;">
    <span>
      Select:
      &nbsp;
      <a href="javascript:void(0);" onClick="SetAllCheckBoxes('mat_form', 'select_material[]', true);" >All,</a>
      &nbsp;
      <a href="javascript:void(0);" onClick="SetAllCheckBoxes('mat_form', 'select_material[]', false);" >None</a>
    </span> <br />
    <input type="submit" name="delete" id="delete" value="Delete" class="confirm"
				customprompt="You are about to permanently delete ALL the selected materials. ARE YOU SURE?">
    <input type="submit" name="download" id="download" value="Download">
        &nbsp;*includes Content Objects<br />
  </div>
</form>
<?php }  ?>

<script type="text/javascript">
window.addEvent('domready', function() {
    var myTips = new MooTips($$('.tooltip'), { maxTitleChars: 50 });
});
</script>
<?php $this->load->view(property('app_views_path').'/materials/materials_footer.php', $data); ?>
