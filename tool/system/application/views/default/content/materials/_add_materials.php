<?php	
?>

<div id="pane_uploadmat" class="editpane">

<div class="column span-21 first last" style="padding: 40px; text-align: center;">
	
	<form>
		<input type="hidden" name="category" value="Resource Items" />
		<input type="hidden" name="course_id" value="<?=$cid?>" />
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
	</form>
	
	<br/><br/>
  
	<input type="button" value="Done" id="do_close_uploadmat_pane"/>

</div>

</div>
