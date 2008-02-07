<div id="changePassword">
<h1><?=$heading?></h1><br/>
<div class="column span-24 first last">
<?=form_open($this->uri->uri_string(), array('id' => 'change_password_form'))?>
<table>
<tr>
<th><p><label for="user_name"><?=$this->lang->line('FAL_user_name_label');?>:</label></th>
<td>
      <?=form_input(array('name'=>'user_name', 
	                       'id'=>'user_name',
	                       'maxlength'=>'30', 
	                       'size'=>'30',
	                       'value'=>(isset($this->fal_validation) ? $this->fal_validation->{'user_name'} : '')))?>
     <?=(isset($this->fal_validation) ? $this->fal_validation->{'user_name'.'_error'} : '')?>
	</p>
</td>
</tr>
<tr>
<th>  <p><label for="password"><?=$this->lang->line('FAL_old_password_label');?>:</label></th>
<td>
      <?=form_password(array('name'=>'old_password', 
	                       'id'=>'old_password',
	                       'maxlength'=>'16', 
	                       'size'=>'16',
	                       'value'=>''))?>
    	<?=(isset($this->fal_validation) ? $this->fal_validation->{'old_password'.'_error'} : '')?>
      </p>
</td>
</tr>
<tr>
 <th>   <p><label for="new_password"><?=$this->lang->line('FAL_new_password_label');?>:</label></th>
<td>
    <?=form_password(array('name'=>'password', 
	                       'id'=>'password',
	                       'maxlength'=>'16', 
	                       'size'=>'16',
	                       'value'=>''))?>
    	<?=(isset($this->fal_validation) ? $this->fal_validation->{'password'.'_error'} : '')?>
    </p>
</td>
</tr>
<tr>
<th>
      <p><label for="password_confirm"><?=$this->lang->line('FAL_retype_new_password_label');?>:</label>
</th>
<td>
      <?=form_password(array('name'=>'password_confirm', 
	                       'id'=>'password_confirm',
	                       'maxlength'=>'16', 
	                       'size'=>'16',
	                       'value'=>''))?>
    <?=(isset($this->fal_validation) ? $this->fal_validation->{'password_confirm'.'_error'} : '')?>
      </p>
</td>
</tr>
</table>
	<input type="submit" name="Submit" value="<?=$this->lang->line('FAL_submit')?>" class="submit"/>
	<input type="reset" name="Reset" value="<?=$this->lang->line('FAL_reset')?>" />
</form>
</div>
</div><!--END CHANGEPASSWORD DIV-->
