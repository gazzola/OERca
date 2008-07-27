<h2>Content Objects in need of dscribe2 input (<?=$need_input?> items)</h2>
<?php 
	if (isset($alert_missing_dscribe))
	{
		echo '<p class="error">'.$alert_missing_dscribe.'</p>';
	}
 	
 	if ($role=='dscribe2') { ?>
<div class="column span-24 first last" style="padding:0px;margin: 20px 0 20px 0">
	<b>Please respond to the following dScribe questions or action recommendations.  Thanks for your cooperation!</b>
</div>
<?php } ?> 

<div class="column span-24 first last" style="margin-bottom: 10px;">
	<?php 
		$att=' id="active"'; 
		$general = 'General Questions ('.$num_general.')'; 
		$fairuse = 'Fair Use ('.$num_fairuse.')'; 
		$permission = 'Permission ('.$num_permission.')';
		$commission = 'Commission ('.$num_commission.')';
		$retain = 'Retain ('.$num_retain.')';
		$aitems = 'Addressed Items ('.$num_done.')';
	?>
   <div id="navlist">
			<ul id="navlist">
			<li<?=($view=='general')?$att:''?>><?=anchor("/materials/askforms/$cid/$mid/general/dscribe2",$general)?></li>
			<li<?=($view=='fairuse')?$att:''?>><?=anchor("/materials/askforms/$cid/$mid/fairuse/dscribe2",$fairuse)?></li>
			<li<?=($view=='permission')?$att:''?>><?=anchor("/materials/askforms/$cid/$mid/permission/dscribe2",$permission)?></li>
			<li<?=($view=='commission')?$att:''?>><?=anchor("/materials/askforms/$cid/$mid/commission/dscribe2",$commission)?></li>
			<li<?=($view=='retain')?$att:''?>><?=anchor("/materials/askforms/$cid/$mid/retain/dscribe2",$retain)?></li>
			<li<?=($view=='aitems')?$att:''?>><?=anchor("/materials/askforms/$cid/$mid/aitems/dscribe2",$aitems)?></li>
		</ul>
	 </div>
</div>

<br/><br/>

<?php if ($num_avail[$view] == 0) { ?>
	<!--
	<div class="column span-24 first last"> 
			<p class="error">Presently, none of the content objects in this material fall in this category.</p>
	</div>
	-->

<?php } elseif ($view=='aitems') { 	?>

	<div class="column span-24 first last" 
	 		style="margin-bottom:20px; padding: 10px; border: 1px solid #ddd; background-color:#eee;">
			<strong>Response Type:</strong>&nbsp;
			<?php echo form_dropdown('response_type', $select_response_types, $response_type, 'id="response_type"') ?>
	</div>

<?php  } ?>
