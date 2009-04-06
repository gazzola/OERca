  <!-- COPYRIGHT -->
 	
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
