<?php	
// TODO: Narrow down pulldown choices using on select events in previous
// menu
?>

<div id="pane_coinfo" class="editpane">
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

<div class="column span-21 firstlast" style="margin-top:50px; 
  text-align:right;">
    <input type="button" value="Close" id="do_close_courseinfo_pane"/>
  </div>
</div>