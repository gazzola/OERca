	
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
      			<input type="text" name="location" id="location" size="10" value="<?=$obj['location']?>"/>
						&nbsp;<img src="<?=property('app_img')?>/info.gif" style="margin:0; padding:0" class="ine_tip" title="<?=$loc_tip?>" />
						</td>
				</tr>
				<tr>
					<th><span style="vertical-align:top; color:red">*</span> Source Information:</th>
					<td>
		    		<textarea name="citation" id="citation" style="height: 50px; width: 100%;"><?=$obj['citation']?></textarea>
					</td>
				</tr>
			</table>
	
			<div style="text-align:left; margin-bottom: 10px;" id="originfo-show">	
					<a href="javascript:void(0);" onclick="$('originfo-other').style.display='block';$('originfo-show').style.display='none';$('originfo-hide').style.display='block'">ADD More information (author, keywords, copyright holder) &raquo</a>
			</div>
			<div style="text-align:left; display:none" id="originfo-hide">	
					<a href="javascript:void(0);" onclick="$('originfo-other').style.display='none';$('originfo-hide').style.display='none';$('originfo-show').style.display='block'">Hide &raquo;</a>
			</div>

<!-- HIDE THESE -->
<div id="originfo-other" style="display: none">
			<table style="border:none" width="100%">
				<tr>
					<th width="50%">Author:</th>
					<td width="50%">
      			<input type="text" name="author" id="author" size="50" value="<?=$obj['author']?>"/>
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
		    		<input type="text" name="description" id="description" size="50" value="<?=$obj['description']?>" />
					</td>
				</tr>
				<tr>
					<th style="vertical-align: top">Keywords:</th>
					<td>
		    		<input type="text" name="tags" id="tags" size="50" value="<?=$obj['tags']?>" />
					</td>
				</tr>
			
			<?php $this->load->view(property('app_views_path').'/materials/co/_edit_orig_copy.php', $data); ?> 

			</table>
</div>
