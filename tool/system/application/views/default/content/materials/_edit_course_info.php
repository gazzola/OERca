<?php	
?>

<div id="pane_courseinfo" class="editpane">
  <div class="column span-21 firstlast">
    <?php echo form_open_multipart("courses/check_course_info/$cid"); ?>
      <div class="column span-6 colborder">
        <div class="formLabel">Course ID:</div>
        <div class="formField">
          <input type="text" name="id" id ="id" class="input" />
        </div>

        <div class="formLabel">Title:</div>
        <div class="formField">
          <input type="text" name="title" id="title" class="input"
          />
        </div>

        <div class="formLabel">Director (Med School Only):</div>
        <div class="formField">
          <input type="text" name="director" id="director" class="input" />
        </div>
        
        <div class="formLabel">Creator:</div>
        <div class="formField">
          <input type="text" name="creator" id="creator" class="input" />
        </div>

        <div class="formLabel">Collaborator:</div>
        <div class="formField">
          <input type="text" name="collaborator" id="collaborator"
          class="input" />
        </div>

        <div class="formLabel">School:</div>
        <div class="formField">
          <?php echo form_dropdown('school', $school, '', 'id="school"'); ?>
        </div>

        <div class="formLabel">Level:</div>
        <div class="formField">
          <?php echo form_dropdown('courselevel', $courselevel, '', 
        'id="courselevel"'); ?>
        </div>

        <div class="formLabel">Length:</div>
        <div class="formField">
          <?php echo form_dropdown('courselength', $courselength, '', 
          'id="courselength"'); ?>
        </div>
        
        <div class="formLabel">Term:</div>
        <div class="formField">
          <?php echo form_dropdown('term', $term, '', 'id="term"'); ?>
        </div>

        <div class="formLabel">Year:</div>
        <div class="formField">
          <?php echo form_dropdown('year', $year, $curryear, 'id="year"'); 
          ?>
        </div>
      </div>
      
      <div class="column span-6 colborder">
        <div class="formLabel">Rights:</div>
        <div class="formField">
          <input type="text" name="rights" id="rights" class="input" />
        </div>
        
        <div class="formLabel">Language:</div>
        <div class="formField">
          <input type="text" name="language" id="language" value="English" 
          class="input" />
        </div>
        
        <div class="formLabel">Subject:</div>
        <div class="formField">
          <input type="text" name="subject" id="subject" class="input" />
        </div>
        
        <div class="formLabel">Curricular Information:</div>
        <div class="formField">
          <input type="text" name="currinfo" id="currinfo" class="input" />
        </div>
        
        <div class="formLabel">Lifecycle Version:</div>
        <div class="formField">
          <input type="text" name="lcversion" id="lcversion" class="input" />
        </div>
        
        <div class="formLabel">Course Image/Icon:</div>
        <div class="formField">
          <input type="file" name="icon" id="icon">
        </div>
      </div>
      
      <div class="column span-6 last">  
        <div class="formLabel">Highlights:</div>
        <div class="formField">
          <?php echo form_textarea($coursehighlightbox); ?>
        </div>
        
        <div class="formLabel">Description:</div>
        <div class="formField">
          <?php echo form_textarea($coursedescbox); ?>
        </div>
        
        <div class="formLabel">Keywords:</div>
        <div class="formField">
          <?php echo form_textarea($keywordbox); ?>
        </div>
        
        <div class="formField">
          <br />
          <input type="submit" value="Save" />
        </div>
      </div>
    </form>
  </div>
  
  <div class="column span-21 firstlast" style="margin-top:50px; 
  text-align:right;">
    <input type="button" value="Close" id="do_close_courseinfo_pane"/>
  </div>
</div>
