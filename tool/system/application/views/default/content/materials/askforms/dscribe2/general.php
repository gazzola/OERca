<?php 
$count = 1;
$sliders = array();

foreach($cos as $obj) {
  $questions = $obj['questions'];
	$questions = (!is_null($questions) && 
								 isset($questions['dscribe2']) && sizeof($questions['dscribe2'])>0) ? $questions['dscribe2'] : null;

	// some array value might been unset, so need to loop in order to find the first set one
	$found = false;
	for ($i = 0; $i < sizeof($questions) && !$found; $i++)
	{
		if (isset($questions[$i]))
		{
			$fq = $questions[$i];
			$found = true;
		}
	}
?>
<tr>
	<!-- first column -->
	<td valign="top" style="vertical-align:top;"><?=$count?></td>

	<!-- second column -->
	<td valign="top" style="vertical-align:top;">

		<!-- new/unseen questions -->
		<div id="new-col1-<?=$obj['id']?>" style="display: <?=($fq['status']=='in progress') ? 'none':'block'?>;">

			<?php foreach($questions as $question) { ?>
      	<p><strong>"<?=$question['question']?>"</strong></p>
       	<p>
					<small>Please provide an answer or comments to the above question:</small><br/>
					<?= form_textarea($question['ta_data']) ?><br/>
					<?php if ($question['status']<>'new' && $question['modified_by']<>'') { ?>
								<small>Last modified by: <?=$this->ocw_user->username($question['modified_by'])?></small><br/>
					<?php } ?>
       	</p>
       	<p><hr style="border: 1px solid #eee"/></p>
			<?php } ?>

			<!-- save options  -->	
			<br/><br/>
			<p><?php echo form_submit($question['save_data'])?>
				&nbsp;&nbsp;
				<?php echo form_submit($question['send_data']) ?></p>
		</div>

		<!-- saved for later -->
		<div id="inprogress-col1-<?=$obj['id']?>" style="display: <?=($fq['status']=='in progress')?'block':'none'?>;">
			<b>Saved for further editing later</b><br/><br/>
			<input type="button" value="Continue editing" id="open_<?=$obj['id']?>" />
		</div>
	</td>

	<!-- third column -->
	<td style="vertical-align:top">
		<!-- new/unseen questions -->
		<div id="new-col2-<?=$obj['id']?>" style="display: <?=($fq['status']=='in progress') ? 'none':'block'?>;">
			<?php 
				 if ($obj['otype']=='original') { 
						 echo $this->ocw_utils->create_co_img($cid,$mid,$obj['id'],$obj['location'],'orig',false,true,true);
				 } else {
   					 echo $this->ocw_utils->create_co_img($cid,$mid,$obj['id'],$obj['location'],'rep',false,true,true);
				 }
			?>
			<br/><br/>

			<?php 
				$data['obj'] = $obj;
				$this->load->view(property('app_views_path').'/materials/askforms/dscribe2/thirdcol.php', $data); 
			?> 
		</div>

		<!-- saved for later -->
		<div id="inprogress-col2-<?=$obj['id']?>" style="display: <?=($fq['status']=='in progress')?'block':'none'?>;">
			<?php 
				 if ($obj['otype']=='original') { 
						 echo $this->ocw_utils->create_co_img($cid,$mid,$obj['id'],$obj['location'],'orig',true,true,true);
				 } else {
   					 echo $this->ocw_utils->create_co_img($cid,$mid,$obj['id'],$obj['location'],'rep',true,true,true);
				 }
			?>
  		<br/>
		</div>
	</td>
</tr>	

<?php
	// code to show and hide objects when saved for later
	$sliders[] = "var mySlide_newcol1_{$obj['id']} = $('new-col1-{$obj['id']}');
				  var mySlide_newcol2_{$obj['id']} = $('new-col2-{$obj['id']}');
				  var mySlide_inpcol1_{$obj['id']} = $('inprogress-col1-{$obj['id']}');
				  var mySlide_inpcol2_{$obj['id']} = $('inprogress-col2-{$obj['id']}');

				  $('open_{$obj['id']}').addEvent('click', function(e) {
						e = new Event(e);
						mySlide_inpcol1_{$obj['id']}.style.display = 'none';
						mySlide_inpcol2_{$obj['id']}.style.display = 'none';
						mySlide_newcol2_{$obj['id']}.style.display = 'block';
						mySlide_newcol1_{$obj['id']}.style.display = 'block';
						e.stop();
				  });
				  $('close_{$obj['id']}').addEvent('click', function(e) {
						e = new Event(e);
						mySlide_newcol1_{$obj['id']}.style.display = 'none';
						mySlide_newcol2_{$obj['id']}.style.display = 'none';
						mySlide_inpcol1_{$obj['id']}.style.display = 'block';
						mySlide_inpcol2_{$obj['id']}.style.display = 'block';
						e.stop();
				  });";
 	$count++; 
} 
?>

<script type="text/javascript">
window.addEvent('domready', function() {
    <?php foreach($sliders as $slider) { echo $slider."\n"; } ?>
    var myTips = new MooTips($$('.ine_tip'), { maxTitleChars: 50 });
});
</script>
