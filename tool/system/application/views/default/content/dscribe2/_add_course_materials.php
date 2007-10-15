	<a href="javascript:void(0);" class="do_show_addpanel"><img src="<?= property('app_img').'/add.png'?>" valign="bottom"/>&nbsp; Add Course Material</a>

	<div id="addpanel" class="panel" style="display:none;">
	<div>
		<h2>Add New Course Material:</h2><br/>
 		<div id="addpanel_error" 
             style="color:red; display: none; width: 90%; border: 1px solid #ccc; background-color: #eee; margin-bottom: 10px;"></div>
		<table>
			<tr>
           		<th align="right">Material Type:</th>
           		<td>
   					<input type="radio" name="mtype" id="mtype" value="item" checked="checked" />&nbsp;item&nbsp;&nbsp;<input type="radio" name="mtype" id="mtype" value="folder" />&nbsp;Folder
				</td>
			</tr>	
			<tr>
           		<th align="right">Category:</th>
				<td>
						<?php echo form_dropdown('category', $categories, 0,'id="category"'); ?>
						&nbsp;	
						<input type="text" size="20" name="new_category" id="new_category" style="display:none;" />
				</td>
			</tr>
			<tr>
           		<th align="right">Name</th>
           		<td>
					<input type="text" size="25" name="mname" id="mname" />
				</td>
			</tr>	
		</table>

		<p>
			<input type="button" value="Add" class="do_add_material" />
  			<input type="button" value="Cancel" class="do_hide_addpanel" />
		</p>
	</div>
	</div>
