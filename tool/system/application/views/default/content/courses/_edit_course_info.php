<?php	
// TODO: Narrow down pulldown choices using on select events in previous
// menu
echo style('blueprint/screen.css',array('media'=>"screen, projection"));
echo style('blueprint/print.css',array('media'=>"print"));
echo '<!--[if IE]>'.style('blueprint/lib/ie.css',array('media'=>"screen, projection")).'<![endif]-->';
echo style('style.css',array('media'=>"screen, projection"));
echo style('mootabs1.2.css',array('media'=>"screen, projection"));
echo '<style type="text/css">body { background-color: #222; padding: 15px; margin:auto; width: 800px; border:0px solid blue; height:450px; color:#999}</style>';

echo script('mootools.js');
echo script('mootabs1.2.js');
echo script('event-selectors.js');
echo script('event-rules.js');

echo script('flash.js');

$flash=$this->db_session->flashdata('flashMessage');
if (isset($flash) AND $flash!='') {
?>
<!--START FLASH MESSAGE-->
<div id="statusmsg" class="column span-20 first last">
  <div id="flashMessage" style="display:none;"><?=$flash?></div>
</div>
<!--END FLASH-->
<?php } ?>

<h2>Edit Course</h2>

<div style="border: 1px solid #888; margin-top: 10px; margin-bottom: 10px; margin-left: -10px; padding: 5px;" class="column span-21 first last">
    <?php echo form_open_multipart("courses/edit_course_info/$cid/edit"); ?>
		<div class="column span-6 colborder">
        <div class="formLabel"><span style="color:red">*</span>School:</div>
        <div class="formField">
          <?php echo form_dropdown('school_id', $school_id, 
          $coursedetails['school_id'], 'id="school_id" class="do_curriculum_subject_update"'); ?>
        </div>
				<br/>
      
				<div class="formLabel"><span style="color:red">*</span>Curriculum:</div>
				<div class="formField">
					<?php echo form_dropdown('curriculum_id', $curriculum_list, 
					$coursedetails['curriculum_id'], 'id="curriculum_id"'); ?>
				</div>
				<br/>

        <div class="formLabel">Course Subject:</div>
        <div class="formField">
          <?php echo form_dropdown('subj_id', $subj_id, 
          $coursedetails['subject_id'], 'id="subj_id"');
           ?>
        </div>
				<br/>
      
        <div class="formLabel">Course Number:</div>
        <div class="formField">
          <input type="text" name="cnum" id ="cnum" 
          value="<?=$coursedetails['number'] ?>" class="input" />
        </div>
				<br/>
      
        <div class="formLabel"><span style="color:red">*</span>Title:</div>
        <div class="formField">
          <input type="text" name="title" id="title" 
          value="<?=$coursedetails['title'] ?>" class="input" />
        </div>
				<br/>
      
        <div class="formLabel"><span style="color:red">*</span>Level:</div>
        <div class="formField">
          <?php echo form_dropdown('courselevel', $courselevel, 
          $coursedetails['level'], 'id="courselevel"'); ?>
        </div>
				<br/>
      
        <div class="formLabel"><span style="color:red">*</span>Length:</div>
        <div class="formField">
          <?php echo form_dropdown('courselength', $courselength, 
          $coursedetails['length'], 'id="courselength"'); ?>
        </div>
				<br/>
      
        <div class="formLabel"><span style="color:red">*</span>Term:</div>
        <div class="formField">
          <?php echo form_dropdown('term', $term, $coursedetails['term'], 
          'id="term"'); ?>
        </div>
				<br/>
      
        <div class="formLabel"><span style="color:red">*</span>Year:</div>
        <div class="formField">
          <?php echo form_dropdown('year', $year, $curryear, 'id="year"'); ?>
        </div>
      </div>
      
      <div class="column span-6 colborder">

			<div class="formLabel"><span style="color:red">*</span>Start Date:</div>
			<div class="formField">
				<input type="text" name="start_date" id="start_date"
				value="<?=$coursedetails['start_date'] ?>" class="input" tabindex="10" />
			</div>
			<br>
				
			<div class="formLabel"><span style="color:red">*</span>End Date:</div>
			<div class="formField">
				<input type="text" name="end_date" id="end_date"
				value="<?=$coursedetails['end_date'] ?>" class="input" tabindex="11" />
			</div>
			<br>

      <div class="formLabel">Director (Med School Only):</div>
      <div class="formField">
				<input type="text" name="director" id="director" 
					value="<?=$coursedetails['director'] ?>" class="input" />
			</div>
			<br/>
      
			<div class="formLabel">Creator:</div>
			<div class="formField">
				<input type="text" name="creator" id="creator" 
					value="<?=$instdetails['name'] ?>" class="input" />
			</div>
			<br/>
      
			<div class="formLabel">Collaborators:</div>
			<div class="formField">
				<input type="text" name="collaborators" id="collaborators"
					value="<?=$coursedetails['collaborators'] ?>" class="input" />
			</div>
			<br/>
      
			<!-- <div class="formLabel">Copyright Holder:</div>
			<div class="formField">
				<input type="text" name="copyright_holder" id="copyright_holder"
					class="input" />
			</div>
			<br/> -->

			<div class="formLabel">Language:</div>
			<div class="formField">
				<input type="text" name="language" id="language" 
					value="<?=$coursedetails['language'] ?>" class="input" />
			</div>
        
			<!-- <div class="formLabel">Curricular Information:</div>
			<div class="formField">
				<input type="text" name="currinfo" id="currinfo" class="input" />
			</div>
			<br/> -->
      
			<!-- <div class="formLabel">Lifecycle Version:</div>
			<div class="formField">
				<input type="text" name="lcversion" id="lcversion" class="input" />
			</div>
			<br/> -->
      
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
		</div>
		<br style="clear:both"/><br/><br/>
		<span style="text-align:center">
			<input type="submit" style="float:left" value="Save" />
		</span>
	</div>
	<div style="clear:both"/>
		<input type="button" style="float:right" value="Cancel" onclick="parent.window.location.reload(); parent.TB_remove();"/>
		<input type="button" style="float:left" value="Done" onclick="parent.window.location.reload(); parent.TB_remove();"/>
	</div>
  </form>
</div>
 
<div id="feedback" style="display:none"></div>
<input type="hidden" id="cid" name="cid" value="<?=$cid?>" />
<input type="hidden" id="imgurl" value="<?=property('app_img')?>" />
<input type="hidden" id="server" value="<?=site_url();?>" />
<script type="text/javascript">EventSelectors.start(Rules);</script>
