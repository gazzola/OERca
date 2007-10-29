<?php 
	$this->load->view(property('app_views_path').'/dscribe/dscribe_header.php', $data); 
	$action_types = array('remove'=>'remove','replace'=>'replace','commission'=>'commission','permission'=>'permission');
	$filetypes[0] = '--- Select Filetype ---';
	$action_types[0] = '--- Select IP action ---';
	$ip_uses[0] = '--- Select IP Use ---';
	$ip_types[0] = '--- Select IP Type ---';
	$comments = $ipobject['comments']; 
?>
<input type="hidden" id="cid" name="cid" value="<?=$cid?>" />
<input type="hidden" id="mid" name="mid" value="<?=$material['id']?>" />
<input type="hidden" id="oid" name="oid" value="<?=$ipobject['id']?>" />

<div id="tool_content">
<!--
	<a href="<?=site_url()."dscribe/materials/$cid/view_ip/".$material['id']?>">&laquo; return</a>
-->
	<a href="javascript:history.go(-1)">&laquo; return</a>
	<h2>
		<a href="<?=site_url()."dscribe/materials/$cid/view/".$material['id']?>">
			<?= $this->ocw_utils->icon($material['mimetype'])?>&nbsp;&nbsp;<?= $material['name']?>
		</a>
		&raquo; <span id="ip_name"><?= $ipobject['name'] ?></span>
   	</h2>

	<br/><br/>

  	<div id="ippanel_error" 
		style="color:red; display: none; width: 90%; border: 1px solid #ccc; background-color: #eee; margin-bottom: 10px;">
	</div>

	<table width="780">
		<tr>
			<th width="40%">Information</th>
			<th width="60%">Comments</th>
		</tr>

		<tr>
			<td>
				<table style="border:none;" border="0">
        		<tr>
           			<td style="border:none; background-image:none;">
        				Name:<br>
						<input type="text" name="name" id="name" class="update_ip" size="40px" 
							   value="<?=$ipobject['name']?>" />
					</td>
         		</tr> 
		 		<tr>
           			<td style="border:none; background-image:none;">
        				Location:<br/>
						<input type="text" name="location" id="location" class="update_ip" size="40px" 
								value="<?=$ipobject['location']?>" />
					</td>
         		</tr>
         		<tr>
           			<td style="border:none; background-image:none;">
         				Type:<br/>
						<?php echo form_dropdown('ipobject_type_id', $ip_types,
					 			$ipobject['ipobject_type_id'], 'class="update_ip"'); ?>
					</td>
         		</tr>
         		<tr>
           			<td style="border:none; background-image:none;">
            			SubType:<br/>
						<input type="text" name="subtype" id="subtype" class="update_ip" size="40px" 
								value="<?=$ipobject['subtype']?>" />
					</td>
         		</tr>
         		<tr>
           			<td style="border:none; background-image:none;">
            			File Type:<br/>
					<?php echo form_dropdown('filetype_id', $filetypes,
				  					 $ipobject['filetype_id'], 'class="update_ip"'); ?>
					</td>
				</tr>
         		<tr>
           			<td style="border:none; background-image:none;">
            			Instructor Use:<br/>
					<?php echo form_dropdown('instructor_use_id', $ip_uses,
					  					 $ipobject['instructor_use_id'], 'class="update_ip"'); ?>
					</td>
         		</tr>
         		<tr>
           			<td style="border:none; background-image:none;">
            			Student Use:<br/>
						<?php echo form_dropdown('student_use_id', $ip_uses,
					  					 $ipobject['student_use_id'], 'class="update_ip"'); ?>
					</td>
         		</tr>
         		<tr>
           			<td style="border:none; background-image:none;">
            			Copyright Holder:<br/>
						<input type="text" name="copyright_holder" id="copyright_holder" class="update_ip" 
							size="40px" value="<?=$ipobject['copyright_holder']?>"/>
					</td>
         		</tr> 
         		<tr>
           			<td style="border:none; background-image:none;">
            			Full Citation:<br/>
						<textarea name="citation" id="citation" class="update_ip" cols="40" rows="4"><?=$ipobject['citation']?></textarea>
					</td>
         		</tr> 
         		<tr>
           			<td style="border:none; background-image:none;">
            			Publisher:<br/>
						<input type="text" name="publisher" id="publisher" class="update_ip" size="40px" value="<?=$ipobject['publisher']?>"/>
					</td>
         		</tr> 
         		<tr>
           			<td style="border:none; background-image:none;">
            		Action Type:<br/>
					<?php echo form_dropdown('action_type',$action_types, 
												$ipobject['action_type'],'class="update_ip"'); ?></td>
         		</tr> 
        		</table>
				<br/><br/>
				<!--
				<a href="<?=site_url()."dscribe/materials/$cid/view_ip/".$material['id']?>">&laquo; return</a>
				-->
				<a href="javascript:history.go(-1)">&laquo; return</a>
			</td>



			<td valign="top">
				<small>
            		<a href="javascript:void(0);" class="do_show_addpanel">
           	<img src="<?= property('app_img').'/add.png'?>" valign="bottom"/>&nbsp; Add Comment</a>
		        </small>

				<div id="addpanel" class="panel" style="display:none; width: 300px;">
				<div>
					<h2>Add Comment:</h2><br/>
			 		<div id="addpanel_error" style="color:red; display: none; width: 90%; border: 1px solid #ccc; background-color: #eee; margin-bottom: 10px;"></div>
			   			<textarea name="comments" id="comments" cols="30" rows="5"></textarea>
			   			<p>
							<input type="button" value="Save" class="do_add_ip_comments" />
			   				<input type="button" value="Cancel" class="do_hide_addpanel" />
						</p>
				</div>
				</div>

				<div id="ipcomments">
			<?php if ($comments == null) { ?>
					<p id="nocomments">No comments posted</p>
			<?php } else { foreach($comments as $comment) { ?>
				 <p><?=$comment['comments']?></p>
				 <p>
					<small>by&nbsp;<?=$this->ocw_user->username($comment['user_id'])?>&nbsp;
					<?=strtolower($this->ocw_utils->time_diff_in_words($comment['modified_on']))?>
				 	</small>
				 </p>
			   	  <p><hr noshode style="border: 1px dashed #eee"/><br/></p>
				  
			 <?php  }  } ?>
				</div>
			</td>
		</tr>
	</table>
</div>
