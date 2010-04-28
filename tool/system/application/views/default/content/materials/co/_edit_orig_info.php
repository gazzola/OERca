	
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

