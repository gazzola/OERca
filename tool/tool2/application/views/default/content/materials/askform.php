<?php
echo style('smoothbox.css');
echo style('multiupload.css');
echo script('mootips.js');
echo script('smoothbox.js');
echo script('moo-ipe.js');
echo script('multiupload.js');

$sigs = array('high'=>'Very important', 'normal'=>'Important', 'low'=>'Not important'); 
$num_avail = array('provenance'=>$num_prov, 'replacement'=>$num_repl, 'done'=>$num_done);
?>
<style>
.carousel-image { float: left; padding-right: 10px; padding-bottom: 10px;}
p.txt{
	margin: 0;
	padding: 5px;
}
textarea{
	font-size: 12px;
	font-family: Arial, Helvetica, sans-serif;
	border: 1px solid #888;
	margin: 0 5px 5px 0;
}
</style>

<!-- Header -->
<h1 style="margin-bottom: 5px"><?=$course['title']?>&nbsp;&raquo;&nbsp;<?=$material['name']?> Content Objects</h1>
<p style="border-top: 1px solid #ddd; background-color: #fff; padding: 2px 3px 0 0; color: #ddd">
<b>Instructor:</b> 
<?php echo $material['author']; echo ($material['collaborators']<>'') ? ' with '.$material['collaborators'] : ''?> &nbsp;|&nbsp;
<b>Date: </b><?=mdate('%d %M, %Y',mysql_to_unix($course['start_date'])).' - '.  mdate('%d %M, %Y',mysql_to_unix($course['end_date']))?> &nbsp;|&nbsp;
<b>CTools URL:</b>  <?php if ($material['ctools_url'] <> '') { ?>
	<a href="<?=$material['ctools_url']?>"><?= $material['name']?></a>
  <?php } else { ?>
	<span style="color: red">no URL found for resource</span>
  <?php } ?>
</p>

<br/>

<div class="column span-24 first last" style="background-color:#eee;padding:5px;margin-bottom:20px;">
<p>
The following page lists some of the media used in one of your courses.  We are asking about it because we cannot determine its provenance or need your approval for a replacement we intend to use. Please go through the list and for each content object indicate whether or not you created and hold the copyright to the media or, in the 'Replacement' tab, indicate whether you approve or not of the found replacement. 
<br/><br/>
Thanks for your cooperation!
</p>
</div>

<?php if ($numobjects == 0) { ?>

<div class="column span-24 first last"> 
<p class="error">Presently, none of the content objects in this material need copyright clarification.</p>
</div>

<?php } else { ?>
<input type="hidden" id="cid" name="cid" value="<?=$cid?>" />
<input type="hidden" id="mid" name="mid" value="<?=$mid?>" />
<input type="hidden" id="view" name="view" value="<?=$view?>" />

<h2>Content Objects (<?=$numobjects?> items)</h2>

<?php 

$this->load->view(property('app_views_path').'/materials/askform_header.php', $data); 
	
if ($num_avail[$view] == 0) {
	echo '<div class="column span-24 first last"> 
			<p class="error">Presently, none of the content objects in this material fall in this category.</p>
		</div>';
} else {
?>

<div class="column span-24 first last"> 
<table class="rowstyle-alt no-arrow" style="padding: 0">
    <thead>
    <tr>
	<?php if ($view == 'done') { ?>
       	<th>&nbsp;</th>
        <th>Content Object</th>
		<th>Information</th>
	<?php } elseif ($view == 'replacement') { ?>
       	<th>&nbsp;</th>
		<th>Questions</th>
        <th>Original Object</th>
        <th>Replacement Object</th>
	<?php } else { ?>
        <th>&nbsp;</th>
		<th>Questions</th>
        <th>Content Object Information</th>
	<?php } ?>
    </tr>
    </thead>

    <tbody>
		<?php 
			if ($view == 'provenance') {
				$this->load->view(property('app_views_path').'/materials/askform_prov.php', $data); 

			} elseif ($view == 'replacement') {
				$this->load->view(property('app_views_path').'/materials/askform_repl.php', $data); 

			} else {
				$this->load->view(property('app_views_path').'/materials/askform_done.php', $data); 
		    }
		?>
	</tbody>
</table>
</div>

<?php }} ?>
