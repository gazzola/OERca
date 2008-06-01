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
    <iframe id="snapper-frame" name="snapper-frame" src="<?=site_url("materials/snapper/$cid/$mid")?>" scrolling="no" marginwidth="0" marginheight="0" frameborder="0" vspace="0" hspace="0" style="overflow:visible; width:100%; height: 470px;"></iframe>
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
		
			<div class="formLabel">Location:</div>
    	<div class="formField">
    			<input type="text" name="location" id="location" size="50" class="input" />
			</div>
		
 			<div class="formField">Upload Content Object:</div>
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
			  Upload Content Objects ZIP file:
	     	<input type="file" name="userfile" id="userfile" size="30" />
	       <br/><br/>
	     	<input type="submit" name="submit" id="submit" value="Add" />
			</form>
	  </div>

    <br style="clear:both"/><br/><br/>

	  <div  style="margin-top:5px; text-align:right;">
		<input type="button" value="Close" id="do_close_uploadco_pane"/>
	</div>
	</div>
</div>
