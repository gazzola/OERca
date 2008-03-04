<div id="pane_uploadco" class="editpane" style="height: 15px;">

<div class="column span-15 first last">
		<div class="formField">
			<form action="<?=site_url("materials/add_object_zip/$cid/$mid/")?>" enctype="multipart/form-data" id="add_co_zip" method = "post">
			<b>Upload Content Objects ZIP file:</b>
      	<input type="file" name="userfile" id="userfile" size="30" />
        <br/><br/>
      	<input type="submit" name="submit" id="submit" value="Upload" />
			</form>
    </div>
</div>

<div class="column span-21 first last">
	<br/><br/>
  <input type="button" value="Close" id="do_close_uploadco_pane"/>
</div>

</div>
