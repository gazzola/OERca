<?php 
	$this->load->view(property('app_views_path').'/dscribe/dscribe_header.php', $data); 
	$comments = $material['comments']; 
	$tags[0] = '-- select --';
	$filetypes[0] = '--- Select Filetype ---';
	$categories[0] = '--- Select category ---';
	$copyholder = ($material['author']=='') ? $course['director'] : $material['author'];
?>
<input type="hidden" id="cid" name="cid" value="<?=$cid?>" />
<input type="hidden" id="mid" name="mid" value="<?=$material['id']?>" />
<input type="hidden" id="defcopy" name="defcopy" value="<?=$course['director']?>" />

<div id="tool_content">

<!--
	<a href="<?=site_url()."dscribe/materials/$cid"?>">&laquo; return</a>
-->
	<a href="javascript:history.go(-1)">&laquo; return</a>
	<h2>
		<a href="<?=site_url()."dscribe/materials/$cid/view/".$material['id']?>">
			<?= $this->ocw_utils->icon($material['mimetype'])?>&nbsp;&nbsp;<?= $material['name']?>
		</a>
   	</h2>

	<br/><br/>

  	<div id="ippanel_error" 
		style="color:red; height: 20px; padding: 5px; display: none; border: 1px solid #ccc; background-color: #eee; margin-bottom: 10px;">
		hello
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
        				Category:<br>
						<input type="text" size="20" name="category" id="category"  value="<?=$material['category']?>" class="update_material"/> <br/>or pick one of these:<br>
						<?php echo form_dropdown('category_new', $categories, 0,'id="category_new" class="update_material"'); ?>
						&nbsp;	
					</td>
				</tr>
        		<tr>
           			<td style="border:none; background-image:none;">
        				Name:<br>
						<input type="text" name="name" id="name" class="update_material" size="40px" 
							   value="<?=$material['name']?>" />
					</td>
				</tr>	
        		<tr>
           			<td style="border:none; background-image:none;">
        				Author:<br>
						<input type="text" name="author" id="author" class="update_material" size="40px" 
							   value="<?=$copyholder?>" />
					</td>
				</tr>	
        		<tr>
           			<td style="border:none; background-image:none;">
        				Collaborators:<br>
						<textarea name="collaborators" id="collaborators" class="update_ip" cols="40" rows="4"><?=$material['collaborators']?></textarea>
					</td>
				</tr>	
        		<tr>
           			<td style="border:none; background-image:none;">
        				Tag:<br>
						<?php echo form_dropdown('selectname_'.$material['id'], $tags,
						 $material['tag_id'],'class="update_tag" id="selectname_'.$material['id'].'"'); ?>
					</td>
				</tr>
         		<tr>
           			<td style="border:none; background-image:none;">
            			File Type:<br/>
					<?php echo form_dropdown('filetype_id', $filetypes,
				  					 $material['filetype_id'], 'class="update_material"'); ?>
					</td>
				</tr>
				</table>
				<br/><br/>
				<!--
				<a href="<?=site_url()."dscribe/materials/$cid/".$material['id']?>">&laquo; return</a>
				-->
				<a href="javascript:history.go(-1)">&laquo; return</a>
			</td>
		

			<td valign="top">
				<small><a href="javascript:void(0);" class="do_show_addpanel"><img src="<?= property('app_img').'/add.png'?>" valign="bottom"/>&nbsp; Add Comment</a></small>
				<br/><br/>
				<div id="addpanel" class="panel" style="display:none;">
				<div>
					<h2>Add Comment:</h2><br/>
			 		<div id="addpanel_error" 
                style="color:red; display: none; width: 90%; border: 1px solid #ccc; background-color: #eee; margin-bottom: 10px;"></div>
			   			<textarea name="comments" id="comments" cols="50" rows="9"></textarea>
			   			<p>
							<input type="button" value="Save" class="do_add_material_comment" />
			   				<input type="button" value="Cancel" class="do_hide_addpanel" />
						</p>
				</div>
				</div>

				<div id="materialcomments">
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
