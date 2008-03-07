<?php
$copy_status = array('unknown'=>'Unknown', 'copyrighted'=>'Copyrighted','public domain'=>'Public Domain');
$copy = $repl_obj['copyright'];
$cp_status = ($copy==null) ? '' : $copy['status'];
$cp_holder = ($copy==null) ? '' : $copy['holder'];
$cp_notice = ($copy==null) ? '' : $copy['notice'];
$cp_url = ($copy==null) ? '' : $copy['url'];
?>
<div id="Replacement" class="mootabs_panel">

	<?php if ($this->ocw_utils->replacement_exists("c$cid.m$mid.o{$obj['id']}")) { ?>
  <!-- INFORMATION -->
  <div class="column span-17 first last">
    <br/><h3>Information</h3>
		<table style="border:none" width="100%">
        <tr>
            <th>Location:</th>
            <td>
            <input type="text" name="location_<?=$repl_obj['id']?>" id="location" size="50" value="<?=$repl_obj['location']?>" class="do_replacement_update"/>
            </td>
        </tr>
        <tr>
          <th>Author:</th>
          <td>
            <input type="text" name="author_<?=$repl_obj['id']?>" id="author" size="50" value="<?=$repl_obj['author']?>" class="do_replacement_update"/>
          </td>
        </tr>
        <tr>
          <th>Contributor:</th>
          <td>
            <input type="text" name="contributor_<?=$repl_obj['id']?>" id="contributor" size="50" value="<?=$repl_obj['contributor']?>" class="do_replacement_update"/>
          </td>
        </tr>
        <tr>
          <th style="vertical-align:top">Citation:</th>
          <td>
            <textarea name="citation_<?=$repl_obj['id']?>" id="citation" cols="6" rows="1" class="do_replacement_update"><?=$repl_obj['citation']?></textarea>
          </td>
        </tr>
        <tr>
          <th style="vertical-align: top">Description:</th>
          <td>
            <textarea name="description_<?=$repl_obj['id']?>" id="description" cols="6" rows="1" class="do_replacement_update"><?=$repl_obj['description']?></textarea>
          </td>
        </tr>
        <tr>
          <th style="vertical-align: top">Keywords:</th>
          <td>
            <textarea name="tags_<?=$repl_obj['id']?>" id="tags" cols="6"  class="do_replacement_update"><?=$repl_obj['tags']?></textarea>
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
            <?php echo form_dropdown('copy_status_'.$repl_obj['id'],
                  $copy_status, $cp_status ,'id="copy_status" class="do_replacement_cp_update"'); ?>
          </td>
        </tr>
        <tr>
          <th style="vertical-align: top">Copy Holder:</th>
          <td>
            <input type="text" name="copy_holder_<?=$repl_obj['id']?>" id="copy_holder" size="50" value="<?=$cp_holder?>" class="do_replacement_cp_update"/>
          </td>
        </tr>
        <tr>
          <th style="vertical-align: top">Copy Info URL:</th>
          <td>
            <input type="text" name="copy_url_<?=$repl_obj['id']?>" id="copy_url" size="50" value="<?=$cp_url?>" class="do_replacement_cp_update"/>
          </td>
        </tr>
        <tr>
          <th style="vertical-align: top">Copy Notice:</th>
          <td>
            <textarea name="copy_notice_<?=$repl_obj['id']?>" id="copy_notice" cols="10"  class="do_replacement_cp_update"><?=$cp_notice?></textarea>
          </td>
        </tr>
    </table>
</div>


  <!-- Status -->
  <div class="column span-17 first last">
    <br/><h3>Status</h3>
		<table width="100%">
			<tr>
				<th>Ask Instructor:</th>
				<td colspan="2">
			  	<?php 
				  	$yes = ($repl_obj['ask']=='yes') ? true : false;
				  	$no = ($repl_obj['ask']=='yes') ? false : true;
				  	echo form_radio('ask_'.$repl_obj['id'], 'yes', $yes, 'class="do_replacement_update"').'&nbsp;Yes&nbsp;'; 
				  	echo form_radio('ask_'.$repl_obj['id'], 'no', $no, 'class="do_replacement_update"').'&nbsp;No';
			  	?>
				</td>
 	    </tr>
			<tr>
	    	<th>Instructor approves of image?</th>
				<td colspan="2">
			  <?php 
				  if ($repl_obj['suitable']=='yes') { 
              echo 'Yes'; 
          } elseif ($repl_obj['suitable']=='no') {
		        echo 'No<br/><br/>Reason:<br/><p>'.$repl_obj['unsuitable_reason'].'</p>';
          } else {
           	echo 'Waiting on response';
         }
        ?>
      </td>
			</tr>
	 </table>
</div>

<!-- Questions -->
<div class="column span-17 first last">
  	<br/><h3>Questions</h3>
					<small>
						<a href="javascript:void(0);" onclick="repl_q_ap.toggle()">Add questions</a>
  					<br/>
					</small>
					
					<div id="repl_q_addpanel">
   					<textarea name="repl_question" id="repl_question" cols="50"></textarea>
   					<p>
     				<input type="button" value="Save" class="do_add_replacement_question" />
     				<input type="button" value="Cancel" onclick="repl_q_ap.hide()" />
     				<br/><hr style="border: 1px dotted #555"/><br/>
   					</p>
  				</div>
				
					<div class="clear"><br/></div>
				
				  <div id="replqs">
  					<?php if ($repl_obj['questions'] == null) { ?>
				 			<p id="noquestions">No questions posted</p>
						<?php } else { foreach($repl_obj['questions'] as $question) { ?>
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
		<a href="javascript:void(0);" onclick="repl_com_ap.toggle();">Add Comment</a>
	</small>

  <br/>

	<div id="repl_com_addpanel">
   	<textarea name="repl_comments" id="repl_comments" cols="50"></textarea>
   	<p>
     <input type="button" value="Save" class="do_add_replacement_comment" />
     <input type="button" value="Cancel" onclick="repl_com_ap.hide()" />
     <br/><hr style="border: 1px dotted #555"/><br/>
   	</p>
  </div>

	<div class="clear"><br/></div>

  <div id="replcomments">
  <?php if ($repl_obj['comments'] == null) { ?>
     <p id="nocomments">No comments posted</p>
  <?php } else { foreach($repl_obj['comments'] as $comment) { ?>
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

 	<div id="replacementlog">
	<br/>
    <?php if ($repl_obj['log'] == null) { ?>
     <p id="nocomments">No log items.</p>
    <?php } else { foreach($repl_obj['log'] as $l) { ?>
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
<?php } ?>

<!-- Uploads -->
<div class="column span-17 first last">
  <br/><h3 id="upload">Upload replacement</h3>

			<form action="<?=site_url("materials/update_object/$cid/$mid/{$obj['id']}/rep")?>" enctype="multipart/form-data" id="add_ip_rep" method = "post">
			<b>New Replacement Image:</b>
			<div class="formField">
      			<input type="file" name="userfile" id="userfile" size="30" />
		        <small style="color:red">NB: any existing replacement image will be overwritten</small>	
      </div>
			<div class="formField">
      			<input type="submit" name="submit" id="submit" value="Upload" />
      </div>
			</form>
</div>

</div><!-- end of replacement -->