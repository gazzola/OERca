<?php $this->load->view(property('app_views_path').'/materials/materials_header.php', $data); ?>

<?php $tags[0] = '-- select --'; ?>

<input type="hidden" id="cid" name="cid" value="<?=$cid?>" />

<?php if ($materials == null) { ?>

<p class="error">No materials found for this course</p>

<?php } else { 

 foreach($materials as $category => $cmaterial) { 
?> 
<h2><?=$category?></h2>
<?php $mat_form_attr = array(
  'id' => 'mat_form',
  'name' => 'mat_form'
  ); ?>
<?php echo form_open("materials/manipulate/$cid", $mat_form_attr) ?>
  <div class="column span-7 firstlast">
    <div class="column span-3 first">
      <input type="button" id="selectall" value="Select All" onClick="SetAllCheckBoxes('mat_form', 'select_material[]', true);" > <br />
      <input type="reset" id="clearselected" value="Unselect All" onClick="SetAllCheckBoxes('mat_form', 'select_material[]', false);" >
    </div>
    
    <div class="column span-3 last">
      <input type="submit" name="delete" id="delete" value="Delete Items" class="confirm"> <br />
      <!-- ><input type="submit" name="download" id="download" value="Download Items"> -->
    </div>
  </div>
  <br /><br /><br /><br /><!-- put in to fix odd layout behavior in safari and ff2 -->

  <table class="sortable-onload-1 rowstyle-alt no-arrow">
  	<thead>
  	<tr>
  	  <th><strong>Select</strong></th>
  		<th class="sortable"><strong>Name</strong></th>
  		<th class="sortable"><strong>File Type</strong></th>
  		<th class="sortable"><strong>Resource Type</strong></th>
  		<th class="sortable"><strong>Date Modified</strong></th>
  		<th class="sortable"><strong>Author</strong></th>
  		<th class="sortable"><strong>CO Status</strong></th>
  <!--		<th class="sortable"><strong>Ask Items?</strong></th> -->
  	</tr>
  	</thead>

  	<tbody>
  <?php foreach($cmaterial as $material) { 
  	$objstats =  $this->coobject->object_stats($material['id']);

  ?>
  	<tr>
  	  <td>
  	      <input type="checkbox" name="select_material[]" id="<?=$material['id'] ?>" value="<?=$material['id'] ?>" >
  				<!-- <a href="<?=site_url("materials/remove_material/$cid/{$material['id']}")?>" title="Remove material" class="confirm">Remove</a> -->
  		</td>
		
  		<td>
  			<a href="<?php echo site_url()."materials/edit/$cid/".$material['id'].'/'.$caller?>"><?= $material['name']?>&nbsp;&nbsp;</a>
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
  		  <?= $material['modified_on'] ?>
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
		
  		<!-- <td>
  			<b>
  			 <?php 
  				if ($objstats['ask'] > 0) { echo '<small>Yes&nbsp;(<a href="'.site_url("materials/askforms/$cid/".$material['id']).'">view ASK form</a>)</small>'; } else { echo 'no ask items'; }?> 
  			</b>
  		</td> -->
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
  <div class="column span-7 firstlast">
    <div class="column span-3 first">
      <input type="button" id="selectall" value="Select All" onClick="SetAllCheckBoxes('mat_form', 'select_material[]', true);" > <br />
      <input type="reset" id="clearselected" value="Unselect All" onClick="SetAllCheckBoxes('mat_form', 'select_material[]', false);" >
    </div>
    
    <div class="column span-3 last">
      <input type="submit" name="delete" id="delete" value="Delete Items"> <br />
      <!-- ><input type="submit" name="download" id="download" value="Download Items"> -->
    </div>
  </div>
</form>
<?php }}  ?>
<?php $this->load->view(property('app_views_path').'/materials/materials_footer.php', $data); ?>
