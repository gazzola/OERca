<?php	
$action_types = array('Permission'=>'Permission',
						'Search'=>'Search',
						'Fair Use'=>'Fair Use',
						'Re-Create'=>'Re-Create', 
						'Retain: Instructor Created'=>'Retain: Instructor Created',
						'Retain: Public Domain' =>'Retain: Public Domain',
						'Retain: No Copyright' =>'Retain: No Copyright',
						'Commission'=>'Commission', 
						'Remove & Annotate'=>'Remove & Annotate');
$action_taken_types=array_merge(array('no action assigned'=>'no action assigned'), $action_types);

$ask_status = array('new'=>'Instructor has not looked at this object yet.',
									  'in progress'=>'Instructor is working on this',
									 	'done'=>'Instructor is done reviewing this object');
									
$copy_status = array('unknown'=>'Unknown', 'copyrighted'=>'Copyrighted','public domain'=>'Public Domain');

$types = '<select id="subtype_id" name="subtype_id" class="do_object_update">';
foreach($subtypes as $type => $subtype) {
		$types .= '<optgroup label="'.$type.'">';
		foreach($subtype as $st) {
			$sel = ($obj['subtype_id']== $st['id']) ? 'selected' : '';
			$types .= '<option value="'.$st['id'].'" '.$sel.'>'.$st['name'].'</option>';
    }
		$types .= '</optgroup>';
} 
$types .= '</select>';

$copy = $obj['copyright'];
$cp_status = ($copy==null) ? '' : $copy['status'];
$cp_holder = ($copy==null) ? '' : $copy['holder'];
$cp_notice = ($copy==null) ? '' : $copy['notice'];
$cp_url = ($copy==null) ? '' : $copy['url'];
$questions = $obj['questions'];
$comments = $obj['comments'];
$log = $obj['log'];
?>
<!--<a id="do_open_coinfo_pane" onClick='"class=do_open_coinfo_pane" '>Edit Content Object info</a>-->
<div id="Original" class="mootabs_panel">
  <!-- STATUS -->
<div class="column span-17 first last">
  <br/><h3>Status</h3>
	<table width="100%">
		<tr>
			<th>Cleared:</th>
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
			<tr>
				<th>Recommended Action:</th>
				<td>
			  <?php echo form_dropdown('action_type', 
				  				$action_types, $obj['action_type'] ,'id="action_type" class="do_object_update do_object_action_type"'); ?>
				</td>
				<th>Final Action Taken:</th>
				<td>
				<?php echo form_dropdown('action_taken', 
				  				$action_taken_types, $obj['action_taken'] ,'id="action_taken" class="do_object_update"'); ?>
				</td>
      </tr>
	  <tr>
  		<td colspan='4'>
  			<div id="Fair Use" style="display: <?= ($obj['action_type']=='Fair Use') ? 'block':'none'?>">
  				<p>
					<b>Please provide the dScribe2 with your rationale for the fair use of this content object in the space below.</b><br/>
			       	<textarea name="fairuse_rationale" id="fairuse_rationale" rows="10" cols="50" class="do_object_rationale"><?=$fairuse_rationale?></textarea>
				</p>
	  		</div>
	  		<div id="Permission" style="display: <?= ($obj['action_type']=='Permission') ? 'block':'none'?>">
  				<p>
					<b>Provide a detailed description of the content object for reference in the permission form.</b><br/>
			       	<textarea name="description" id="description" rows="10" cols="50" class="do_update_description"><?=$obj['description']?></textarea><br/>
			       	<b>Please provide the contact information for the copyright holder of this content object.</b><br/>
			       	<table>
			       		<tr>
			       			<th>
			       				<label for="contact_name">Name:</label>
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
			       				<label for="contact_phone">Phone:</label>
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
			       				<label for="contact_email">Email:</label>
			       			</th>
			       			<td>
			       				<input type="text"  name="contact_email" id="contact_email"  class="do_update_contact" size="50" value="<?=isset($contact_email)?$contact_email:''?>"/>
			       			</td>
			       		</tr>
			       	</table>	
				</p>
	  		</div>
	  		<div id="Commission" style="display: <?= ($obj['action_type']=='Commission') ? 'block':'none'?>">
  				<p>
					<b>Please provide the dScribe2 with your rationale for commissioning a re-creation of this content object in the space below.</b><br/>
			       	<textarea name="commission_rationale" id="commission_rationale" rows="10" cols="50" class="do_object_rationale"><?=$commission_rationale?></textarea><br/>
			       	<b>Provide a detailed description of the desired re-creation of this content object. Provide as much context as possible to explain what critical features the re-creation should emphasize.</b><br/>
			       	<textarea name="description" id="description" rows="10" cols="50" class="do_update_description"><?=$obj['description']?></textarea>
				</p>
	  		</div>
	  		<div id="Retain" style="display: <?= (substr($obj['action_type'], 0, 6)=='Retain') ? 'block':'none'?>">
  				<p>
					<b>Please provide the dScribe2 with your rationale for retaining this content object in the space below.</b><br/>
			       	<textarea name="retain_rationale" id="retain_rationale" rows="10" cols="50" class="do_object_rationale"><?=$retain_rationale?></textarea>
				</p>
	  		</div>
		</td>
      </tr>
      <tr>
			<th colspan="4">Ask instructor about origin of CO?</th>
	  </tr>
	  <tr>
			<td colspan="4">
			  <?php 
				  $yes = ($obj['ask']=='yes') ? TRUE : FALSE;
				  $no = ($obj['ask']=='yes') ? FALSE : TRUE;
				  $data = array(
            			  	'name'        => 'ask',
              				'id'          => 'ask',
              				'value'       => 'yes',
              				'checked'     => $yes,
              				'class'       => 'do_object_update do_object_ask_yesno',
            		);
				  echo form_radio($data).'&nbsp;Yes&nbsp;';
				  $data = array(
            			  	'name'        => 'ask',
              				'id'          => 'ask',
              				'value'       => 'no',
              				'checked'     => $no,
              				'class'       => 'do_object_update do_object_ask_yesno',
            		);
				  echo form_radio($data).'&nbsp;No&nbsp;&nbsp;';
			?>
			<div id="ask_yes" style="display: <?= ($obj['ask']=='yes') ? 'block':'none'?>"> 
				<p>
					<b><?php echo '<a target="_new" href="'.site_url("materials/askforms/$cid/$mid/provenance/instructor").'">view ASK form</a>'; ?> to see the default questions.</b><br/>
					<b>Please add any additional questions for the instructor in the space below</b><br/>
			       	<textarea name="question" id="question" rows="10" cols="50" class="do_object_update"><?=$question?></textarea>
				</p>
			</div>
 	    </td>
		</tr>
		<tr>
			<th colspan="4">Ask dScribe2 a general question about the CO?</th>
		</tr>
		<tr>
			<td colspan="4">
			  <?php 
				  $yes = ($obj['ask_dscribe2']=='yes') ? true : false;
				  $no = ($obj['ask_dscribe2']=='yes') ? false : true;
				  echo form_radio('ask_dscribe2', 'yes', $yes, 'class="do_object_update do_object_ask_dscribe2_yesno"').'&nbsp;Yes&nbsp;'; 
				  echo form_radio('ask_dscribe2', 'no', $no, 'class="do_object_update do_object_ask_dscribe2_yesno"').'&nbsp;No&nbsp;&nbsp;';
			?>
			<div id="ask_dscribe2_yes" style="display: <?= ($obj['ask_dscribe2']=='yes') ? 'block':'none'?>"> 
				<p>
					<b>Please add any questions for the dScribe2 in the space below.</b><br/>
			       	<textarea name="dscribe2_question" id="dscribe2_question" rows="10" cols="50" class="do_object_update"><?=$dscribe2_question?></textarea>
				</p>
			</div>
 	    </td>
			</tr>
		</table>
	</div>
	<!-- INFORMATION -->
  <div class="column span-17 first last">
    <br/><h3>Information</h3>
			<table style="border:none" width="100%">
				<tr>
						<th>Content Type:</th>
	    			<td><?=$types?></td>
				</tr>
				<tr>
						<th>Location:</th>
	    			<td>
      			<input type="text" name="location" id="location" size="50" value="<?=$obj['location']?>" class="do_object_update"/>
						</td>
				</tr>
				<tr>
					<th>Author:</th>
					<td>
      			<input type="text" name="author" id="author" size="50" value="<?=$obj['author']?>" class="do_object_update"/>
					</td>
				</tr>
				<tr>
					<th>Contributor:</th>
					<td>
      			<input type="text" name="contributor" id="contributor" size="50" value="<?=$obj['contributor']?>" class="do_object_update"/>
					</td>
				</tr>
				<tr>
					<th style="vertical-align:top">Citation:</th>
					<td>
		    		<textarea name="citation" id="citation" cols="6" rows="1" class="do_object_update"><?=$obj['citation']?></textarea>
					</td>
				</tr>
				<tr>
					<th style="vertical-align: top">Description:</th>
					<td>
		    		<textarea name="description" id="description" cols="6" rows="1" class="do_object_update"><?=$obj['description']?></textarea>
					</td>
				</tr>
				<tr>
					<th style="vertical-align: top">Keywords:</th>
					<td>
		    		<textarea name="tags" id="tags" cols="6"  class="do_object_update"><?=$obj['tags']?></textarea>
					</td>
				</tr>
			</table>
</div>

<!-- COPYRIGHT -->
<div class="column span-17 first last">
  <br/><h3>Copyright</h3>
		<table width="100%">
				<tr>
					<th style="vertical-align: top">Copyright Status:</th>
					<td>
			  		<?php echo form_dropdown('copy_status_'.$obj['id'], 
				  				$copy_status, $cp_status ,'id="copy_status" class="do_object_cp_update"'); ?>
					</td>
				</tr>
				<tr>
					<th style="vertical-align: top">Copyright Holder:</th>
					<td>
      			<input type="text" name="copy_holder_<?=$obj['id']?>" id="copy_holder" size="50" value="<?=$cp_holder?>" class="do_object_cp_update"/>
					</td>
				</tr>
				<tr>
					<th style="vertical-align: top">Copyright Info URL:</th>
					<td>
      			<input type="text" name="copy_url_<?=$obj['id']?>" id="copy_url" size="50" value="<?=$cp_url?>" class="do_object_cp_update"/>
					</td>
				</tr>
				<tr>
					<th style="vertical-align: top">Copyright Notice:</th>
					<td>
		    		<textarea name="copy_notice_<?=$obj['id']?>" id="copy_notice" cols="10"  class="do_object_cp_update"><?=$cp_notice?></textarea>
					</td>
				</tr>
    </table>
</div>

<!-- Questions -->
<div class="column span-17 first last">
  	<br/><h3>Questions</h3>
					<small>
						<a href="javascript:void(0);" onclick="orig_q_ap.toggle()">Add questions</a>
  					<br/>
					</small>
					
					<div id="orig_q_addpanel">
   					<textarea name="question" id="question" cols="50"></textarea>
   					<p>
     				<input type="button" value="Save" class="do_add_object_question" />
     				<input type="button" value="Cancel" onclick="orig_q_ap.hide()" />
     				<br/><hr style="border: 1px dotted #555"/><br/>
   					</p>
  				</div>
				
					<div class="clear"><br/></div>
				
				  <div id="objectqs">
  					<?php if ($questions == null) { ?>
				 			<p id="noquestions">No questions posted</p>
						<?php } else { foreach($questions as $question) { ?>
     					<p><b><?=$question['question']?><b></p>
							<?php if ($question['answer']<>'') { ?>
							<p style="margin-left: 5px; border: 1px dotted #eee; background-color:white"><?=$question['answer']?></p>
     					<?php } ?>
							<p>
        				<small>by&nbsp;<?=$this->ocw_user->username($question['user_id'])?>&nbsp;
        					<?=strtolower($this->ocw_utils->time_diff_in_words($question['modified_on']))?>
        				</small>
     					</p>
     					<p><hr style="border: 1px dashed #eee"/></p>
   					<?php  }  } ?>
					</div>
</div>

<!-- COMMENTS -->
<div class="column span-17 first last">
  <br/><h3>Comments</h3>

	<small>
		<a href="javascript:void(0);" onclick="orig_com_ap.toggle();">Add Comment</a>
	</small>

  <br/>

	<div id="orig_com_addpanel">
   	<textarea name="comments" id="comments" cols="50"></textarea>
   	<p>
     <input type="button" value="Save" class="do_add_object_comment" />
     <input type="button" value="Cancel" onclick="orig_com_ap.hide()" />
     <br/><hr style="border: 1px dotted #555"/><br/>
   	</p>
  </div>

	<div class="clear"><br/></div>

  <div id="objectcomments">
  <?php if ($comments == null) { ?>
     <p id="nocomments">No comments posted</p>
  <?php } else { foreach($comments as $comment) { ?>
     <p><?=$comment['comments']?></p>
     <p>
        <small>by&nbsp;<?=$this->ocw_user->username($comment['user_id'])?>&nbsp;
        <?=strtolower($this->ocw_utils->time_diff_in_words($comment['modified_on']))?>
        </small>
     </p>
     <p><hr style="border: 1px dashed #eee"/></p>
   <?php  }  } ?>
   </div>
</div>

<!-- LOGS -->
<div class="column span-17 first last">
  <br/><h3>Log</h3>

 	<div id="objectlog">
	<br/>
    <?php if ($log == null) { ?>
     <p id="nocomments">No log items.</p>
    <?php } else { foreach($log as $l) { ?>
     <p><?=$l['log']?></p>
     <p>
       <small>by&nbsp;<?=$this->ocw_user->username($l['user_id'])?>&nbsp;
       <?=strtolower($this->ocw_utils->time_diff_in_words($l['modified_on']))?>
       </small>
     </p>
     <p><hr style="border: 1px solid #336699"/></p>
    <?php  }  } ?>
</div>
</div>
</div>
