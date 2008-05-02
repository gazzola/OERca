<?php	
$action_types = array('Fair Use'=>'Fair Use', 'Search'=>'Search',
						          'Commission'=>'Commission', 'Permission'=>'Permission',
						          'Retain'=>'Retain', 'Remove'=>'Remove');

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
			<th>Ask Instructor:</th>
			<td colspan="2">
			  <?php 
				  $yes = ($obj['ask']=='yes') ? true : false;
				  $no = ($obj['ask']=='yes') ? false : true;
				  echo form_radio('ask', 'yes', $yes, 'class="do_object_update"').'&nbsp;Yes&nbsp;'; 
				  echo form_radio('ask', 'no', $no, 'class="do_object_update"').'&nbsp;No&nbsp;&nbsp;';
					if ($yes) {
						echo '<b>Status:&nbsp;'.$ask_status[$obj['ask_status']].'</b>';
						echo '&nbsp;&nbsp;<small>(<a target="_top" href="'.site_url("materials/askforms/$cid/$mid").'">view ASK form</a>)</small>';
			  	}
			?>
 	    	</td>
		</tr>
			<tr>
				<th>Recommanded Action:</th>
				<td>
			  <?php echo form_dropdown('action_type', 
				  				$action_types, $obj['action_type'] ,'id="action_type" class="do_object_update do_object_action_type"'); ?>
				</td>
				<th>Final Action Taken:</th>
				<td>
				<?php echo form_dropdown('action_taken', 
				  				$action_types, $obj['action_taken'] ,'id="action_taken" class="do_object_update"'); ?>
				</td>
      </tr>
	  <tr>
  		<td colspan='4'>
  			<div id="Fair Use" style="display: <?= ($obj['action_type']=='Fair Use') ? 'block':'none'?>">
  				<p>
					<b>Please provide the dScribe2 with your rationale for the fair use of this content object in the space below</b><br/>
			       	<textarea name="fair_use_rationale" id="fair_use_rationale" rows="10" cols="50" class="do_fair_use_rationale"></textarea>
				</p>
	  		</div>
	  		<div id="Permission" style="display: <?= ($obj['action_type']=='Permission') ? 'block':'none'?>">
  				<p>
					<b>Provide a detailed description of the content object for reference in the permission form.</b><br/>
			       	<textarea name="permission_description" id="permssion_description" rows="10" cols="50" class="do_permission_description"></textarea><br/>
			       	<b>Please provide the contact information for the copyright holder of this content object.</b><br/>
			       	<textarea name="permission_contact" id="permssion_contact" rows="10" cols="50" class="do_permission_contact"></textarea>
				</p>
	  		</div>
	  		<div id="Commission" style="display: <?= ($obj['action_type']=='Commission') ? 'block':'none'?>">
  				<p>
					<b>Please provide the dScribe2 with your rationale for commissioning a re-creation of this content object in the space below.</b><br/>
			       	<textarea name="commission_rationale" id="commission_rationale" rows="10" cols="50" class="do_commission_rationale"></textarea><br/>
			       	<b>Provide a detailed description of the desired re-creation of this content object. Provide as much context as possible to explain what critical features the re-creation should emphasize.</b><br/>
			       	<textarea name="commission_description" id="commission_description" rows="10" cols="50" class="do_commission_description"></textarea>
				</p>
	  		</div>
	  		<div id="Retain" style="display: <?= ($obj['action_type']=='Retain') ? 'block':'none'?>">
  				<p>
					<b>Please provide the dScribe2 with your rationale for retaining this content object in the space below.</b><br/>
			       	<textarea name="retain" id="retain" rows="10" cols="50" class="do_retain"></textarea>
				</p>
	  		</div>
		</td>
      </tr>
      <tr>
			<th>Ask Instructor?</th>
			<td colspan="3">
			  <?php 
				  $yes = ($obj['ask']=='yes') ? TRUE : FALSE;
				  $no = ($obj['ask']=='yes') ? FALSE : TRUE;
				  echo form_radio('ask', 'yes', $yes, 'class="do_object_update do_object_ask_yesno"').'&nbsp;Yes&nbsp;'; 
				  echo form_radio('ask', 'no', $no, 'class="do_object_update do_object_ask_yesno"').'&nbsp;No&nbsp;&nbsp;';
			?>
			<div id="ask_yes" style="display: <?= ($obj['ask']=='yes') ? 'block':'none'?>"> 
				<p>
					<b><?php echo '<a target="_top" href="'.site_url("materials/viewform/ask/$cid/$mid").'">view ASK form</a>'; ?> to see the default questions.</b><br/>
					<b>Please add any additional questions for the instructor in the space below</b><br/>
			       	<textarea name="ask_additional" id="ask_additional" rows="10" cols="50" class="do_ask_additional"></textarea>
				</p>
			</div>
 	    </td>
		</tr>
		<tr>
			<th>Ask dScribe2?</th>
			<td colspan="3">
			  <?php 
				  $yes = ($obj['ask_dscribe2']=='yes') ? true : false;
				  $no = ($obj['ask_dscribe2']=='yes') ? false : true;
				  echo form_radio('ask_dscribe2', 'yes', $yes, 'class="do_object_update do_object_ask_dscribe2_yesno"').'&nbsp;Yes&nbsp;'; 
				  echo form_radio('ask_dscribe2', 'no', $no, 'class="do_object_update do_object_ask_dscribe2_yesno"').'&nbsp;No&nbsp;&nbsp;';
			?>
			<div id="ask_dscribe2_yes" style="display: <?= ($obj['ask_dscribe2']=='yes') ? 'block':'none'?>"> 
				<p>
					<b>Please add any questions for the dScribe2 in the space below.</b><br/>
			       	<textarea name="ask_dscribe2_additional" id="ask_dscribe2_additional" rows="10" cols="50" class="do_ask_dscribe2_additional"></textarea>
				</p>
			</div>
 	    </td>
			</tr>
		</table>
	</div>
</div>
