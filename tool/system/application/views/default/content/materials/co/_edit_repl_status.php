<!-- STATUS -->
<table width="100%">
  <tr>
			<th style="vertical-align: top">Ask Instructor/dScribe2 if replacement is suitable?</th>
			<td>
			  <?php 
				  $yes = ($repl_obj['ask']=='yes') ? TRUE : FALSE;
				  $no = ($repl_obj['ask']=='yes') ? FALSE : TRUE;
				  $data = array(
            			  	'name'        => 'ask',
              				'id'          => 'ask_yes',
              				'value'       => 'yes',
              				'checked'     => $yes,
              				'class'       => 'do_replacement_update do_replacement_ask_yesno',
            		);
				  echo form_radio($data).'&nbsp;Yes&nbsp;';
				  $data = array(
            			  	'name'        => 'ask',
              				'id'          => 'ask_no',
              				'value'       => 'no',
              				'checked'     => $no,
              				'class'       => 'do_replacement_update do_replacement_ask_yesno',
            		);
				  echo form_radio($data).'&nbsp;No&nbsp;&nbsp;';
				?>
				<div id="repl_ask_yes" style="display: <?= ($repl_obj['ask']=='yes') ? 'block':'none'?>"> 
							<br/><br/>
							<a target="_new" href="<?=site_url("materials/askforms/$cid/$mid/replacement/instructor")?>">View ASK form</a> to see the default questions.
							<br/><br/>
 	    </td>
	</tr>
	
	<tr>
	   	<th style="vertical-align: top">Instructor approves of image?</th>
			<td>
					  <?php 
						  if ($repl_obj['suitable']=='yes') { 
		              echo 'Yes, instructor approves of this image as a substitute.'; 
		          } elseif ($repl_obj['suitable']=='no') {
				        echo 'No<br/><br/>Reason:<br/><p>'.$repl_obj['unsuitable_reason'].'.</p>';
		          } else {
		           	echo 'Waiting on response.';
		          }
		         ?>
		   </td>
	</tr>
	
  <tr>
  <th>Ask dScribe2 a general question about the Replacement Content Object?</th>
  <td>
	  <?php 
		  $data = array(
        			  	'name'        => 'dscribe2_repl_ask_q',
          				'id'          => 'dscribe2_repl_ask_q_yes',
          				'value'       => 'yes',
          				'class'       => 'do_dscribe2_replacement_ask_q',
        		);
		  echo form_radio($data).'&nbsp;Yes&nbsp;';
		  $data = array(
        			  	'name'        => 'dscribe2_repl_ask_q',
          				'id'          => 'dscribe2_repl_ask_q_no',
          				'value'       => 'no',
          				'class'       => 'do_dscribe2_replacement_ask_q',
        		);
		  echo form_radio($data).'&nbsp;No&nbsp;&nbsp;';
		?>
		<div id="dscribe2_repl_ask_q_pane" style="display: <?= ($repl_obj['ask']=='yes') ? 'block':'none'?>"> 
		  <p style="padding:5px; background-color:yellow; border:2px solid gray; color:black;display:none" id="repl_question_conf">Sent to dScribe2!</p>
					<br/><br/>
					<a target="_new" href="<?=site_url("materials/askforms/$cid/$mid/general/dscribe2")?>">View dScribe2 ASK form</a>
					<br/><br/>
					<div>
              <input type="hidden" name="replrole" id="replrole" value="dscribe2">
              <textarea name="repl_question" id="repl_question" style="width: 100%; height: 50px;"></textarea>
          		<p>
           				<input type="button" value="Send to dScribe2" class="do_add_replacement_question" />
          		</p>
          </div>
		</div>
  </td>
</tr>
</table>

<table id="replquestions" class="sortable-onload-7-reverse rowstyle-alt no-arrow" width="100%">
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

<tbody id="replqs">
<?php
if ($repl_obj['questions'] == null) { ?>
    <tr id="noreplquestions"><td colspan="7">No questions posted.</td></tr>
<?php } else {
    foreach($repl_obj['questions'] as $askee => $qs) {
      foreach($qs as $question) {
?>
  <tr>
    <td><?=ucfirst($askee)?></td>
    <td><?=$question['question']?></td>
    <td><?=($question['answer']=='') ? 'No answer' : $question['answer'] ?></td>
    <td><?=$this->ocw_user->username($question['user_id'])?></td>
    <td><?= ($this->ocw_user->username($question['modified_by'])) ? $this->ocw_user->username($question['modified_by']):''?></td>
    <td><?=mdate('%d %M, %Y %H:%i',mysql_to_unix($question['created_on']))?></td>
    <td><?=mdate('%d %M, %Y %H:%i',mysql_to_unix($question['modified_on']))?></td>
  </tr>
<?php }}} ?>
</tbody>
</table>
