<!-- STATUS -->
<table width="558px">
	<!-- ASK INSTRUCTOR -->
  <tr>
		<th>Ask instructor about origin of CO?</th>
		<td>
			  <?php 
							  $yes = ($obj['ask']=='yes') ? true : false;
							  $no = ($obj['ask']=='yes') ? false : true;
			          echo form_radio('ask_inst', 'yes', $yes, 'class="do_object_update" id="ask_inst_yes"').'&nbsp;Yes&nbsp;';
			          echo form_radio('ask_inst', 'no', $no, 'class="do_object_update" id="ask_inst_no"').'&nbsp;No&nbsp;';
				?>

				<div id="ask_yes" style="display: <?= ($obj['ask']=='yes') ? 'inline':'none'?>"> 
							<br/><br/>
							<a target="_new" href="<?=site_url("materials/askforms/$cid/$mid/provenance/instructor")?>">View ASK form</a> to see the default questions.
							<br/><br/>
							<a href="#orig_q_addpanel" onclick="orig_q_ap.setrole('instructor'); orig_q_ap.show();">Ask instructor additional questions</a>&nbsp;&raquo;
							<br/><br/>
							<a href="#origquestions">View answers</a>&nbsp;&raquo;
							<br/><br/>
							<?php echo $this->coobject->ask_instructor_report($cid,$mid,$obj,'original'); ?>
				</div>
 	  </td>
	</tr>

	<!-- ASK dSCRIBE2 -->
	<tr>
		<th>Ask dScribe2 a general question about the CO?</th>
		<td>
			  <?php 
							  $yes = ($obj['ask_dscribe2']=='yes') ? true : false;
							  $no = ($obj['ask_dscribe2']=='yes') ? false : true;
							  echo form_radio('ask_dscribe2', 'yes', $yes, 'class="do_object_update do_object_ask_dscribe2_yesno" id="ask_dscribe2r_yes"').'&nbsp;Yes&nbsp;'; 
							  echo form_radio('ask_dscribe2', 'no', $no, 'class="do_object_update do_object_ask_dscribe2_yesno" id="ask_dscribe2r_no"').'&nbsp;No&nbsp;&nbsp;';
				?>

				<div id="ask_dscribe2_yes" style="display: <?= ($obj['ask_dscribe2']=='yes') ? 'inline':'none'?>"> 
							<br/><br/>
							<a target="_new" href="<?=site_url("materials/askforms/$cid/$mid/general/dscribe2")?>">View dScribe2 ASK form</a>
							<br/><br/>
							<a href="#orig_q_addpanel" onclick="orig_q_ap.setrole('dscribe2'); orig_q_ap.show();">Ask dScribe2 additional questions</a>&nbsp;&raquo;
							<br/><br/>
							<a href="#origquestions">View answers</a>&nbsp;&raquo;
				</div>
	  </td>
	</tr>
	<tr>
		<th>Recommended Action:</th>
		<td>
			 <?php echo form_dropdown('action_type', $action_types, $obj['action_type'] ,'id="action_type" class="do_object_update"'); ?>
				<input type="hidden" value="<?=$obj['action_type']?>" id="raction" name="raction" />
		</td>
  </tr>

  <tr>
  		<td colspan="2">
				<p style="padding:5px; background-color:yellow; border:2px solid gray; color:black;display:none" id="update_msg">Sent to dScribe2!</p>

  			<div id="Fair Use" style="display: <?= ($obj['action_type']=='Fair Use') ? 'block':'none'?>">
  				<p>
						<b>Please provide the dScribe2 with your rationale for the fair use of this content object in the space below. (REQUIRED)</b><br/>
			       	<textarea name="fairuse_rationale" id="fairuse_rationale" rows="10" cols="50" class="do_object_rationale"><?=$fairuse_rationale?></textarea>
							<br/><br/>
							<input type="button" value="Send to dScribe2" class="do_update_action_type" />
					</p>
	  		</div>

				<div id="Permission" style="display: <?= ($obj['action_type']=='Permission') ? 'block':'none'?>">
			  				<p>
								<b>Provide a detailed description of the content object for reference in the permission form (REQUIRED).</b><br/>
						       	<textarea name="description" id="description" rows="10" cols="50" class="do_update_description"><?=$obj['description']?></textarea><br/>
						       	<b>Please provide the contact information for the copyright holder of this content object.</b><br/>
						       	<table>
						       		<tr>
						       			<th>
						       				<label for="contact_name">Name: (required)</label>
						       			</th>
						       			<td>
						       				<input type="text"  name="contact_name" id="contact_name"  class="do_update_contact" size="50" value="<?=isset($contact_name)?$contact_name:''?>" />
						       			</td>
						       		</tr>
						       		<tr>
						       			<th>
						       				<label for="contact_address_1">Address:</label>
						       			</th>
						       			<td>
						       				<input type="text"  name="contact_line1" id="contact_line1" class="do_update_contact" size="50" value="<?=isset($contact_line1)?$contact_line1:''?>"/>
						       			</td>
						       		</tr>
						       		<tr>
						       			<th>
						       			</th>
						       			<td>
						       				<input type="text"  name="contact_line2" id="contact_line2"  class="do_update_contact" size="50" value="<?=isset($contact_line2)?$contact_line2:''?>"/>
						       			</td>
						       		</tr>
						       		<tr>
						       			<th>
						       				<label for="contact_city">City:</label>
						       			</th>
						       			<td>
						       				<input type="text"  name="contact_city" id="contact_city"  class="do_update_contact" size="50" value="<?=isset($contact_city)?$contact_city:''?>"/>
						       			</td>
						       		</tr>
						       		<tr>
						       			<th>
						       				<label for="contact_state">State:</label>
						       			</th>
						       			<td>
						       				<input type="text"  name="contact_state" id="contact_state"  class="do_update_contact" size="50" value="<?=isset($contact_state)?$contact_state:''?>"/>
						       			</td>
						       		</tr>
						       		<tr>
						       			<th>
						       				<label for="contact_country">Country:</label>
						       			</th>
						       			<td>
						       				<input type="text"  name="contact_country" id="contact_country"  class="do_update_contact" size="50" value="<?=isset($contact_country)?$contact_country:''?>"/>
						       			</td>
						       		</tr>
						       		<tr>
						       			<th>
						       				<label for="contact_postalcode">Postal Code:</label>
						       			</th>
						       			<td>
						       				<input type="text"  name="contact_postalcode" id="contact_postalcode"  class="do_update_contact" size="50" value="<?=isset($contact_postalcode)?$contact_postalcode:''?>"/>
						       			</td>
						       		</tr>
						       		<tr>
						       			<th>
						       				<label for="contact_phone">Phone:(required) </label>
						       			</th>
						       			<td>
						       				<input type="text"  name="contact_phone" id="contact_phone"  class="do_update_contact" size="50" value="<?=isset($contact_phone)?$contact_phone:''?>"/>
						       			</td>
						       		</tr>
						       		<tr>
						       			<th>
						       				<label for="contact_fax">Fax:</label>
						       			</th>
						       			<td>
						       				<input type="text"  name="contact_fax" id="contact_fax"  class="do_update_contact" size="50" value="<?=isset($contact_fax)?$contact_fax:''?>"/>
						       			</td>
						       		</tr>
						       		<tr>
						       			<th>
						       				<label for="contact_email">Email: (required)</label>
						       			</th>
						       			<td>
						       				<input type="text"  name="contact_email" id="contact_email"  class="do_update_contact" size="50" value="<?=isset($contact_email)?$contact_email:''?>"/>
						       			</td>
						       		</tr>
						       	</table>	
							<br/><br/>
							<input type="button" value="Send to dScribe2" class="do_update_action_type" />
							</p>
			</div>

  		<div id="Commission" style="display: <?= ($obj['action_type']=='Commission') ? 'block':'none'?>">
			  				<p>
								<b>Please provide the dScribe2 with your rationale for commissioning a re-creation of this content object in the space below. (REQUIRED)</b><br/>
						       	<textarea name="commission_rationale" id="commission_rationale" rows="10" cols="50" class="do_object_rationale"><?=$commission_rationale?></textarea><br/>
						       	<b>Provide a detailed description of the desired re-creation of this content object. Provide as much context as possible to explain what critical features the re-creation should emphasize.</b><br/>
						       	<textarea name="description" id="description" rows="10" cols="50" class="do_update_description"><?=$obj['description']?></textarea>
							<br/><br/>
							<input type="button" value="Send to dScribe2" class="do_update_action_type" />
							</p>
  		</div>

  		<div id="Retain" style="display: <?= (substr($obj['action_type'], 0, 6)=='Retain') ? 'block':'none'?>">
			  				<p>
								<b>Please provide the dScribe2 with your rationale for retaining this content object in the space below. (REQUIRED)</b><br/>
						       	<textarea name="retain_rationale" id="retain_rationale" rows="10" cols="50" class="do_object_rationale"><?=$retain_rationale?></textarea>
							<br/><br/>
							<input type="button" value="Send to dScribe2" class="do_update_action_type" />
							</p>
  		</div>
		</td>
  </tr>


  <tr>
		<th>Final Action Taken:</th>
		<td>
				<?php echo form_dropdown('action_taken', $action_types, $obj['action_taken'] ,'id="action_taken" class="do_object_update"'); ?>
		</td>
  </tr>

	<tr>
		<th>Is this CO cleared for publishing?</th>
		<td colspan="3">
						  <?php 
							  $yes = ($obj['done']=='1') ? true : false;
							  $no = ($obj['done']=='1') ? false : true;
							  echo form_radio('done', '1', $yes, 'class="do_object_update"').'&nbsp;Yes&nbsp;'; 
							  echo form_radio('done', '0', $no, 'class="do_object_update"').'&nbsp;No';
						  ?>
								<input type="hidden" name="ask_status" id="ask_status"
											 value="<?= (($obj['ask']=='yes') ? (($obj['ask_status']=='done') ? 'true':'false'): 'true') ?>">
 	    </td>
	</tr>
</table>

<!-- QUESTIONS -->
<br/>
<h2 style="display:inline">Questions&nbsp;(<small><a href="javascript:void(0);" onclick="orig_q_ap.toggle()">Ask Question</a></small>)</h2>
<br/><br/>
<div id="orig_q_addpanel" style="color:black">
			<label for="origrole">Ask:</label>
			<select name="origrole" id="origrole">
				<option value="instructor">Instructor</option>
				<option value="dscribe2">dScribe2</option>
			</select><br/>
 			<textarea name="question" id="question" cols="50"></textarea>
 			<p>
     						<input type="button" value="Save" class="do_add_object_question" />
		     				<input type="button" value="Cancel" onclick="orig_q_ap.hide()" />
		     				<br/><hr style="border: 1px dotted #555"/><br/>
 			</p>
</div>

<em style="color:black;">Note: Hold down the shift key to select multiple columns to sort</em>
<table id="origquestions" class="sortable-onload-7-reverse rowstyle-alt no-arrow" width="100%" bgcolor="white">
<thead>
	<tr>
		<th class="sortable">Asked to</th>
		<th>Question</th>
		<th class="sortable">Answer</th>
		<th class="sortable">Asked by</th>
  	<th class="sortable-sortEnglishLonghandDateFormat">Answered By</th>
  	<th class="sortable-sortEnglishLonghandDateFormat">Asked On</th>
  	<th class="sortable-sortEnglishLonghandDateFormat">Last Modified</th>
	</tr>
</thead>

<tbody id="objectqs">
<?php 
if ($questions == null) { ?>
	  <tr id="noquestions"><td colspan="7">No questions posted.</td></tr> 
<?php } else { 
		foreach($questions as $askee => $qs) { 
			foreach($qs as $question) {
	                // bdr - NOTE: I changed ocw_user->username to use ocw_user->goofyname
			//             because long username was skewing the columns in the table
?>
		   <tr>
		   <td><?=ucfirst($askee)?></td>
		   <td><?=$question['question']?></td>
		   <td><?=($question['answer']=='') ? 'No answer' : $question['answer'] ?></td>
    		   <td><?=$this->ocw_user->goofyname($question['user_id'])?></td>
    		   <td><?= ($this->ocw_user->goofyname($question['modified_by'])) ? $this->ocw_user->goofyname($question['modified_by']):''?></td>
    		   <td><?=mdate('%d %M, %Y %H:%i',mysql_to_unix($question['created_on']))?></td>
    		   <td><?=mdate('%d %M, %Y %H:%i',mysql_to_unix($question['modified_on']))?></td>
		   </tr>
<?php }}} ?>	
</tbody>
</table>
