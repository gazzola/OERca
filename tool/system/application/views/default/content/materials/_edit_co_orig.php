<?php	
$action_types = array('Fair Use'=>'Fair Use', 'Search'=>'Search',
						          'Commission'=>'Commission', 'Permission'=>'Permission',
						          'Retain'=>'Retain', 'Remove'=>'Remove');

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
					<th style="vertical-align: top">Copy Status:</th>
					<td>
			  		<?php echo form_dropdown('copy_status_'.$obj['id'], 
				  				$copy_status, $cp_status ,'id="copy_status" class="do_object_cp_update"'); ?>
					</td>
				</tr>
				<tr>
					<th style="vertical-align: top">Copy Holder:</th>
					<td>
      			<input type="text" name="copy_holder_<?=$obj['id']?>" id="copy_holder" size="50" value="<?=$cp_holder?>" class="do_object_cp_update"/>
					</td>
				</tr>
				<tr>
					<th style="vertical-align: top">Copy Info URL:</th>
					<td>
      			<input type="text" name="copy_url_<?=$obj['id']?>" id="copy_url" size="50" value="<?=$cp_url?>" class="do_object_cp_update"/>
					</td>
				</tr>
				<tr>
					<th style="vertical-align: top">Copy Notice:</th>
					<td>
		    		<textarea name="copy_notice_<?=$obj['id']?>" id="copy_notice" cols="10"  class="do_object_cp_update"><?=$cp_notice?></textarea>
					</td>
				</tr>
    </table>
</div>



<!-- STATUS -->
<div class="column span-17 first last">
  <br/><h3>Status</h3>
	<table width="100%">
		<tr>
			<th>Cleared:</th>
			<td colspan="2">
			  <?php 
				  $yes = ($obj['done']=='1') ? true : false;
				  $no = ($obj['done']=='1') ? false : true;
				  echo form_radio('done', '1', $yes, 'class="do_object_update"').'&nbsp;Yes&nbsp;'; 
				  echo form_radio('done', '0', $no, 'class="do_object_update"').'&nbsp;No';
			  ?>
 	    </td>
		</tr>
		<tr>
			<th>Ask Instructor:</th>
			<td colspan="2">
			  <?php 
				  $yes = ($obj['ask']=='yes') ? true : false;
				  $no = ($obj['ask']=='yes') ? false : true;
				  echo form_radio('ask', 'yes', $yes, 'class="do_object_update"').'&nbsp;Yes&nbsp;'; 
				  echo form_radio('ask', 'no', $no, 'class="do_object_update"').'&nbsp;No';
			  ?>
 	    </td>
			</tr>
			<tr>
				<th colspan="2">Action:</th>
				<td>
			  <?php echo form_dropdown('action_type', 
				  				$action_types, $obj['action_type'] ,'id="action_type" class="do_object_update"'); ?>
 	    	</td>
			</tr>
			<tr>
				<th colspan="2">Action Taken:</th>
				<td>
	      	<input type="text" name="action_taken" id="action_taken" size="30" value="<?=$obj['action_taken']?>" class="do_object_update"/>
				</td>
      </tr>
			<tr>
				<th>Questions:<br/>
					<small>
						<a href="javascript:void(0);" style="color:orange" class="do_show_hide_panel">Add questions</a>
  					<br/>
					</small>
					<div id="addpanel" style="display:none">
   					<textarea name="question" id="question" cols="50"></textarea>
   					<p>
     				<input type="button" value="Save" class="do_add_object_question" />
     				<input type="button" value="Cancel" class="do_show_hide_panel" />
     				<br/><hr style="border: 1px dotted #555"/><br/>
   					</p>
  				</div>
				</th>
  			<?php if ($questions == null) { ?>
				<td colspan="2"> 
     			<p id="noquestions">No questions posted</p>
				</td>
  			<?php } else { foreach($questions as $question) { ?>
				<td>
     			<p><b><?=$question['question']?><b></p>
     			<p>
        		<small>by&nbsp;<?=$this->ocw_user->username($question['user_id'])?>&nbsp;
        <?=strtolower($this->ocw_utils->time_diff_in_words($question['modified_on']))?>
        		</small>
     			</p>
     			<p><hr style="border: 1px dashed #eee"/></p>
				</td>
				<td>
     			<p><?=$question['answer']?></p>
     			<p><hr style="border: 1px dashed #eee"/></p>
				</td>
   			<?php  }  } ?>
			</tr>
		</table>
</div>

<!-- COMMENTS -->
<div class="column span-17 first last">
  <br/><h3>Comments</h3>

	<small>
		<a href="javascript:void(0);" class="do_show_hide_panel">Add Comment</a>
	</small>

  <br/>

	<div id="addpanel" style="display:none">
   <textarea name="comments" id="comments" cols="50"></textarea>
   <p>
     <input type="button" value="Save" class="do_add_object_comment" />
     <input type="button" value="Cancel" class="do_show_hide_panel" />
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
