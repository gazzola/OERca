<?php	
?>

<div id="pane_uploadmat" class="editpane">

<div class="column span-21 first last" style="text-align: center;">
	
<form action="<?=site_url("materials/add_material/$cid")?>" enctype="multipart/form-data" method = "post" id="add_new_material">
		<input type="hidden" name="category" value="Resource Items" />
		<input type="hidden" name="in_ocw" value="1" />
		<input type="hidden" name="nodetype" value="parent" />
		
		<div class="formLabel">Name:</div>
		<div class="formField">
			<input type="text" name="name" id="name" class="input" size="40px"/>
		</div>

		<div class="formLabel">Author:</div>
		<div class="formField">
			<input type="text" name="author" id="author" class="input" size="40px"  />
		</div>

		<div class="formLabel">Collaborators:</div>
		<div class="formField">
			<textarea name="collaborators" id="collaborators" cols="40" rows="4"></textarea>
		</div>
				
		<div class="formLabel"><b>Zip file of Materials:</b></div>
		<div class="formField">
	      	<input type="file" name="userfile" id="userfile" size="30" />
			    <small style="color:red">NB: any existing replacement image will be overwritten</small>	
	  </div>

  	<div class="formField">
				<input type="submit" value="Add" />
				<input type="button" value="Close" id="do_close_uploadmat_pane"/>
		</div>		
</form>
	
</div>
<script type="text/javascript">EventSelectors.start(Rules);</script>
<script type="text/javascript">
 new MultiUpload( $('add_new_material').userfile, 1, null, true, true);
</script>
</div>
