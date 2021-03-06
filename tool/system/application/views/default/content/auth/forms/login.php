<h1><?=$heading?></h1><br/>

<div class="column span-24 first last">

<?=isset($this->fal_validation->login_error_message) ? $this->fal_validation->login_error_message : ''?>
<?=form_open($this->uri->uri_string(), array('id' => 'login_form'))?>
<!--USERNAME-->
<table>
<tr>
<th><p><label for="user_name"><?=$this->lang->line('FAL_user_name_label')?>:</label></th>
<td>
	<?=form_input(array('name'=>'user_name', 
	                       'id'=>'user_name',
	                       'maxlength'=>'30', 
	                       'size'=>'30',
	                       'value'=>''))?>
    <?=(isset($this->fal_validation) ? $this->fal_validation->{'user_name'.'_error'} : '')?>
   </p>
</td>
</tr>

<tr>
    <!--PASSWORD-->
<th><p><label for="password"><?=$this->lang->line('FAL_user_password_label')?>:</label></th>
<td>
	<?=form_password(array('name'=>'password', 
	                       'id'=>'password',
	                       'maxlength'=>'30', 
	                       'size'=>'30',
	                       'value'=>''))?>
    <?=(isset($this->fal_validation) ? $this->fal_validation->{'password'.'_error'} : '')?>
    <span class="note"><?=anchor($this->config->item('FAL_forgottenPassword_uri'), $this->lang->line('FAL_forgotten_password_label'))?></span></p>	
</td>
</tr>
    <!--CAPTCHA (security image)-->
	<?php
	if ($this->config->item('FAL_use_captcha_login'))
	{?>
<tr>
<td><p><label for="security"><?=$this->lang->line('FAL_captcha_label')?>:</label></td>
<td>
	<?=form_input(array('name'=>'security', 
	                       'id'=>'security',
	                       'maxlength'=>'30', 
	                       'size'=>'30',
	                       'value'=>''))?>
	<br/>
    <?=(isset($this->fal_validation) ? $this->fal_validation->{'security'.'_error'} : '')?>
    <?=$this->load->view($this->config->item('FAL_captcha_img_tag_view'), null, true)?></p>
</td>
    <?php }?>
    <!-- END CAPTCHA (security image)-->
 <tr>
</table>
	<p><label>
	<?=form_submit(array('name'=>'login', 
	                     'id'=>'login', 
	                     'value'=>$this->lang->line('FAL_login_label')))?>
	</label></p>
	<p>
    <?php 
		if ($this->config->item('FAL_allow_user_registration')) {
	 		  echo anchor($this->config->item('FAL_register_uri'), $this->lang->line('FAL_register_label'));
	   } else  { echo nbs(3); } 
	?>
	</p>
<?=form_close()?>

</div>
