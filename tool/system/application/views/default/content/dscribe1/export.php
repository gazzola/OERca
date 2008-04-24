<?php $this->load->view(property('app_views_path').'/dscribe1/dscribe1_header.php', $data); ?>
<?php
	echo form_open('dscribe1/do_export', '');
	echo form_hidden('cid', $cid);
?>
<table border="0" cellpadding="0" cellspacing="0">
<tr>
	<td width="40%" style="font-weight: bold; text-align: left; border:1px solid #ccc;padding: 5px;  ">
		Export Course
    </td>
 </tr>
  <tr>
	<td style="font-weight: bold; text-align: left; border:1px solid #ccc;padding: 5px;  ">
		<h4><?php echo $this->lang->line('ocw_ds_export_instruction') ?></h4>
	</td>
  </tr>
</table>

<?php
	echo form_submit('exportButton', 'Export');
	$string = "</div></div>";
	echo form_close($string);
?>
<?php $this->load->view(property('app_views_path').'/dscribe1/dscribe1_footer.php', $data); ?>
