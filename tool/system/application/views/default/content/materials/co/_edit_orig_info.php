	
			<span style="color:red">* Required information</span><br/>

<!-- INFORMATION -->
			<table style="border:none" width="100%">
				<tr>
					<th width="50%"><span style="color:red">*</span> Content Type:</th>
	    			<td width="50%"><?=$types?></td>
				</tr>
				<tr>
						<th><span style="color:red">*</span> Location in material:</th>
	    			<td>
      			<input type="text" name="location" id="location" size="10" value="<?=$obj['location']?>" class="do_object_update"/>
						&nbsp;<img src="<?=property('app_img')?>/info.gif" style="margin:0; padding:0" class="ine_tip" title="<?=$loc_tip?>" />
						</td>
				</tr>
				<tr>
					<th><span style="vertical-align:top; color:red">*</span> Source Information:</th>
					<td>
		    		<textarea name="citation" id="citation" class="do_object_update" style="height: 50px; width: 100%;"><?=$obj['citation']?></textarea>
					</td>
				</tr>
				<tr>
					<th style="vertical-align: top">Keywords:</th>
					<td>
		    		<input type="text" name="tags" id="tags" size="50" value="<?=$obj['tags']?>" class="do_object_update"/>
					</td>
				</tr>
			</table>
	
<!-- HIDE THESE -->
			<dl>	
			<dt class="accordion_toggler_2">Add more information (author, copyright, etc.)</dt>
			<dd class="accordion_content_2">
				<table style="border:none" width="100%">
					<tr>
						<th width="50%">Author:</th>
						<td width="50%">
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
						<th style="vertical-align: top">Description:</th>
						<td>
   						<input type="text" name="description" id="description" size="50" value="<?=$obj['description']?>" class="do_object_update"/>
						</td>
					</tr>
				</table>
				<?php $this->load->view(property('app_views_path').'/materials/co/_edit_orig_copy.php', $data); ?>
			</dd>
			</dl>
