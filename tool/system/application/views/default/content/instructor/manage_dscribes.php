<?php $this->load->view(property('app_views_path').'/instructor/instructor_header.php', $data); ?>

<p class="error">Under construction: A place to add and remove dscribes from the course</p>

<!--
<p><?php echo $this->lang->line('ocw_ins_dscribes_intro')?></p>

<br>
<fieldset>
		<legend><?php echo $this->lang->line('ocw_ins_dscribes_addadscribe')?></legend>

		<form name="adminform" method="post" action="<?php echo site_url("instructor/dscribes/$cid")?>" style="margin:0px;">

		<input type="hidden" name="task" value="add_dscribe" />
		<input type="hidden" name="level" value="dscribe1" />

		<table>
			<tr>
				<th style="text-align:right">
					<?php echo $this->lang->line('ocw_ins_dscribes_name')?>: &nbsp;&nbsp;
				</th>
				<td><input type="text"  name="name" tabindex="1" size="20" /></td>
			</tr>
			<tr>
				<th style="text-align: right">
					<?php echo $this->lang->line('ocw_ins_dscribes_email')?>: &nbsp;&nbsp;
				</th>
				<td>
					<input type="text" id="email" name="email" tabindex="2" size="20" />
				</td>
			</tr>
			<tr>
				<th style="text-align:right">
					<?php echo $this->lang->line('ocw_ins_dscribes_username')?>: &nbsp;&nbsp;
				</th>
				<td><input type="text" id="username" name="username" tabindex="3" size="20" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<input id="submitbutton" type="submit" 
						   name="add_dscribe" value="<?php echo $this->lang->line('ocw_add')?>" />
				</td>
			</tr>
		</table>
	</form>
	</fieldset>

<br/>

<?php if ($dscribes != null): ?>
<h3><?php echo $this->lang->line('ocw_ins_dscribes_currentds')?></h3>

<table>
<tr> 
	<th><?php echo $this->lang->line('ocw_ins_dscribes_name') ?></th>
	<th><?php echo $this->lang->line('ocw_ins_dscribes_username') ?></th>
	<th><?php echo $this->lang->line('ocw_ins_dscribes_email') ?></th>
	<th><?php echo $this->lang->line('ocw_ins_dscribes_level') ?></th>
	<th>&nbsp;</th>
</tr>
<?php foreach($dscribes as $dscribe): ?>
<tr>
	<td><?php echo $dscribe['name']?></td>
	<td><?php echo $dscribe['user_name']?></td>
	<td><?php safe_mailto($dscribe['email'])?><?php echo $dscribe['email'] ?></td>
	<td><?php echo $dscribe['role']?></td>
	<td>
		<?php echo anchor(site_url('instructor/dscribes/remove/'.$dscribe['id']), 
					  '<img src="'.property('app_img').'/cross.png" title="Remove dScribe" />',
					  array('title'=>'Remove dScribe', 'class'=>'confirm'))?>
    </td>
</tr> 
<?php endforeach; ?> 
</table>
<?php endif; ?>
-->

<?php $this->load->view(property('app_views_path').'/instructor/instructor_footer.php', $data); ?>
