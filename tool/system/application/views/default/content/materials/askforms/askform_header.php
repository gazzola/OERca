<?php
echo style('smoothbox.css');
echo style('multiupload.css');
echo script('mootips.js');
echo script('smoothbox.js');
echo script('moo-ipe.js');
echo script('multiupload.js');
?>
<style>
p.txt{ margin: 0; padding: 5px; }
textarea{ font-size: 12px; font-family: Arial, Helvetica, sans-serif; border: 1px solid #888; margin: 0 5px 5px 0; }
</style>

<input type="hidden" id="cid" name="cid" value="<?=$cid?>" />
<input type="hidden" id="mid" name="mid" value="<?=$mid?>" />
<input type="hidden" id="view" name="view" value="<?=$view?>" />

<!-- Header bar -->
<h1 style="margin-bottom: 5px"><?=$course['title']?>&nbsp;&raquo;&nbsp;<?=$material['name']?> Content Objects</h1>
<p style="border-top: 1px solid #ddd; background-color: #fff; padding: 2px 3px 0 0; color: #222">
	<b>Instructor:</b> 
	<?php echo $material['author']; echo ($material['collaborators']<>'') ? ' with '.$material['collaborators'] : ''?> &nbsp;|&nbsp;
	<b>Date: </b><?=mdate('%d %M, %Y',mysql_to_unix($course['start_date'])).' - '.  mdate('%d %M, %Y',mysql_to_unix($course['end_date']))?> &nbsp;|&nbsp;
	<b>Download URL:</b>  <?php if ($material['files'][0]['fileurl'] <> '') { ?>
	<a href="<?=site_url("materials/manipulate/$cid/{$material['id']}")?>"><?= $material['name']?></a>
  <?php } else { ?>
	<span style="color: red">no URL found for resource</span>
  <?php } ?>
</p>

<br/>

<?php if ($role == 'dscribe1' or $role=='dscribe2') { ?>
<div class="column span-24 first last" 
		 style="margin-bottom:20px; padding: 10px; border: 1px solid #ddd; background-color:#eee;">
	<strong>View the Content Objects sent to:</strong>&nbsp;
	<?php echo form_dropdown('questions_to', $select_questions_to, $questions_to, 'id="questions_to"') ?>
</div>
<br/><br/>
<?php } ?>
