<?php 
	$this->load->view(property('app_views_path').'/dscribe/dscribe_header.php', $data); 
	//$this->ocw_utils->dump($data);
	$copyholder = ($material['author']=='') ? $course['director'] : $material['author'];
	$action_types = array('remove'=>'remove','replace'=>'replace','commission'=>'commission','permission'=>'permission');
	$filetypes[0] = '--- Select Filetype ---';
	$action_types[0] = '--- Select IP action ---';
	$ip_uses[0] = '--- Select IP Use ---';
	$ip_types[0] = '--- Select IP Type ---';
?>
<input type="hidden" id="cid" name="cid" value="<?=$cid?>" />
<input type="hidden" id="mid" name="mid" value="<?=$material['id']?>" />
<input type="hidden" id="defcopy" name="defcopy" value="<?=$course['director']?>" />

<div id="tool_content">
	<!--<a href="<?=site_url()."dscribe/materials/$cid.html"?>">&laquo; return</a>-->
	<a href="javascript:void(0)" onClick="history.back()">&laquo; return</a>

	<br/>

	<h2>
		<a href="<?=site_url()."dscribe/materials/$cid/edit_material/".$material['id']?>">
			<?= $this->ocw_utils->icon($material['mimetype'])?>&nbsp;&nbsp;<?= $material['name']?>
		</a>
   	</h2>

	<br/>
	Copyright Holder:&nbsp;<input type="text" name="author" id="holder" class="update_material" value="<?= $copyholder ?>" size="30" />
	<br/> <br/>
	Collaborators:&nbsp;<input type="text" name="collaborators" id="collaborator" class="update_material" value="<?= $material['collaborators'] ?>" size="60" /><br><small>(use commas to separate names)</small>

	<br/><br/>
	
	Does this material contain embedded ip objects? 
	<?php if ($material['embedded_ip']==1) { ?>
   		<input type="radio" name="embedded_ip" id="emip_yes" class="update_material" value="1" checked="checked" />&nbsp;Yes
   		<input type="radio" name="embedded_ip" id="emip_no" class="update_material" value="0" />&nbsp;No
	<?php } else { ?>
   		<input type="radio" name="embedded_ip" id="emip_yes" class="update_material" value="1" />&nbsp;Yes
   		<input type="radio" name="embedded_ip" id="emip_no" class="update_material" value="0" checked="checked" />&nbsp;No
	<?php }  ?>

	<br/><br/>

	<fieldset id="fs_emip" <?php if (!$material['embedded_ip']) { echo 'style="display:none"';}?>>
		<legend>IP Objects</legend>
		<small>
			<a href="javascript:void(0);" class="do_show_addpanel">
				<img src="<?= property('app_img').'/add.png'?>" valign="bottom"/>&nbsp; Add IP Object
			</a>
		</small>

   		<div id="ippanel_error" 
			style="color:red; display: none; width: 90%; border: 1px solid #ccc; background-color: #eee; margin-bottom: 10px;"></div>

		<!-- add ipobject panel -->
		<div id="addpanel" class="panel" style="display:none;">
		 <div>
    		<h2>Add New IP Object</h2><br/>
   			<div id="addpanel_error" 
				style="color:red; display: none; width: 90%; border: 1px solid #ccc; background-color: #eee; margin-bottom: 10px;"></div>

        		<table>
         		<tr>
            		<th align="right">Name</th>
            		<td><input type="text" name="name" id="name" size="30"/></td>
         		</tr>
         		<tr>
            		<th align="right">Location</th>
            		<td><input type="text" name="location" id="location" size="30"/></td>
         		</tr>
         		<tr>
            		<th align="right">Type</th>
            		<td><?php echo form_dropdown('ipobject_type_id', $ip_types, 0,'id="ipobject_type_id"'); ?></td>
         		</tr>
         		<tr>
            		<th align="right">SubType</th>
            		<td><input type="text" name="subtype" id="subtype" size="30"/></td>
         		</tr>
         		<tr>
            		<th align="right">File type</th>
            		<td><?php echo form_dropdown('filetype_id', $filetypes, 0,'id="filetype_id"'); ?></td>
         		</tr>
         		<tr>
            		<th align="right">Instructor Use</th>
            		<td><?php echo form_dropdown('instructor_use_id', $ip_uses, 0,'id="instructor_use_id"'); ?></td>
         		</tr>
         		<tr>
            		<th align="right">Student Use</th>
            		<td><?php echo form_dropdown('student_use_id', $ip_uses, 0,'id="student_use_id"'); ?></td>
         		</tr>
         		<tr>
            		<th align="right">Copyright Holder</th>
            		<td><input type="text" name="copyright_holder" id="copyright_holder" size="40px" /></td>
         		</tr> 
         		<tr>
            		<th align="right">Full Citation</th>
            		<td><textarea name="citation" id="citation" cols="40" rows="4"></textarea></td>
         		</tr> 
         		<tr>
            		<th align="right">Publisher</th>
            		<td><input type="text" name="publisher" id="publisher" size="40px" /></td>
         		</tr> 
         		<tr>
            		<th align="right">Action Type</th>
            		<td><?php echo form_dropdown('action_type', $action_types, 0,'id="action_type"'); ?></td>
         		</tr> 
         		<tr>
            		<th align="right">Comments</th>
					<td><textarea name="comments" id="comments" cols="40" rows="4"></textarea></td>
         		</tr> 
        		</table>
    			<input type="button" value="Save" id="do_add_ip" />
    			<input type="button" value="Cancel" class="do_hide_addpanel" />
  		</div>
	  </div>
	  <!-- end add panel -->
	<br/>
	<br/>

	<?php if ($ipobjects != null) { ?>
	<table class="rowstyle-alt no-arrow" width="780px">
		<thead>
		<tr>
			<th><strong>Name</strong></th>
			<th><strong>Location</strong></th>
			<th><strong>Sub Type</strong></th>
			<th><strong>Action</strong></th>
			<th><strong>Comments</strong></th>
			<th><strong>Cleared</strong></th>
			<th><strong>Edit</strong></th>
		</tr>
		</thead>

		<tbody>
		<?php 
			$color = '#eee';
			foreach($ipobjects as $ipobject) { 
				$color = ($color=='#ddd') ? '#eee' : '#ddd';
		?> 
		<tr>
			<td><?= $ipobject['name']?></td>
			<td><small><?= $ipobject['location']?></small></td>
			<td><small><?= $ipobject['subtype']?></small></td>
			<td><small><?= ($ipobject['action_type']=='') ? 'not set': $action_types[$ipobject['action_type']]?></small></td>

			<td>
				 <small> 
					<?php
						if ($ipobject['comments'] != null) { 
							echo $this->ocw_user->username($ipobject['comments'][0]['user_id']).' - '.
								 character_limiter($ipobject['comments'][0]['comments'].'&nbsp;',30);
							echo '<a href="'.site_url()."dscribe/materials/$cid/edit_ip/".$material['id'].'/'.$ipobject['id'].'">more &raquo;</a>'; 
						} else {
							echo 'No comments..&nbsp;&nbsp;';
							echo '<a href="'.site_url()."dscribe/materials/$cid/edit_ip/".$material['id'].'/'.$ipobject['id'].'">add &raquo;</a>'; 
						}
					?>
				</small>
			</td>

			<td align="left">
				<input type="checkbox" class="update_ip_done" 
					    id="dn_itemname_<?=$ipobject['id']?>" value="1" 
					<?php if ($ipobject['done']) { echo 'checked="checked"'; }?>/>
			</td>

			<td align="center" class="options">
				<a href="<?= site_url()."dscribe/materials/$cid/edit_ip/".$material['id'].'/'.$ipobject['id']?>" class="do_show_ipdetails" id="sd_<?=$ipobject['id']?>">
					<img src="<?=property('app_img').'/pencil.png'?>" title="Edit IP Object" />
				</a>&nbsp;&nbsp;&nbsp;

				<?=anchor(site_url("dscribe/materials/$cid/remove_ip/".$material['id']."/remove/".$ipobject['id']), 
					  '<img src="'.property('app_img').'/cross.png" title="Remove IP object" />',
					  array('title'=>'Remove IP object', 'class'=>'confirm'))?>
			</td>
		</tr>
		<?php } ?>
		</tbody>
	</table>
	<?php } ?>
	
	</fieldset>
</div>
