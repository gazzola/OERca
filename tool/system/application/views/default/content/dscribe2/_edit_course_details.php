<a href="javascript:void(0);" class="do_show_editpanel"><img src="<?= property('app_img').'/pencil.png'?>" valign="bottom"/>&nbsp; Edit Course Details</a>

<div id="ippanel_error" 
		style="color:red; display: none; width: 90%; border: 1px solid #ccc; background-color: #eee; margin-bottom: 10px;">
</div>

<div id="editpanel" class="panel" style="display:none;">
<div>
	<div id="editpanel_error" 
         style="color:red; display: none; width: 90%; border: 1px solid #ccc; background-color: #eee; margin-bottom: 10px;"></div>
<table>
				<tr>
            		<th align="right">Curriculum</th>
            		<td>
						<?php echo form_dropdown('curriculum_id', $curriculum, $course['curriculum_id'],'id="curriculum_id" class="update_course"'); ?>
						&nbsp;	
						<input type="text" size="20" name="new_curriculum_id" id="new_curriculum_id" style="display:none;" />
					</td>
				</tr>	
				<tr>
            		<th align="right">Sequence</th>
            		<td>
						<?php echo form_dropdown('sequence_id', $sequences, $course['sequence_id'],'id="sequence_id" class="update_course"'); ?>
						&nbsp;	
						<input type="text" size="20" name="new_sequence_id" id="new_sequence_id" style="display:none;" />
					</td>
				</tr>	
				<tr>
            		<th align="right">Sequence Director</th>
            		<td>
						<input type="text" size="25" name="director" id="director" value="<?=$course['director']?>"  class="update_course"/>
					</td>
				</tr>	
				<tr>
            		<th align="right">Collaborators</th>
            		<td>
						<textarea rows="5" cols="40" name="collaborators" id="collaborators" class="update_course"><?=$course['collaborators']?></textarea>
					</td>
				</tr>	
				<tr>
            		<th align="right">Course number</th>
            		<td>
						<input type="text" size="25" name="number" id="number" value="<?=$course['number']?>" class="update_course"/>
					</td>
				</tr>	
				<tr>
            		<th align="right">Course title</th>
            		<td>
						<input type="text" size="25" name="title" id="title" value="<?=$course['title']?>" class="update_course"/>
					</td>
				</tr>	
				<tr>
            		<th align="right">Start date</th>
            		<td>
			<input type="text" class="update_course w8em format-y-m-d divider-dash highlight-days-12 range-low-today no-transparency" id="start_date" name="start_date" value="<?=$course['start_date']?>" maxlength="10" /></td>
				</tr>	
				<tr>
            		<th align="right">End date</th>
            		<td>
						<input type="text" class="update_course w8em format-y-m-d divider-dash highlight-days-12 range-low-today no-transparency" id="end_date" name="end_date" value="<?=$course['end_date']?>" maxlength="10" /></td>
				</tr>	
				<tr>
            		<th align="right">Class</th>
            		<td><input type="text" size="25" name="class" id="class" value="<?=$course['class']?>" class="update_course"/></td>
				</tr>	
	</table>
			   			<p>
							<input type="button" value="Save" id="save" name="save" class="update_course" />
			   				<input type="button" value="Close" class="do_hide_editpanel" />
						</p>
</div>
</div>
