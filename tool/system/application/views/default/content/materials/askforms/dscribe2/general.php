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
       		<textarea name="<?=$obj['otype']?>_<?=$obj['id']?>_<?=$question['id']?>" rows="10" cols="62" 
					  				class="do_d2_question_update"><?=$question['answer']?></textarea>
       	</p>
       	<p><hr style="border: 1px solid #eee"/></p>
			<?php } ?>

			<!-- save options  -->	
			<br/><br/>
			<p>
 				<input type="submit" name="<?=$obj['otype']?>_status_<?=$obj['id']?>" 
							 value="Save for later" id="close_<?=$obj['id']?>" class="do_d2_question_update"/>
				&nbsp;&nbsp;
 				<input type="submit" name="<?=$obj['otype']?>_status_<?=$obj['id']?>" 
							 value="Send to dScribe" class="do_d2_question_update"/>
			</p>
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
						 $this->ocw_utils->create_co_img($cid,$mid,$obj['id'],$obj['location'],false,false);
				 } else {
   					 $this->ocw_utils->create_corep_img($cid,$mid,$obj['id'],$obj['location'],false,false);
				 }
			?>
			<br/><br/>

				<?php if ($obj['otype']=='original') { ?>
					<b>Content-Type:</b> <?=$this->coobject->get_subtype_name($obj['subtype_id'])?><br/><br/>
				<?php } ?>

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
						<b>Copyright Status:</b> <?=$c['status']?>
						<b>Copyright Holder:</b> <?=$c['holder']?>
						<b>Copyright Info URL:</b> <?=$c['url']?>
						<b>Copyright Notice:</b> <?=$c['notice']?>
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
						 $this->ocw_utils->create_co_img($cid,$mid,$obj['id'],$obj['location'],false,true);
				 } else {
   					 $this->ocw_utils->create_corep_img($cid,$mid,$obj['id'],$obj['location'],false,true);
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
