<?php $this->load->view(property('app_views_path').'/materials/materials_header.php', $data); ?>

<?php $tags[0] = '-- select --'; ?>

<input type="hidden" id="cid" name="cid" value="<?=$cid?>" />

<?php if ($materials == null) { ?>

<p class="error">No materials found for this course</p>

<?php } else { 

 foreach($materials as $category => $cmaterial) { 
?> 
<h2 style="clear:both"><?=$category?></h2>
<?php $mat_form_attr = array( 'id' => 'mat_form', 'name' => 'mat_form'); ?>

<?php echo form_open("materials/manipulate/$cid", $mat_form_attr) ?>
  <div class="column span-7 firstlast">
    <input type="submit" name="delete" id="delete" value="Delete" class="confirm">
    <input type="submit" name="download" id="download" value="Download"> <br />
    <span>
      Select:
      &nbsp;
      <a href="javascript:void(0);" onClick="SetAllCheckBoxes('mat_form', 'select_material[]', true);" >All,</a>
      &nbsp;
      <a href="javascript:void(0);" onClick="SetAllCheckBoxes('mat_form', 'select_material[]', false);" >None</a>
    </span>
  </div>

  <table class="sortable-onload-7 rowstyle-alt no-arrow" style="clear: both; margin-bottom: 0px;">
  	<thead>
  	<tr>
  	  <th><strong>Select</strong></th>
  		<th class="sortable"><strong>Name</strong></th>
  		<th class="sortable"><strong>File Type</strong></th>
  		<th class="sortable"><strong>Material Type</strong></th>
  		<th class="sortable-sortEnglishDateTime"><strong>Date Modified</strong></th>
  		<th class="sortable"><strong>Author</strong></th>
  		<th class="sortable-sortImage, sortable-sortAlphaNumeric"><strong>CO Status</strong></th>
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
						<a href="<?=site_url("materials/editinfo/$cid/{$material['id']}")?>?TB_iframe=true&height=500&width=350" class="smoothbox" title="Editing <?=$material['name']?> Info">Edit</a>&nbsp;|&nbsp;
						<a href="<?=site_url("materials/editcomments/$cid/{$material['id']}")?>?TB_iframe=true&height=500&width=350" class="smoothbox" title="Comments for <?=$material['name']?>">Comments (<?=$numcomments?>)</a>&nbsp;|&nbsp;
						<a href="<?=site_url("materials/askforms/$cid/{$material['id']}")?>" title="View Material ASK forms" style="<?=$askcolor?>" target="_blank">ASK Forms</a>
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
  			<?php if ($material['validated']) { ?>
  				<img src="<?=property('app_img')?>/validated.gif" title="ready" />
  			<?php } else { ?>
  				<img src="<?=property('app_img')?>/required.gif" title="not ready" />
  			<?php } ?>
  		<?php echo ($material['embedded_co']==0) ? '(no CO)' : "&nbsp;({$material['statcount']})"; ?>
  		</td>
		
  		<?php } ?>
  	</tr>
  	<?php 
  		if (@is_array($material['childitems'])) { 
  			$childitems = $material['childitems'];
  			$depth = 1;
  			include property('app_views_abspath').'/materials/_childitems.php';
  	    } 
  	 ?>
  <?php }?>
  	</tbody>
  </table>
  <div class="column span-7 firstlast" style="margin-bottom:30px;">
    <span>
      Select:
      &nbsp;
      <a href="javascript:void(0);" onClick="SetAllCheckBoxes('mat_form', 'select_material[]', true);" >All,</a>
      &nbsp;
      <a href="javascript:void(0);" onClick="SetAllCheckBoxes('mat_form', 'select_material[]', false);" >None</a>
    </span> <br />
    <input type="submit" name="delete" id="delete" value="Delete" class="confirm">
    <input type="submit" name="download" id="download" value="Download">
  </div>
</form>
<?php }}  ?>

<script type="text/javascript">
window.addEvent('domready', function() {
    var myTips = new MooTips($$('.tooltip'), { maxTitleChars: 50 });
});
</script>
<?php $this->load->view(property('app_views_path').'/materials/materials_footer.php', $data); ?>
