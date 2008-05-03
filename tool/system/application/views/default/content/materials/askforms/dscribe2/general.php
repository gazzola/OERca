<?php 
$count = 1;
$sliders = array();

foreach($cos as $obj) {
  			$questions = $obj['questions'];
				$fq = $obj['questions'][0]; // get first question
?>
<tr>
	<!-- first column -->
	<td valign="top" style="vertical-align:top;"><?=$count?></td>

	<!-- second column -->
	<td valign="top" style="vertical-align:top;">

		<!-- new/unseen questions -->
		<div id="new-col1-<?=$obj['id']?>" style="display: <?=($fq['status']=='in progress') ? 'none':'block'?>;">

			<?php foreach($questions as $question) { ?>
      	<p><strong><?=$question['question']?></strong></p>
       	<p>
					<small>Please provide an answer or comments to the above question:</small><br/>
					<?= form_textarea($question['ta_data']); ?>
       	</p>
       	<p><hr style="border: 1px solid #eee"/></p>
			<?php } ?>

			<!-- save options  -->	
			<br/><br/>
			<p><?= form_submit($question['save_data']) ?>&nbsp;&nbsp;<?= form_submit($question['send_data']) ?></p>
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
						 echo $this->ocw_utils->create_co_img($cid,$mid,$obj['id'],$obj['location'],false,false);
				 } else {
   					 echo $this->ocw_utils->create_corep_img($cid,$mid,$obj['id'],$obj['location'],false,false);
				 }
			?>
			<br/><br/>

				<?php if ($obj['otype']=='original') { ?>
					<b>Content-Type:</b> <?=$this->coobject->get_subtype_name($obj['subtype_id'])?><br/><br/>
				<?php } ?>

				<b>Description:</b> 
				<?php if ($obj['description']=='') { ?><span style="color:red">No description</span>
				<?php } else { echo $obj['description']; }?><br/><br/>

				<b>Author:</b> 
				<?php if ($obj['author']=='') { ?><span style="color:red">No author</span>
				<?php } else { echo $obj['author']; }?><br/><br/>

				<b>Contributor:</b> 
				<?php if ($obj['contributor']=='') { ?><span style="color:red">No contributor</span>
				<?php } else { echo $obj['contributor']; }?><br/><br/>

				<b>Citation:</b> 
				<?php if ($obj['citation']=='') { ?><span style="color:red">No citation</span>
				<?php } else { echo $obj['citation']; }?><br/><br/>

				<?php if (is_array($obj['copyright'])) { $c = $obj['copyright'];?>
						<b>Copyright Status:</b> <?=$c['status']?><br/>
						<b>Copyright Holder:</b> <?=$c['holder']?><br/>
						<b>Copyright Info URL:</b> <?=$c['url']?><br/>
						<b>Copyright Notice:</b> <?=$c['notice']?><br/>
				<?php } else { ?>
						<b>Copyright:</b> <span style="color:red">No copyright information</span>
				<?php } ?><br/><br/>

				<?php if ($obj['otype']=='original') { ?>
						<b>Action Taken:</b> 
						<?php if ($obj['action_taken']=='') { ?><span style="color:red">No action</span>
						<?php } else { echo $obj['action_taken']; }}?><br/><br/>
		</div>

		<!-- saved for later -->
		<div id="inprogress-col2-<?=$obj['id']?>" style="display: <?=($fq['status']=='in progress')?'block':'none'?>;">
			<?php 
				 if ($obj['otype']=='original') { 
						 echo $this->ocw_utils->create_co_img($cid,$mid,$obj['id'],$obj['location'],false,true);
				 } else {
   					 echo $this->ocw_utils->create_corep_img($cid,$mid,$obj['id'],$obj['location'],false,true);
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
});
</script>
