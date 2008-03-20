<?php
?>

<div id="pane_instinfo" class="editpane">
  <div class="column span-21 firstlast">
    <?php echo form_open_multipart("instructors/edit_instructor_info")?>
      <div class="formLabel">Name:</div>
      <div class="formField">
        <input type="text" name="name" id="name" class="input" />
      </div>
      
      <div class="formLabel">Title:</div>
      <div class="formField">
        <input type="text" name="title" id="title" class="input" />
      </div>
      
      <div class="formLabel">Information:</div>
      <div class="formField">
        <input type="text" name="info" id="info" class="input" />
      </div>
      
      <div class="formLabel">URI:</div>
      <div class="formField">
        <input type="text" name="uri" id="uri" class="input">
      </div>
      
      <!-- ><div class-"formLabel">Image:</div>
      <div class="formField">
        <input type="file" name="imagefile" id="imagefile">
      </div> -->
      
      <div class="formField">
      <br />
      <input type="submit" value="Save" />
      </div>
    </form>
  </div>
  <!-- TODO: When image upload code is done, display existing image if one  
    exists -->
  <div class="column span-21 firstlast" style="margin-top:50px; 
  text-align:right;">
    <input type="hidden" value="Close" id="do_close_instinfo_pane"/>
  </div>
</div>
