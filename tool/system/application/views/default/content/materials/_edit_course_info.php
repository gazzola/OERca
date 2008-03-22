<?php	
// TODO: Narrow down pulldown choices using on select events in previous
// menu
?>

<div id="pane_courseinfo" class="editpane">
  <div class="column span-21 firstlast">
    <?php echo form_open_multipart("courses/edit_course_info/$cid"); ?>
      <div class="column span-6 colborder">
        <div class="formLabel">School:</div>
        <div class="formField">
          <?php echo form_dropdown('school_id', $school_id, 
          $coursedetails['school_id'], 'id="school_id"'); ?>
        </div>
      
        <div class="formLabel">Course Subject:</div>
        <div class="formField">
          <?php echo form_dropdown('subj_id', $subj_id, 
          $coursedetails['subject_id'], 'id="subj_id"');
           ?>
        </div>
      
        <div class="formLabel">Course Number:</div>
        <div class="formField">
          <input type="text" name="cnum" id ="cnum" 
          value="<?=$coursedetails['number'] ?>" class="input" />
        </div>

        <div class="formLabel">Title:</div>
        <div class="formField">
          <input type="text" name="title" id="title" 
          value="<?=$coursedetails['title'] ?>" class="input" />
        </div>

        <div class="formLabel">Level:</div>
        <div class="formField">
          <?php echo form_dropdown('courselevel', $courselevel, 
          $coursedetails['level'], 'id="courselevel"'); ?>
        </div>

        <div class="formLabel">Length:</div>
        <div class="formField">
          <?php echo form_dropdown('courselength', $courselength, 
          $coursedetails['length'], 'id="courselength"'); ?>
        </div>
        
        <div class="formLabel">Term:</div>
        <div class="formField">
          <?php echo form_dropdown('term', $term, $coursedetails['term'], 
          'id="term"'); ?>
        </div>

        <div class="formLabel">Year:</div>
        <div class="formField">
          <?php echo form_dropdown('year', $year, $curryear, 'id="year"'); ?>
        </div>
      </div>
      
      <div class="column span-6 colborder">
       <div class="formLabel">Director (Med School Only):</div>
       <div class="formField">
         <input type="text" name="director" id="director" 
         value="<?=$coursedetails['director'] ?>" class="input" />
       </div>
        
        <div class="formLabel">Creator:</div>
        <div class="formField">
          <input type="text" name="creator" id="creator" 
          value="<?=$instdetails['name'] ?>" class="input" />
        </div>

        <div class="formLabel">Collaborators:</div>
        <div class="formField">
          <input type="text" name="collaborators" id="collaborators"
          value="<?=$coursedetails['collaborators'] ?>" class="input" />
        </div>
        
        <!-- <div class="formLabel">Copyright Holder:</div>
        <div class="formField">
          <input type="text" name="copyright_holder" id="copyright_holder"
           class="input" />
        </div> -->
        
        <div class="formLabel">Language:</div>
        <div class="formField">
          <input type="text" name="language" id="language" 
          value="<? $coursedetails['language'] ?>" class="input" />
        </div>
        
        <!-- <div class="formLabel">Curricular Information:</div>
        <div class="formField">
          <input type="text" name="currinfo" id="currinfo" class="input" />
        </div> -->
        
        <!-- <div class="formLabel">Lifecycle Version:</div>
        <div class="formField">
          <input type="text" name="lcversion" id="lcversion" class="input" />
        </div> -->
        <!-- TODO: Enable image upload -->
        <!-- <div class="formLabel">Course Image/Icon:</div>
        <div class="formField">
          <input type="file" name="icon" id="icon">
        </div> -->
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
