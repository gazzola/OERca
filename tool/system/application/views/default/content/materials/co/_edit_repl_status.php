<!-- STATUS -->
<table width="100%">
	<tr>
			<th style="vertical-align: top">Ask Instructor if replacement is suitable:</th>
			<td>
			  <?php 
				  $yes = ($repl_obj['ask']=='yes') ? TRUE : FALSE;
				  $no = ($repl_obj['ask']=='yes') ? FALSE : TRUE;
				  $data = array(
            			  	'name'        => 'ask',
              				'id'          => 'ask',
              				'value'       => 'yes',
              				'checked'     => $yes,
              				'class'       => 'do_replacement_update do_replacement_ask_yesno',
            		);
				  echo form_radio($data).'&nbsp;Yes&nbsp;';
				  $data = array(
            			  	'name'        => 'ask',
              				'id'          => 'ask',
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
							<a href="#repl_q_addpanel" onclick="repl_q_ap.setrole('instructor'); repl_q_ap.show();">Ask instructor additional questions</a>&nbsp;&raquo;
							<br/><br/>
							<a href="#replquestions">View answers</a>&nbsp;&raquo;
				</div>
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
</table>


<!-- QUESTIONS -->
<br/>
<h2 style="display:inline">Questions&nbsp;(<small><a href="javascript:void(0);" onclick="repl_q_ap.toggle()">Ask Question</a></small>)</h2>
<br/><br/>
<div id="repl_q_addpanel" style="color:black">
    <label for="replrole">Ask:</label>
    <select name="replrole" id="replrole">
        <option value="instructor">Instructor</option>
        <option value="dscribe2">dScribe2</option>
    </select><br/>

 		<textarea name="repl_question" id="repl_question" cols="50"></textarea>
		<p>
 				<input type="button" value="Save" class="do_add_replacement_question" />
 				<input type="button" value="Cancel" onclick="repl_q_ap.hide()" />
 				<br/><hr style="border: 1px dotted #555"/><br/>
		</p>
</div>

<em style="color:black;">Note: Hold down the shift key to select multiple columns to sort</em>
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
