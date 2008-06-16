<?php
$types = '<select id="subtype_id" name="subtype_id" class="do_object_update">';
foreach($subtypes as $type => $subtype) {
		$types .= '<optgroup label="'.$type.'">';
		foreach($subtype as $st) {
			$types .= '<option value="'.$st['id'].'">'.$st['name'].'</option>';
    }
		$types .= '</optgroup>';
} 
$types .= '</select>';
?>

<div id="pane_uploadco" class="editpane">

  <div class="column span-10 first colborder">
     <h2>Snapper Upload</h2>
			
				<?php print form_open_multipart("materials/snapper/$cid/$mid/submit",array('id'=>'snapper-form')) ?>	
				<!--<div id="controls">
					<div>
						<input id="snap_aCapture" type="checkbox" />
						<label for="snap_aCapture">auto capture</label>
					</div>
				</div> -->
				
				<div id="capture">
					<applet id="clipboard" width="200" height="200" archive="<?=site_url()?>snapper/Ssnapper.jar,<?=site_url()?>snapper/commons-codec-1.3.jar" code="org.muse.snapper.Snapper" codebase="<?=site_url()?>snapper/">
					</applet>
					<br />
					<input id="snap" type="button" value="Capture" />
					<div id="snap_status" style="margin-top: 5px;"></div>
					<input id="snap_image" type="hidden" name="image"/>
					<input id="snap_st" type="hidden" name="subtype_id" value="6"/>
				</div>
				
				<div id="meta" style="margin-top: 10px;">
					<h2>Meta Data</h2>
					<label>What are you capturing?</label>
				
					&nbsp;&nbsp;
			
					<input type="hidden" id="snap_type" value="object" />	
					<input class="snapper_captype" name="type" id="snap_aCaptureTypeObject" type="radio" value="object" checked="checked"/>
					<label for="aCaptureType">Original Object</label>
				
					&nbsp;&nbsp;
				
					<input class="snapper_captype" name="type" id="snap_aCaptureTypeSlide" type="radio" value="slide" />
					<label for="aCaptureType">Context Image</label>
				
					<div id="contentloc">
						<br/>
						<label for="snap_location" class="ine_tip" title="<?=$loc_tip?>">Location in material:</label>
						<input id="snap_location" name="location" type="text" width="30" />
						&nbsp;<img src="<?=property('app_img')?>/info.gif" style="margin:0; padding:0" class="ine_tip" title="<?=$loc_tip?>" />
					</div>
				</div>
				
				<div id="action" style="margin-top:20px;">
					<input id="snap_save" type="button" value="Save" />
				</div>
				<?php print form_close(); ?>
  </div>

  <div class="column span-8">
		<h2>Single File Upload</h2>
		
		<form action="<?=site_url("materials/add_object/$cid/$mid")?>" enctype="multipart/form-data" id="add_co_single" method="post">
			<input type="hidden" name="citation" value="none" />
			<input type="hidden" name="contributor" value="" />
			<input type="hidden" name="question" value="" />
			<input type="hidden" name="comment" value="" />
			<input type="hidden" name="copyurl" value="" />
			<input type="hidden" name="copynotice" value="" />
			<input type="hidden" name="copyholder" value="" />
			<input type="hidden" name="copystatus" value="" />
			
			<div class="formLabel">Ask Instructor:</div>
			<div class="formField">
				<input type="radio" name="ask" value="yes"/>&nbsp;Yes&nbsp;
		  	<input type="radio" name="ask" value="no" checked="checked"/>&nbsp;No
			</div>
			
			<div class="formLabel">Content Type:</div>
    	<div class="formField"><?=$types?></div>
		
			<div class="formField" class="ine_tip" title="<?=$loc_tip?>">Location in material (required):</div>
    	<div class="formField">
    			<input type="text" name="location" id="location" size="50" class="input" />
&nbsp;<img src="<?=property('app_img')?>/info.gif" style="margin:0; padding:0" class="ine_tip" title="<?=$loc_tip?>" />
			</div>
		
 			<div class="formField">Upload Content Object: (required)</div>
			<div class="formField">
	     	<input type="file" name="userfile_0" id="userfile_0" size="30" />
			</div>
			
			<div class="formField">
						<br/>
						<input type="submit" value="Add" />
			</div>
		</form>

    <br style="clear:both"/><br/><br/>
	
		<h2>Bulk Upload</h2>
	
		<div class="formField">
			<form action="<?=site_url("materials/add_object_zip/$cid/$mid")?>" enctype="multipart/form-data" id="add_co_zip" method="post">
			  Upload Content Objects ZIP file: (required)
	     	<input type="file" name="userfile" id="userfile" size="30" />
	       <br/><br/>
	     	<input type="submit" name="submit" id="submit" value="Add" />
			</form>
	  </div>

    <br style="clear:both"/><br/>

	  <div  style="margin-top:5px; margin-right: 10px; text-align:right;">
		<input type="button" value="Close" id="do_close_uploadco_pane"/>
	</div>
	</div>
</div>
