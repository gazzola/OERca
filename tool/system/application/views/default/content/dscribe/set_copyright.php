<?php $this->load->view(property('app_views_path').'/dscribe/dscribe_header.php', $data); ?>

<div id="tool_content">
	<h3><?php echo $this->lang->line('ocw_ds_copy_header')?>:</h3>

	<form name="adminform" method="post" onsubmit="return isFormValid();" 
		  action="<?php echo site_url("dscribe/copyright/$cid")?>" style="margin:0px;">

		<input type="hidden" name="task" value="update_copy" />

		<table>
			<tr>
				<th style="text-align:right">
					<?php echo $this->lang->line('ocw_ins_dscribes_name')?>: &nbsp;&nbsp;
				</th>
				<td>
					<input type="text" required="1" name="copyholder" size="40" 
						   value="<?=$course['director']?>" tabindex="1" />
				</td>
				<td>
					<input type="submit" name="update_copy" value="<?php echo $this->lang->line('ocw_update')?>" />
				</td>
			</tr>
		</table>
	</form>
	<br/>
	<p style="font-size: small">
		<?php echo $this->lang->line('ocw_ds_copy_introtext')?><br/><br/>
		<?php echo '<img src="'.property('app_img').'/cc_attr.gif" />' ?>
		<?php echo '<img src="'.property('app_img').'/cc_nc.gif" />' ?>
		<?php echo '<img src="'.property('app_img').'/cc_sa.gif" />' ?>
	</p>
</div>
