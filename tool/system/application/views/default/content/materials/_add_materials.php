<?php		
  $tags[0] = '-- select --';
?>

<div id="pane_uploadmat" class="editpane">
	<div class="column span-8 first colborder">
		<h2>Single File Upload</h2>
		<form action="<?=site_url("materials/add_material/$cid/single")?>" enctype="multipart/form-data" method="post"  id="add_new_material_single">
			<input type="hidden" name="category" value="Materials" />
			<input type="hidden" name="in_ocw" value="1" />
			<input type="hidden" name="nodetype" value="parent" />

					<div class="formLabel">Author: (required)</div>
					<div class="formField">
						<input type="text" name="author" id="author" class="input" size="50px"  />
					</div>

					<div class="formLabel">Collaborators:</div>
					<div class="formField">
						<input type="text" name="collaborators" id="collaborators" class="input" size="50px"  />
					</div>

					<div class="formLabel">CTools URL:</div>
					<div class="formField">
						<input type="text" name="ctools_url" id="ctools_url" class="input" size="50px"  />
					</div>

						<div class="formLabel">Material Type: (required)</div>
						<div class="formField">
							<?php echo form_dropdown('tag_id', $tags, '','id="tag_id"'); ?>
						</div>

						<div class="formLabel">File Type:</div>
						<div class="formField">
								<?php echo form_dropdown('mimetype_id', $mimetypes, ''); ?>
						</div>


						<div class="formLabel">Embedded COs?:</div>
						<div class="formField">
				        <input type="radio" name="embedded_co" id="emip_yes" class="update_material" value="1" checked="checked" />&nbsp;Yes
				        <input type="radio" name="embedded_co" id="emip_no" class="update_material" value="0" />&nbsp;No
						</div>

					<div class="formLabel">Material: (required)</div>
					<div class="formField">
				      	<input type="file" name="single_userfile" id="single_userfile" size="30" />
				  </div>

	  			<div class="formField">
							<br/>
							<input type="submit" value="Add" />
					</div>		
	</form>
	</div>
	
<div class="column span-8 last">
	<h2>Bulk Upload</h2>
	<form action="<?=site_url("materials/add_material/$cid/bulk")?>" enctype="multipart/form-data" method="post" id="add_new_material_bulk">
		<input type="hidden" name="category" value="Materials" />
		<input type="hidden" name="in_ocw" value="1" />
		<input type="hidden" name="nodetype" value="parent" />
		<input type="hidden" name="ctools_url" value="" />
		<input type="hidden" name="mimetype_id" value="6" />
		<input type="hidden" name="tag_id" value="15" />
		
		
		<div class="formLabel">Author: (required)</div>
		<div class="formField">
			<input type="text" name="author" id="author" class="input" size="50px"  />
		</div>

		<div class="formLabel">Collaborators:</div>
		<div class="formField">
			<input type="text" name="collaborators" id="collaborators" class="input" size="50px"  />
		</div>
				
		<div class="formField">Zip file of Materials: (required)</div>
		<div class="formField">
	      	<input type="file" name="zip_userfile" id="zip_userfile" size="30" />
	  </div>

  	<div class="formField">
				<br/>
				<input type="submit" value="Add" />
		</div>		
	</form>
</div>


<div class="column span-16 first last" style="margin-top:50px; text-align: left;">
	<input type="button" value="Close" id="do_close_uploadmat_pane"/>
</div>

</div>
