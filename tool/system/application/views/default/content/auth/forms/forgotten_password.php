<h1><?=$heading?></h1><br/>

<div class="column span-24 first last">
<?=form_open($this->uri->uri_string(), array('id' => 'forgotten_password_form'))?>
<table>
<tr>
	<th><p><label for="email"><?=$this->lang->line('FAL_user_email_label')?>:</label></th>
	<td>
	<?=form_input(array('name'=>'email', 
	                       'id'=>'email',
	                       'maxlength'=>'100', 
	                       'size'=>'60',
	                       'value'=>(isset($this->fal_validation) ? $this->fal_validation->{'email'} : '')))?>
    <?=(isset($this->fal_validation) ? $this->fal_validation->{'email'.'_error'} : '')?></p>
	</td>
</tr>
    <!--CAPTCHA (security image)-->
	<?php
	if ($this->config->item('FAL_use_captcha_forgot_password'))
	{?>
<tr>
<th><p><label for="security"><?=$this->lang->line('FAL_captcha_label')?>:</label></td>
<td>
	<?=form_input(array('name'=>'security', 
	                       'id'=>'security',
	                       'maxlength'=>'45', 
	                       'size'=>'45',
	                       'value'=>''))?>
    <?=(isset($this->fal_validation) ? $this->fal_validation->{'security'.'_error'} : '')?>
    <?=$this->load->view($this->config->item('FAL_captcha_img_tag_view'), null, true)?></p>
</td>
</tr>
    <?php }?>
    <!-- END CAPTCHA (security image)-->
</table>
	<p><?=form_submit(array('name'=>'submit', 
	                     'value'=>$this->lang->line('FAL_submit')))?>
 </p>
<?=form_close()?>
</div>
