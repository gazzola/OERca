<div id="addpanel" class="panel" style="display:none;">
<div>
	<h2>Add Course Material:</h2><br/>
 	<div id="addpanel_error" 
          style="color:red; display: none; width: 90%; border: 1px solid #ccc; background-color: #eee; margin-bottom: 10px;"></div>
		
			<table width="100%">
				<tr>
            		<th align="right">Curriculum</th>
            		<td>
						<?php echo form_dropdown('curriculum', $curriculum, 0,'id="curriculum"'); ?>
						&nbsp;	
						<input type="text" size="20" name="new_curriculum" id="new_curriculum" style="display:none;" />
					</td>
				</tr>	
				<tr>
            		<th align="right">Sequence</th>
            		<td>
						<?php echo form_dropdown('sequence', $sequences, 0,'id="sequence"'); ?>
						&nbsp;	
						<input type="text" size="20" name="new_sequence" id="new_sequence" style="display:none;" />
					</td>
				</tr>	
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr>
            		<th align="right">Sequence Director</th>
            		<td>
						<input type="text" size="25" name="director" id="director" />
					</td>
				</tr>	
				<tr>
            		<th align="right">Collaborators</th>
            		<td>
						<textarea rows="5" cols="40" name="collaborators" id="collaborators"></textarea>
					</td>
				</tr>	
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr>
            		<th align="right">Course number</th>
            		<td>
						<input type="text" size="25" name="cnumber" id="cnumber" />
					</td>
				</tr>	
				<tr>
            		<th align="right">Course title</th>
            		<td>
						<input type="text" size="25" name="ctitle" id="ctitle" />
					</td>
				</tr>	
				<tr>
            		<th align="right">Start date</th>
            		<td>
			<input type="text" class="w8em format-y-m-d divider-dash highlight-days-12 range-low-today no-transparency" id="start_date" name="start_date" value="" maxlength="10" /></td>
				</tr>	
				<tr>
            		<th align="right">End date</th>
            		<td>
						<input type="text" class="w8em format-y-m-d divider-dash highlight-days-12 range-low-today no-transparency" id="end_date" name="end_date" value="" maxlength="10" /></td>
				</tr>	
				<tr>
            		<th align="right">Class</th>
            		<td><input type="text" size="25" name="class" id="class" /></td>
				</tr>	
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr>
            		<th align="right">dScribe course</th>
            		<td>
						<input type="radio" id="dscribe" name="dscribe" value="1" checked="checked"/>&nbsp;Yes
						&nbsp;&nbsp;
						<input type="radio" id="dscribe" name="dscribe" value="0" />&nbsp;No
					</td>
				</tr>	
			</table>

	   		<p>
				<input type="button" value="Add" class="do_add_new_course" />
	   			<input type="button" value="Cancel" class="do_hide_addpanel" />
			</p>
		</div>
		</div>
