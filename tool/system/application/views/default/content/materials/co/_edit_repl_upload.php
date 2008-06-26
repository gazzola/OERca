<!-- Uploads -->
<div class="column span-14 first last" style="margin-left: 10px;color:black">
  		<br/><h3 id="upload">Upload Replacement</h3>
			<form action="<?=site_url("materials/update_object/$cid/$mid/{$obj['id']}/rep")?>" enctype="multipart/form-data" id="add_ip_rep" method = "post">
			<b>New Replacement Image:</b>
			<div class="formField">
      			<input type="file" name="userfile_0" id="userfile_0" size="30" />
						<input type="hidden" name="location" value="<?=$obj['location']?>" />
						<input type="hidden" name="question" value="" />
						<input type="hidden" name="comment" value="" />
						<input type="hidden" name="copyurl" value="" />
						<input type="hidden" name="copynotice" value="" />
						<input type="hidden" name="copyholder" value="" />
						<input type="hidden" name="copystatus" value="" />		       
      </div>
			<br class="clear"/>
		 	<small>NB: any existing replacement image will be overwritten</small>	
				<br/><br/>	
      <input type="submit" name="submit" id="submit" value="Upload" />
      <?php if (isset($alert_wrong_mimetype) &&  $alert_wrong_mimetype != '')
      {
      		echo '<p class="error">Please only use image file for replacement. </p>';
      }
      ?>  		
			</form>
</div>
